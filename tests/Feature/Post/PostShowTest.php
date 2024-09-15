<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_showing_an_existing_post(): void
    {
        $authenticatedUser = $this->actingAsUser();

        $post = Post::factory()->create(['author_id' => $authenticatedUser->id]);

        $response = $this->get("/posts/{$post->id}");

        $response->assertStatus(200);

        $response->assertSee($post->title);
        $response->assertSee($post->content);
        $response->assertSee($post->author->name);
    }

    public function test_showing_a_non_existent_post(): void
    {
        $this->actingAsUser();

        $nonExistentPostId = 9999;

        $response = $this->get("/posts/{$nonExistentPostId}");

        $response->assertStatus(404);
    }

    public function test_display_post_comments(): void
    {
        $user = $this->actingAsUser();

        $post = Post::factory()->create(['author_id' => $user->id]);

        $comments = Comment::factory()->count(5)->create(['post_id' => $post->id]);

        $response = $this->get('/posts/' . $post->id);

        $response->assertStatus(200);

        $response->assertSee($post->title);
        $response->assertSee($post->content);

        foreach ($comments as $comment) {
            $response->assertSee($comment->content);
        }
    }

    public function test_show_no_comments_message_when_no_comments_exist(): void
    {
        $user = $this->actingAsUser();

        $post = Post::factory()->create(['author_id' => $user->id]);

        $response = $this->get('/posts/' . $post->id);

        $response->assertStatus(200);

        $response->assertSee($post->title);
        $response->assertSee($post->content);

        $response->assertSee('No comments available');
    }

    public function test_display_post_creation_date(): void
    {
        $user = $this->actingAsUser();

        $creationDate = now();
        $post = Post::factory()->create([
            'author_id' => $user->id,
            'created_at' => $creationDate,
            'updated_at' => $creationDate
        ]);

        $response = $this->get('/posts/' . $post->id);

        $response->assertStatus(200);

        $response->assertSee($post->title);
        $response->assertSee($post->content);

        $formattedDate = $creationDate->format('F j, Y');

        $response->assertSee($formattedDate);
    }

    public function test_ensure_unauthorized_users_cannot_view_post(): void
    {
        $user =  User::factory()->create();
        $post = Post::factory()->create(['author_id' => $user->id]);

        $response = $this->get('/posts/' . $post->id);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function actingAsUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }
}
