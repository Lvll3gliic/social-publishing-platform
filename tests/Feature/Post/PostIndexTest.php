<?php

namespace Tests\Feature\Post;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_main_feed_page_is_displayed(): void
    {
        $this->actingAsUser();


        $response = $this->get('/posts');

        $response->assertOk();
    }

    public function test_main_feed_is_not_accessible_to_unauthenticated(): void
    {
        $response = $this->get('/posts');

        $response->assertStatus(302);
    }

    public function test_redirect_to_login_page_if_unauthenticated(): void
    {
        $response = $this->get('/posts');

        $response->assertRedirect('/login');
    }

    public function test_index_page_displays_all_user_posts(): void
    {
        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            Post::factory()->count(2)->create(['author_id' => $user->id]);
        }

        $response = $this->actingAs($users->first())
            ->get('/posts');

        $response->assertStatus(200);

        foreach ($users as $user) {
            $response->assertSee($user->posts->first()->title);
        }
    }

    public function test_all_categories_are_available_for_filtering(): void
    {
        $this->actingAsUser();

        $categories = Category::factory()->count(5)->create();

        $response = $this->get('/posts');

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_filtering_posts_by_category(): void
    {
        $this->actingAsUser();

        $category1 = Category::factory()->create(['name' => 'Category 1']);
        $category2 = Category::factory()->create(['name' => 'Category 2']);

        $postInCategory1 = Post::factory()->create();
        $postInCategory1->categories()->attach($category1);

        $postInCategory2 = Post::factory()->create();
        $postInCategory2->categories()->attach($category2);

        $response = $this->get('/posts?category=Category+1');

        $response->assertStatus(200);
        $response->assertSee($postInCategory1->title);
        $response->assertDontSee($postInCategory2->title);

        $response = $this->get('/posts?category=Category+2');

        $response->assertStatus(200);
        $response->assertSee($postInCategory2->title);
        $response->assertDontSee($postInCategory1->title);

    }

    public function test_search_functionality(): void
    {
        $authenticatedUser = $this->actingAsUser();

        $post1 = Post::factory()->create([
            'title' => 'Unique Title 1',
            'content' => 'This is the content for the first post.',
            'author_id' => $authenticatedUser->id,
        ]);

        $post2 = Post::factory()->create([
            'title' => 'Unique Title 2',
            'content' => 'Content for the second post which is different.',
            'author_id' => $authenticatedUser->id,
        ]);

        $post3 = Post::factory()->create([
            'title' => 'Another Title',
            'content' => 'Some content that doesnt match the search term.',
            'author_id' => $authenticatedUser->id,
        ]);

        $response = $this->get('/posts?search=Unique+Title');

        $response->assertStatus(200);
        $response->assertSee($post1->title);
        $response->assertSee($post2->title);
        $response->assertDontSee($post3->title);

        $response = $this->get('/posts?search=second+post');

        $response->assertStatus(200);
        $response->assertDontSee($post1->title);
        $response->assertSee($post2->title);
        $response->assertDontSee($post3->title);
    }

    public function test_no_filters_applied_displays_all_posts(): void
    {
        $this->actingAsUser();

        $posts = Post::factory()->count(5)->create();

        $response = $this->get('/posts');

        $response->assertStatus(200);

        foreach ($posts as $post) {
            $response->assertSee($post->title);
        }
    }

    public function test_display_no_posts_for_invalid_category(): void
    {
        $this->actingAsUser();
        $response = $this->get('/posts?category=Invalid+Category');

        $response->assertStatus(200);
        $response->assertSee('No posts available');
    }

    public function test_verify_no_post_message(): void
    {
        $this->actingAsUser();

        $response = $this->get('/posts');

        $response->assertStatus(200);
        $response->assertSee('No posts available');
    }

    public function test_display_posts_with_comments_count(): void
    {
        $users = User::factory()->count(2)->create();

        foreach ($users as $user) {
            $posts = Post::factory()->count(5)->create(['author_id' => $user->id]);

            foreach ($posts as $post) {
                Comment::factory()->count(3)->create(['post_id' => $post->id]);
            }
        }

        $response = $this->actingAs($users->first())->get('/posts');

        $response->assertStatus(200);

        $postsWithComments = Post::withCount('comments')->get();

        foreach ($postsWithComments as $post) {
            $response->assertSee($post->title);
            $response->assertSee($post->comments_count . ' comments');
        }
    }

    public function actingAsUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }
}
