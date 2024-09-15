<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_showing_the_create_post_form(): void
    {
        $this->actingAsUser();

        $response = $this->get('/posts/create');

        $response->assertStatus(200);
        $response->assertViewIs('posts.create');
        $response->assertSee('Create Post');
    }

    public function test_a_user_can_create_a_post_with_categories(): void
    {
        $authenticatedUser = $this->actingAsUser();
        $category = Category::factory()->create();


        $response = $this->post('/posts', [
            'title' => 'New Post Title',
            'content' => 'Content of the new post.',
            'categories' => [$category->id],
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', [
            'title' => 'New Post Title',
            'content' => 'Content of the new post.',
            'author_id' => $authenticatedUser->id,
        ]);

        $postId = \DB::table('posts')->where('title', 'New Post Title')->first()->id;
        $this->assertDatabaseHas('category_post', [
            'post_id' => $postId,
            'category_id' => $category->id,
        ]);
    }

    public function test_a_user_cannot_create_a_post_with_missing_title(): void
    {
        $this->actingAsUser();

        $response = $this->post('/posts', [
            'content' => 'Content of the new post.',
            'categories' => [],
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_a_user_cannot_create_a_post_with_missing_content(): void
    {
        $this->actingAsUser();

        $response = $this->post('/posts', [
            'title' => 'New Post Title',
            'categories' => [],
        ]);

        $response->assertSessionHasErrors('content');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_a_user_cannot_create_a_post_with_missing_categories(): void
    {
        $this->actingAsUser();

        $response = $this->post('/posts', [
            'title' => 'New Post Title',
            'content' => 'Content of the new post.',
        ]);

        $response->assertSessionHasErrors('categories');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_user_cannot_create_a_post_with_invalid_categories(): void
    {
        $this->actingAsUser();

        Category::factory()->create();
        $invalidCategoryId = 99999;

        $response = $this->post('/posts', [
            'title' => 'New Post Title',
            'content' => 'Content of the new post.',
            'categories' => [$invalidCategoryId],
        ]);

        $response->assertSessionHasErrors(['categories.0' => 'The selected categories.0 is invalid.']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_create_post_redirects_to_posts_index(): void
    {
        $this->actingAsUser();

        $category = Category::factory()->create();

        $response = $this->post('/posts', [
            'title' => 'New Post Title',
            'content' => 'Content of the new post.',
            'categories' => [$category->id],
        ]);

        $response->assertRedirect('/posts');
    }

    public function test_create_post_displays_validation_errors(): void
    {
        $this->actingAsUser();

        $response = $this->post('/posts', [
            'title' => '',
            'content' => '',
            'categories' => [],
        ]);

        $response->assertSessionHasErrors([
            'title',
            'content',
            'categories',
        ]);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_create_post_requires_authentication(): void
    {
        $response = $this->get('/posts/create');

        $response->assertRedirect('/login');

        $response = $this->post('/posts', [
            'title' => 'New Post Title',
            'content' => 'Content of the new post.',
            'categories' => [],
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_create_post_form_shows_categories(): void
    {
        $this->actingAsUser();

        $categories = Category::factory()->count(5)->create();

        $response = $this->get('/posts/create');

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_user_cannot_create_post_with_long_title(): void
    {
        $this->actingAsUser();

        $response = $this->post('/posts', [
            'title' => str_repeat('A', 256),
            'content' => 'Valid content.',
            'categories' => [],
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('posts', 0);
    }

    public function actingAsUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }
}
