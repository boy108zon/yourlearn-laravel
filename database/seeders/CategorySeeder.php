<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Using Faker to generate 10 sample categories
        Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic gadgets and devices', 'is_active' => true]);
        Category::create(['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Men and Women clothing', 'is_active' => true]);
        Category::create(['name' => 'Home Appliances', 'slug' => 'home-appliances', 'description' => 'Household appliances for daily use', 'is_active' => true]);
        Category::create(['name' => 'Books', 'slug' => 'books', 'description' => 'Books for all genres', 'is_active' => true]);
        Category::create(['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment and gear', 'is_active' => true]);
        Category::create(['name' => 'Toys', 'slug' => 'toys', 'description' => 'Toys for children of all ages', 'is_active' => true]);
        Category::create(['name' => 'Furniture', 'slug' => 'furniture', 'description' => 'Furniture for home and office', 'is_active' => true]);
        Category::create(['name' => 'Beauty', 'slug' => 'beauty', 'description' => 'Beauty products and skincare', 'is_active' => true]);
        Category::create(['name' => 'Food & Groceries', 'slug' => 'food-groceries', 'description' => 'Food items and groceries', 'is_active' => true]);
        Category::create(['name' => 'Automotive', 'slug' => 'automotive', 'description' => 'Car and motorcycle accessories', 'is_active' => true]);
    }
}
