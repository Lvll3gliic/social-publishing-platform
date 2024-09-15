<?php

declare(strict_types=1);

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
        $categories = [
            'Technology',
            'Health',
            'Lifestyle',
            'Sports',
            'Business',
        ];

        foreach ($categories as $category) {
            Category::factory()->withName($category)->create();
        }
    }
}
