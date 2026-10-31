<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Support\ResizedImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResizedImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_it_creates_a_smaller_copy_and_returns_its_url(): void
    {
        $this->storeJpeg('products/panel.jpg', 800, 400);

        $url = ResizedImage::url('products/panel.jpg', 320);

        $this->assertStringContainsString('storage/resized/320/products/panel.jpg', $url);
        $this->assertSame([320, 160], $this->dimensionsOf($url));
    }

    public function test_it_never_enlarges_an_image(): void
    {
        $this->storeJpeg('products/small.jpg', 200, 100);

        $this->assertSame([200, 100], $this->dimensionsOf(ResizedImage::url('products/small.jpg', 640)));
    }

    public function test_it_replaces_the_copy_when_the_original_changes(): void
    {
        $this->storeJpeg('products/panel.jpg', 800, 400);
        $url = ResizedImage::url('products/panel.jpg', 320);
        touch(Storage::disk('public')->path($this->resizedPathOf($url)), time() - 60);

        $this->storeJpeg('products/panel.jpg', 800, 200);

        $this->assertSame([320, 80], $this->dimensionsOf(ResizedImage::url('products/panel.jpg', 320)));
    }

    public function test_it_falls_back_to_the_original_for_files_it_cannot_resize(): void
    {
        Storage::disk('public')->put('site-settings/logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"/>');

        $this->assertStringEndsWith('storage/site-settings/logo.svg', ResizedImage::url('site-settings/logo.svg', 320));
        $this->assertSame('', ResizedImage::srcset('site-settings/logo.svg', [320, 640]));
        $this->assertStringEndsWith('storage/products/missing.jpg', ResizedImage::url('products/missing.jpg', 320));
    }

    public function test_it_falls_back_to_the_original_for_a_corrupt_image(): void
    {
        Storage::disk('public')->put('products/corrupt.jpg', 'not an image');

        $this->assertStringEndsWith('storage/products/corrupt.jpg', ResizedImage::url('products/corrupt.jpg', 320));
    }

    public function test_it_keeps_png_transparency(): void
    {
        $image = imagecreatetruecolor(400, 400);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        ob_start();
        imagepng($image);
        Storage::disk('public')->put('site-settings/logo.png', ob_get_clean());

        $url = ResizedImage::url('site-settings/logo.png', 160);

        $resized = imagecreatefromstring(Storage::disk('public')->get($this->resizedPathOf($url)));
        $this->assertSame(127, imagecolorat($resized, 10, 10) >> 24 & 0x7F);
    }

    public function test_it_applies_the_exif_rotation_of_phone_photos(): void
    {
        $this->storeJpeg('products/phone.jpg', 800, 400, exifOrientation: 6);

        $this->assertSame([320, 640], $this->dimensionsOf(ResizedImage::url('products/phone.jpg', 320)));
    }

    public function test_srcset_lists_every_width(): void
    {
        $this->storeJpeg('products/panel.jpg', 800, 400);

        $srcset = ResizedImage::srcset('products/panel.jpg', [320, 640]);

        $this->assertStringContainsString('resized/320/products/panel.jpg', $srcset);
        $this->assertStringContainsString(' 320w, ', $srcset);
        $this->assertStringEndsWith(' 640w', $srcset);
    }

    public function test_homepage_loads_only_the_first_hero_slide_up_front(): void
    {
        foreach (['one', 'two', 'three'] as $index => $name) {
            $this->storeJpeg("hero-slides/{$name}.jpg", 800, 400);
            HeroSlide::create([
                'image_path' => "hero-slides/{$name}.jpg",
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('<img class="sh-slide-bg" src="', $html);
        $this->assertSame(2, substr_count($html, '<img class="sh-slide-bg" data-src="'));
        $this->assertStringContainsString('rel="preload" as="image"', $html);
    }

    public function test_jquery_and_toastr_load_only_when_a_page_needs_them(): void
    {
        $this->get('/')
            ->assertDontSee('jquery.min.js')
            ->assertDontSee('toastr.min.css');

        $this->withSession(['success' => 'Thanks'])
            ->get('/')
            ->assertSee('jquery.min.js')
            ->assertSee('toastr.min.css');
    }

    private function storeJpeg(string $path, int $width, int $height, ?int $exifOrientation = null): void
    {
        ob_start();
        imagejpeg(imagecreatetruecolor($width, $height));
        $jpeg = ob_get_clean();

        if ($exifOrientation !== null) {
            $tiff = "MM\x00\x2A\x00\x00\x00\x08\x00\x01\x01\x12\x00\x03\x00\x00\x00\x01"
                .pack('n', $exifOrientation)."\x00\x00\x00\x00\x00\x00";
            $exif = "Exif\x00\x00".$tiff;
            $jpeg = substr($jpeg, 0, 2)."\xFF\xE1".pack('n', strlen($exif) + 2).$exif.substr($jpeg, 2);
        }

        Storage::disk('public')->put($path, $jpeg);
    }

    private function resizedPathOf(string $url): string
    {
        return Str::after($url, 'storage/');
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function dimensionsOf(string $url): array
    {
        $size = getimagesize(Storage::disk('public')->path($this->resizedPathOf($url)));

        return [$size[0], $size[1]];
    }
}
