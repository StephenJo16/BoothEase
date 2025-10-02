<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Food & Beverage', 'Retail', 'Technology', 'Health & Wellness', 'Arts & Crafts', 'Education', 'Entertainment', 'Travel & Tourism', 'Automotive', 'Real Estate'];

        foreach ($categories as $category) {
            \App\Models\Category::create(['name' => $category]);
        }
    }
}
