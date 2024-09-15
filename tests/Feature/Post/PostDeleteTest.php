<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_post_successfully_as_author(): void
    {
       $user = $this->actingAsUser();

        $post = Post::factory()->create(['author_id' => $user->id]);

        $response = $this->delete('/posts/' . $post->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_prevent_non_author_from_deleting_post(): void
    {
        $this->actingAsUser();

        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $otherUser->id]);

        $response = $this->delete('/posts/' . $post->id);

        $response->assertStatus(302);
        $response->assertRedirect('/posts');
    }

    public function test_post_deletion_redirects_or_shows_success_message(): void
    {
        $user = $this->actingAsUser();

        $post = Post::factory()->create(['author_id' => $user->id]);

        $response = $this->delete('/posts/' . $post->id);

        $response->assertRedirect('/posts');

        $response->assertSessionHas('success', 'Post deleted successfully.');
    }

    public function test_verify_post_is_removed_from_database_after_deletion(): void
    {
        $user = $this->actingAsUser();

        $post = Post::factory()->create(['author_id' => $user->id]);

        $this->delete('/posts/' . $post->id);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function actingAsUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }
}
