<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $categories = Category::all();

        foreach (range(1, 20) as $index) {
            $user = User::inRandomOrder()->first();

            $post = Post::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'author_id' => $user->id,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
            ]);

            $post->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
