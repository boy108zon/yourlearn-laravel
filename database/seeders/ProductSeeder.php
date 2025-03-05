<?php

// database/seeders/ProductSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Using Faker to generate 10 sample products
        Product::create(['name' => 'Smartphone', 'description' => 'Latest smartphone with high-end specs', 'price' => 499.99, 'stock_quantity' => 50, 'weight' => 0.5, 'sku' => 'SKU123', 'is_active' => true]);
        Product::create(['name' => 'Laptop', 'description' => 'High-performance laptop for gaming and work', 'price' => 899.99, 'stock_quantity' => 30, 'weight' => 1.5, 'sku' => 'SKU124', 'is_active' => true]);
        Product::create(['name' => 'Washing Machine', 'description' => 'Automatic washing machine with multiple settings', 'price' => 350.00, 'stock_quantity' => 20, 'weight' => 8.0, 'sku' => 'SKU125', 'is_active' => true]);
        Product::create(['name' => 'Jacket', 'description' => 'Warm and stylish jacket for cold weather', 'price' => 79.99, 'stock_quantity' => 100, 'weight' => 1.2, 'sku' => 'SKU126', 'is_active' => true]);
        Product::create(['name' => 'Baseball Bat', 'description' => 'Durable wooden baseball bat for players', 'price' => 25.99, 'stock_quantity' => 50, 'weight' => 1.0, 'sku' => 'SKU127', 'is_active' => true]);
        Product::create(['name' => 'Toy Car', 'description' => 'Miniature toy car for kids', 'price' => 10.50, 'stock_quantity' => 150, 'weight' => 0.3, 'sku' => 'SKU128', 'is_active' => true]);
        Product::create(['name' => 'Office Chair', 'description' => 'Comfortable office chair for long working hours', 'price' => 150.00, 'stock_quantity' => 40, 'weight' => 5.0, 'sku' => 'SKU129', 'is_active' => true]);
        Product::create(['name' => 'Shampoo', 'description' => 'Hair care shampoo for normal to dry hair', 'price' => 12.99, 'stock_quantity' => 200, 'weight' => 0.4, 'sku' => 'SKU130', 'is_active' => true]);
        Product::create(['name' => 'Grocery Basket', 'description' => 'A set of grocery items in a basket', 'price' => 25.00, 'stock_quantity' => 75, 'weight' => 3.0, 'sku' => 'SKU131', 'is_active' => true]);
        Product::create(['name' => 'Car Tire', 'description' => 'Durable car tire for all seasons', 'price' => 80.00, 'stock_quantity' => 30, 'weight' => 9.0, 'sku' => 'SKU132', 'is_active' => true]);
    }
}
