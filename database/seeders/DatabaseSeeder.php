<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = Product::factory(10)->create();

        foreach ($products as $product) {
            $ingredients = Ingredient::factory(3)->create();
            $product->ingredients()->syncWithPivotValues($ingredients, ['quantity'=>10]);
        }
    }
}
