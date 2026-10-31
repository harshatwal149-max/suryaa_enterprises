<?php

namespace App\Support;

use GdImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Gives the public website smaller copies of uploaded images.
 *
 * A copy is created the first time a page asks for it and kept in
 * storage/app/public/resized, so every later visit gets a plain static file.
 * When a copy cannot be made the original image URL is used instead, so a
 * resizing problem can never break a page.
 */
class ResizedImage
{
    private const QUALITY = 78;

    private const RESIZABLE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /** Memory GD needs per pixel while decoding and scaling, including overhead. */
    private const BYTES_PER_PIXEL = 5;

    /** Generating stops after this long in one request; the next request carries on. */
    private const MAX_SECONDS_PER_REQUEST = 3.0;

    private static float $secondsSpentGenerating = 0.0;

    /**
     * URL of a copy of the image no wider than the given width.
     */
    public static function url(string $path, int $width): string
    {
        $path = ltrim($path, '/');
        $resizedPath = static::resizedPath($path, $width);

        return asset('storage/'.(static::ensureExists($path, $resizedPath, $width) ? $resizedPath : $path));
    }

    /**
     * Value for an img srcset attribute; empty for images that are not resized, such as SVGs.
     *
     * @param  array<int, int>  $widths
     */
    public static function srcset(string $path, array $widths): string
    {
        if (! static::isResizable($path)) {
            return '';
        }

        return collect($widths)
            ->map(fn (int $width) => static::url($path, $width).' '.$width.'w')
            ->implode(', ');
    }

    private static function isResizable(string $path): bool
    {
        return in_array(Str::lower(pathinfo($path, PATHINFO_EXTENSION)), self::RESIZABLE_EXTENSIONS, true);
    }

    /**
     * WebP when this server's GD can write it, otherwise the original format.
     */
    private static function resizedPath(string $path, int $width): string
    {
        $suffix = (imagetypes() & IMG_WEBP) ? '.webp' : '';

        return 'resized/'.$width.'/'.$path.$suffix;
    }

    private static function ensureExists(string $path, string $resizedPath, int $width): bool
    {
        $disk = Storage::disk('public');
        $source = $disk->path($path);
        $target = $disk->path($resizedPath);

        if (! static::isResizable($path) || ! is_file($source)) {
            return false;
        }

        if (is_file($target) && filemtime($target) >= filemtime($source)) {
            return true;
        }

        if (self::$secondsSpentGenerating >= self::MAX_SECONDS_PER_REQUEST) {
            return false;
        }

        $startedAt = microtime(true);

        try {
            return static::generate($source, $target, $width);
        } catch (Throwable $exception) {
            report($exception);

            return false;
        } finally {
            self::$secondsSpentGenerating += microtime(true) - $startedAt;
        }
    }

    private static function generate(string $source, string $target, int $width): bool
    {
        if (! static::fitsInMemory($source)) {
            return false;
        }

        $image = imagecreatefromstring(File::get($source));

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $image = static::applyExifOrientation($image, $source);

        $resized = imagescale($image, min($width, imagesx($image)), -1, IMG_BICUBIC);

        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        File::ensureDirectoryExists(dirname($target));

        // Write beside the target and rename, so a visitor never gets a half-written file.
        $temporary = tempnam(dirname($target), 'resize');

        match (Str::lower(pathinfo($target, PATHINFO_EXTENSION))) {
            'webp' => imagewebp($resized, $temporary, self::QUALITY),
            'png' => imagepng($resized, $temporary, 9),
            default => static::writeJpeg($resized, $temporary),
        };

        chmod($temporary, 0644);

        return rename($temporary, $target);
    }

    private static function fitsInMemory(string $source): bool
    {
        $size = getimagesize($source);

        if ($size === false) {
            return false;
        }

        $limit = ini_parse_quantity((string) ini_get('memory_limit'));

        return $limit <= 0
            || $limit - memory_get_usage() > $size[0] * $size[1] * self::BYTES_PER_PIXEL;
    }

    /**
     * GD ignores the EXIF rotation that phone cameras rely on, which browsers honour.
     */
    private static function applyExifOrientation(GdImage $image, string $source): GdImage
    {
        if (! function_exists('exif_read_data') || exif_imagetype($source) !== IMAGETYPE_JPEG) {
            return $image;
        }

        $angle = match ((int) (@exif_read_data($source)['Orientation'] ?? 1)) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        return $angle === 0 ? $image : imagerotate($image, $angle, 0);
    }

    private static function writeJpeg(GdImage $image, string $target): void
    {
        imageinterlace($image, true);
        imagejpeg($image, $target, self::QUALITY);
    }
}
