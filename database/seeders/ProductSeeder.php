<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed 20 dummy Suryaa Enterprises solar products.
     * Safe to re-run: products are matched by SKU.
     */
    public function run(): void
    {
        $categories = collect([
            'Solar water heater' => 'Glass line and ETC solar water heaters for homes, hotels and hostels.',
            'Solar Street Lights' => 'All-in-one and semi-integrated LED solar street lights.',
            'Solar Power Plants' => 'On-grid, off-grid and hybrid rooftop solar power systems.',
            'Solar Fencing' => 'Solar powered fencing systems for farms and orchards.',
            'Solar Home Lighting' => 'Solar lanterns, home lighting systems and inverters.',
        ])->mapWithKeys(function (string $description, string $name) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                    'is_active' => true,
                ]
            );

            return [$category->slug => $category->id];
        });

        $products = [
            // Solar water heaters
            ['solar-water-heater', 'Super Solar Glass Line Water Heater 100 LPD', 'SE-SWH-GL-100', 24500, 22500, 15, '100 LPD', 'Super Solar', 'Glass lined inner tank solar water heater, ideal for small families in hilly regions. Corrosion-resistant tank with PUF insulation keeps water hot overnight.'],
            ['solar-water-heater', 'Super Solar Glass Line Water Heater 200 LPD', 'SE-SWH-GL-200', 38500, 35900, 10, '200 LPD', 'Super Solar', 'Glass line solar water heater for medium families. Works efficiently in Kangra winters with high-efficiency evacuated tubes.'],
            ['solar-water-heater', 'Super Solar Glass Line Water Heater 300 LPD', 'SE-SWH-GL-300', 52000, 48500, 6, '300 LPD', 'Super Solar', 'Large capacity glass line system suitable for homestays and guest houses.'],
            ['solar-water-heater', 'ETC Solar Water Heater 150 LPD', 'SE-SWH-ETC-150', 21000, null, 12, '150 LPD', 'Suryaa', 'Evacuated tube collector based water heater with stainless steel inner tank. Economical choice for daily hot water needs.'],
            ['solar-water-heater', 'FPC Solar Water Heater 500 LPD (Commercial)', 'SE-SWH-FPC-500', 115000, 109000, 3, '500 LPD', 'Super Solar', 'Flat plate collector system for hotels, hostels and hospitals. Works with hard water and supports pressurised supply.'],

            // Solar street lights
            ['solar-street-lights', 'All-in-One Solar Street Light 20W', 'SE-SSL-AIO-20', 7500, 6800, 40, '20W', 'Super Solar', 'Integrated LED street light with lithium battery and PIR motion sensor. Dusk-to-dawn automatic operation.'],
            ['solar-street-lights', 'All-in-One Solar Street Light 40W', 'SE-SSL-AIO-40', 12500, 11500, 25, '40W', 'Super Solar', 'High-brightness integrated solar street light for village roads, colonies and campuses.'],
            ['solar-street-lights', 'Semi-Integrated Solar Street Light 30W', 'SE-SSL-SI-30', 16500, null, 18, '30W', 'Suryaa', 'Separate panel with integrated LiFePO4 battery and LED luminaire. Includes pole mounting bracket.'],
            ['solar-street-lights', 'Solar Street Light Pole 6 Metre', 'SE-SSL-POLE-6M', 9800, 9200, 20, '6 m', 'Suryaa', 'Hot-dip galvanised octagonal pole with base plate and foundation bolts for solar street lights.'],

            // Solar power plants
            ['solar-power-plants', '1 kW On-Grid Rooftop Solar System', 'SE-SPP-ON-1KW', 65000, 60000, 8, '1 kW', 'Super Solar', 'Grid-tied rooftop system with net metering support. Eligible for PM Surya Ghar subsidy.'],
            ['solar-power-plants', '3 kW On-Grid Rooftop Solar System', 'SE-SPP-ON-3KW', 175000, 165000, 5, '3 kW', 'Super Solar', 'Ideal for homes with monthly consumption of 300-400 units. Includes mono PERC panels, inverter and structure.'],
            ['solar-power-plants', '2 kW Off-Grid Solar Power System', 'SE-SPP-OFF-2KW', 125000, null, 4, '2 kW', 'Suryaa', 'Battery backed solar system for remote areas with frequent power cuts. Includes solar PCU and tubular batteries.'],
            ['solar-power-plants', '5 kW Hybrid Solar Power System', 'SE-SPP-HYB-5KW', 320000, 299000, 2, '5 kW', 'Super Solar', 'Hybrid system that runs on solar, battery and grid. Suitable for shops, clinics and large homes.'],
            ['solar-power-plants', 'Mono PERC Solar Panel 540W', 'SE-SPP-PNL-540', 16500, 15200, 60, '540 Wp', 'Super Solar', 'High-efficiency half-cut mono PERC module with 25 year performance warranty.'],

            // Solar fencing
            ['solar-fencing', 'Solar Fence Energizer 12V', 'SE-SF-ENG-12V', 14500, 13500, 15, '12V', 'Suryaa', 'Solar fence energizer for protecting crops and orchards from wild animals and monkeys.'],
            ['solar-fencing', 'Complete Solar Fencing Kit (1 Acre)', 'SE-SF-KIT-1AC', 45000, 42000, 6, '1 Acre', 'Suryaa', 'Includes energizer, solar panel, battery, HT wire, insulators and warning signs for one acre of farmland.'],

            // Solar home lighting
            ['solar-home-lighting', 'Solar LED Lantern with Mobile Charger', 'SE-SHL-LANT-01', 1450, 1250, 80, '5W', 'Super Solar', 'Portable solar lantern with USB mobile charging. Up to 8 hours backup on full charge.'],
            ['solar-home-lighting', 'Solar Home Lighting System (3 Lights + Fan)', 'SE-SHL-HLS-3F', 9500, 8900, 30, '40 Wp', 'Super Solar', 'Complete DC home lighting kit with 3 LED bulbs, 1 DC fan, battery and solar panel.'],
            ['solar-home-lighting', 'Solar Garden Light (Pack of 2)', 'SE-SHL-GDN-2', 2200, null, 50, '3W', 'Suryaa', 'Decorative weatherproof solar garden lights with automatic on/off.'],
            ['solar-home-lighting', 'Solar Inverter PCU 1 kVA', 'SE-SHL-PCU-1KVA', 18500, 17200, 12, '1 kVA / 12V', 'Super Solar', 'Solar power conditioning unit with MPPT charge controller and grid charging priority settings.'],
        ];

        foreach ($products as [$categorySlug, $name, $sku, $price, $discountPrice, $quantity, $size, $brand, $description]) {
            $slug = Str::slug($name);
            $imagePath = $this->publishImage($sku, $slug);

            Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'category_id' => $categories[$categorySlug],
                    'subcategory_id' => null,
                    'name' => $name,
                    'slug' => $slug,
                    'price' => $price,
                    'discount_price' => $discountPrice,
                    'quantity' => $quantity,
                    'size' => $size,
                    'brand' => $brand,
                    'description' => $description,
                    'image' => $imagePath,
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Copy the bundled demo photo for a SKU to the public disk.
     * Photos live in database/seeders/images/products (see CREDITS.md there).
     */
    private function publishImage(string $sku, string $slug): ?string
    {
        $source = collect(glob(__DIR__ . '/images/products/' . strtolower($sku) . '.*'))->first();

        if ($source === null) {
            return null;
        }

        $path = 'products/demo/' . $slug . '.' . pathinfo($source, PATHINFO_EXTENSION);

        Storage::disk('public')->put($path, file_get_contents($source));

        return $path;
    }
}
