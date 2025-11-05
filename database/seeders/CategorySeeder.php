<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Latest electronic gadgets and devices.',
            'meta_title' => 'Buy Electronics Online',
            'meta_description' => 'Shop for the latest electronics including phones, TVs, and accessories.',
            'meta_keywords' => 'electronics, gadgets, tech',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'description' => 'Latest smartphones from top brands.',
            'meta_title' => 'Smartphones Online',
            'meta_description' => 'Explore new Android and iPhone models at great prices.',
            'meta_keywords' => 'smartphones, mobile phones, cell phones',
            'parent_id' => $electronics->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'description' => 'High-performance laptops for work and play.',
            'meta_title' => 'Buy Laptops',
            'meta_description' => 'Choose from gaming, business, and student laptops.',
            'meta_keywords' => 'laptops, notebooks, computers ',
            'parent_id' => $electronics->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Cameras',
            'slug' => 'cameras',
            'description' => 'Digital cameras and photography accessories.',
            'meta_title' => 'Digital Cameras & Lenses',
            'meta_description' => 'Capture your moments with top camera brands.',
            'meta_keywords' => 'cameras, photography, DSLR',
            'parent_id' => $electronics->id,
            'is_active' => true,
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Trendy clothing and accessories for all.',
            'meta_title' => 'Online Fashion Store',
            'meta_description' => 'Discover men’s and women’s clothing, shoes, and accessories.',
            'meta_keywords' => 'fashion, clothing, apparel',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Men’s Clothing',
            'slug' => 'mens-clothing',
            'description' => 'Stylish and comfortable clothes for men.',
            'meta_title' => 'Men’s Clothing',
            'meta_description' => 'Shop shirts, jeans, jackets, and more.',
            'meta_keywords' => 'menswear, men clothing, shirts',
            'parent_id' => $fashion->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Women’s Clothing',
            'slug' => 'womens-clothing',
            'description' => 'Elegant dresses, tops, and outfits for women.',
            'meta_title' => 'Women’s Fashion',
            'meta_description' => 'Explore the latest trends in women’s fashion.',
            'meta_keywords' => 'womenswear, dresses, tops',
            'parent_id' => $fashion->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Shoes',
            'slug' => 'shoes',
            'description' => 'Quality footwear for men, women, and kids.',
            'meta_title' => 'Shoes Online',
            'meta_description' => 'Find sneakers, boots, sandals, and heels.',
            'meta_keywords' => 'shoes, sneakers, footwear',
            'parent_id' => $fashion->id,
            'is_active' => true,
        ]);

        $home = Category::create([
            'name' => 'Home & Living',
            'slug' => 'home-living',
            'description' => 'Furniture, décor, and home essentials.',
            'meta_title' => 'Home & Living',
            'meta_description' => 'Transform your home with stylish furniture and décor.',
            'meta_keywords' => 'home, furniture, interior',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Furniture',
            'slug' => 'furniture',
            'description' => 'Modern and comfortable furniture for every room.',
            'meta_title' => 'Furniture Store',
            'meta_description' => 'Browse sofas, tables, and beds.',
            'meta_keywords' => 'furniture, home decor, sofas',
            'parent_id' => $home->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Kitchen & Dining',
            'slug' => 'kitchen-dining',
            'description' => 'Cookware, dinnerware, and kitchen appliances.',
            'meta_title' => 'Kitchen & Dining Essentials',
            'meta_description' => 'Everything you need for cooking and entertaining.',
            'meta_keywords' => 'kitchen, cookware, dining',
            'parent_id' => $home->id,
            'is_active' => true,
        ]);

        $beauty = Category::create([
            'name' => 'Beauty & Health',
            'slug' => 'beauty-health',
            'description' => 'Beauty products, skincare, and health essentials.',
            'meta_title' => 'Beauty & Health Store',
            'meta_description' => 'Shop makeup, skincare, and wellness products.',
            'meta_keywords' => 'beauty, skincare, health',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Makeup',
            'slug' => 'makeup',
            'description' => 'Cosmetics and beauty accessories.',
            'meta_title' => 'Makeup Products',
            'meta_description' => 'Discover top-rated lipsticks, foundations, and palettes.',
            'meta_keywords' => 'makeup, cosmetics, beauty',
            'parent_id' => $beauty->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Skincare',
            'slug' => 'skincare',
            'description' => 'Lotions, creams, and treatments for radiant skin.',
            'meta_title' => 'Skincare Essentials',
            'meta_description' => 'Keep your skin healthy and glowing.',
            'meta_keywords' => 'skincare, moisturizer, beauty',
            'parent_id' => $beauty->id,
            'is_active' => true,
        ]);

        $sports = Category::create([
            'name' => 'Sports & Outdoors',
            'slug' => 'sports-outdoors',
            'description' => 'Gear and equipment for sports and adventures.',
            'meta_title' => 'Sports & Outdoor Gear',
            'meta_description' => 'Shop fitness, camping, and cycling equipment.',
            'meta_keywords' => 'sports, fitness, outdoor',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Fitness Equipment',
            'slug' => 'fitness-equipment',
            'description' => 'Workout machines and accessories.',
            'meta_title' => 'Fitness Equipment',
            'meta_description' => 'Find treadmills, weights, and yoga mats.',
            'meta_keywords' => 'fitness, gym, exercise',
            'parent_id' => $sports->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Toys & Games',
            'slug' => 'toys-games',
            'description' => 'Fun toys and games for all ages.',
            'meta_title' => 'Toys & Games Store',
            'meta_description' => 'Explore educational toys and entertainment for kids.',
            'meta_keywords' => 'toys, games, kids',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Baby & Kids',
            'slug' => 'baby-kids',
            'description' => 'Everything for babies and children.',
            'meta_title' => 'Baby & Kids Products',
            'meta_description' => 'Clothing, toys, and accessories for little ones.',
            'meta_keywords' => 'baby, kids, children',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Books',
            'slug' => 'books',
            'description' => 'Explore fiction, non-fiction, and educational books.',
            'meta_title' => 'Online Bookstore',
            'meta_description' => 'Buy books across all genres and categories.',
            'meta_keywords' => 'books, reading, novels',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Pet Supplies',
            'slug' => 'pet-supplies',
            'description' => 'Food, toys, and accessories for pets.',
            'meta_title' => 'Pet Supplies',
            'meta_description' => 'Keep your pets happy and healthy.',
            'meta_keywords' => 'pets, pet food, pet care',
            'parent_id' => null,
            'is_active' => true,
        ]);
    }
}
