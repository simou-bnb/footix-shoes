<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Wilaya;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedDeliveryPriceExamples();

        $categories = [
            'Chaussures' => ['sort_order' => 1],
            'Vêtements' => ['sort_order' => 2],
            'Accessoires' => ['sort_order' => 3],
        ];

        $categoryModels = [];

        foreach ($categories as $name => $attributes) {
            $categoryModels[$name] = Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                array_merge(['name' => $name, 'is_active' => true], $attributes)
            );
        }

        $shoeSizes = ['40', '41', '42', '43', '44'];
        $clothingSizes = ['S', 'M', 'L', 'XL'];

        $products = [
            ['Chaussures', 'Nike Air Force 1', 12500, $this->sizeColorVariants($shoeSizes, ['Blanc', 'Noir'])],
            ['Chaussures', 'Adidas Stan Smith', 11000, $this->sizeColorVariants($shoeSizes, ['Blanc'])],
            ['Chaussures', 'Puma Suede Classic', 9500, $this->sizeColorVariants($shoeSizes, ['Bleu', 'Noir'])],
            ['Chaussures', 'Nike Air Max 270', 14000, $this->sizeColorVariants($shoeSizes, ['Noir'])],
            ['Chaussures', 'Converse Chuck Taylor', 8000, $this->sizeColorVariants($shoeSizes, ['Rouge', 'Noir'])],
            ['Chaussures', 'New Balance 574', 13000, $this->sizeColorVariants($shoeSizes, ['Gris'])],
            ['Vêtements', 'T-shirt Basique Coton', 2500, $this->sizeColorVariants($clothingSizes, ['Blanc', 'Noir'])],
            ['Vêtements', 'Sweat à Capuche Oversize', 5500, $this->sizeColorVariants($clothingSizes, ['Gris', 'Noir'])],
            ['Vêtements', 'Jogging Slim Fit', 4500, $this->sizeColorVariants($clothingSizes, ['Noir'])],
            ['Vêtements', 'Veste Bomber', 7500, $this->sizeColorVariants($clothingSizes, ['Kaki', 'Noir'])],
            ['Vêtements', 'Polo Classique', 3500, $this->sizeColorVariants($clothingSizes, ['Bleu marine', 'Blanc'])],
            ['Accessoires', 'Casquette Snapback', 2000, $this->colorOnlyVariants(['Noir', 'Blanc', 'Rouge'])],
            ['Accessoires', 'Sac à Dos Sport', 4000, $this->colorOnlyVariants(['Noir', 'Gris'])],
            ['Accessoires', 'Ceinture Cuir', 2500, $this->colorOnlyVariants(['Noir', 'Marron'])],
            ['Accessoires', 'Chaussettes (lot de 3)', 1500, $this->standardVariant()],
        ];

        foreach ($products as [$categoryName, $name, $price, $variants]) {
            $product = Product::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                [
                    'category_id' => $categoryModels[$categoryName]->id,
                    'name' => $name,
                    'base_price' => $price,
                    'is_active' => true,
                ]
            );

            if ($product->variants()->exists()) {
                continue;
            }

            foreach ($variants as $variant) {
                $product->variants()->create([
                    'size' => $variant['size'] ?? null,
                    'color' => $variant['color'] ?? null,
                    'stock' => fake()->numberBetween(0, 20),
                    'is_active' => true,
                ]);
            }
        }

        $this->seedPlaceholderPhotos($categoryModels);
    }

    /**
     * A couple of sizes per color, not the full cartesian product, to keep demo data readable.
     */
    protected function sizeColorVariants(array $sizes, array $colors): array
    {
        $variants = [];

        foreach ($colors as $color) {
            foreach (fake()->randomElements($sizes, min(3, count($sizes))) as $size) {
                $variants[] = ['size' => $size, 'color' => $color];
            }
        }

        return $variants;
    }

    protected function colorOnlyVariants(array $colors): array
    {
        return array_map(fn ($color) => ['color' => $color], $colors);
    }

    protected function standardVariant(): array
    {
        return [[]];
    }

    /**
     * Rough distance-from-Alger price tiers, just so the checkout doesn't show
     * "0 DA" everywhere in the demo. These are EXAMPLES for the admin to correct
     * with real courier prices from the Wilayas screen — not real rates.
     */
    protected function seedDeliveryPriceExamples(): void
    {
        $tiers = [
            // [home, stopdesk-or-null]
            1 => [400, 250],   // Alger and immediate surroundings
            2 => [500, 350],   // Northern / coastal, moderate distance
            3 => [650, 450],   // Highlands / central
            4 => [900, 600],   // Pre-Saharan
            5 => [1300, null], // Deep south — often no stop desk
        ];

        $wilayaTiers = [
            '16' => 1, '09' => 1, '35' => 1, '42' => 1,
            '02' => 2, '06' => 2, '13' => 2, '14' => 2, '15' => 2, '18' => 2, '19' => 2,
            '21' => 2, '22' => 2, '23' => 2, '24' => 2, '25' => 2, '26' => 2, '27' => 2,
            '29' => 2, '31' => 2, '34' => 2, '36' => 2, '38' => 2, '41' => 2, '43' => 2,
            '44' => 2, '46' => 2, '48' => 2, '10' => 2,
            '03' => 3, '04' => 3, '05' => 3, '07' => 3, '12' => 3, '17' => 3, '20' => 3,
            '28' => 3, '40' => 3, '51' => 3,
            '08' => 4, '30' => 4, '32' => 4, '39' => 4, '45' => 4, '47' => 4, '52' => 4,
            '55' => 4, '57' => 4, '58' => 4,
            '01' => 5, '11' => 5, '33' => 5, '37' => 5, '49' => 5, '50' => 5, '53' => 5,
            '54' => 5, '56' => 5,
        ];

        foreach ($wilayaTiers as $code => $tier) {
            [$home, $stopdesk] = $tiers[$tier];

            Wilaya::where('code', $code)->update([
                'home_delivery_price' => $home,
                'stopdesk_delivery_price' => $stopdesk,
            ]);
        }
    }

    /**
     * Assigns the placeholder photos already sitting in storage/app/public/{categories,products}
     * (downloaded separately, checked for content) to demo categories/products that don't have
     * a photo yet — skips anything that already has one (e.g. a photo uploaded by hand in Filament).
     */
    protected function seedPlaceholderPhotos(array $categoryModels): void
    {
        foreach ($categoryModels as $category) {
            $path = 'categories/'.$category->slug.'.jpg';

            if (! $category->image && Storage::disk('public')->exists($path)) {
                $category->update(['image' => $path]);
            }
        }

        Product::whereDoesntHave('images')->get()->each(function (Product $product) {
            $path = 'products/'.$product->slug.'.jpg';

            if (Storage::disk('public')->exists($path)) {
                $product->images()->create([
                    'path' => $path,
                    'sort_order' => 0,
                    'is_primary' => true,
                ]);
            }
        });
    }
}
