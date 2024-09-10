<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $faker = Faker::create();

       foreach (range(1, 30) as $index) {
           $user = User::inRandomOrder()->first();
           $post = Post::inRandomOrder()->first();

           Comment::create([
               'comment' => $faker->paragraph,
               'user_id' => $user->id,
               'post_id' => $post->id,
               'created_at' => $faker->dateTimeThisYear,
               'updated_at' => $faker->dateTimeThisYear,
           ]);
       }
    }
}
