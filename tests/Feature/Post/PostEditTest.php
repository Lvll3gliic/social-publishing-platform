<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_allow_author_to_edit_post(): void
    {
        $user = $this->actingAsUser();
        $post = Post::factory()->create(['author_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content'
        ];

        $response = $this->put('/posts/' . $post->id, $updatedData);

        $response->assertStatus(302);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated Content'
        ]);
    }

    public function test_prevent_non_author_from_editing_post(): void
    {
        $this->actingAsUser();

        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $otherUser->id]);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content'
        ];

        $response = $this->put('/posts/' . $post->id, $updatedData);

        $response->assertStatus(302);
    }

    public function test_post_edit_redirects_or_shows_success_message(): void
    {
        $user = $this->actingAsUser();

        $post = Post::factory()->create(['author_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content'
        ];

        $response = $this->put('/posts/' . $post->id, $updatedData);

        $response->assertRedirect('/posts/' . $post->id);
        $response->assertSessionHas('success', 'Post updated successfully.');
    }

    public function test_verify_post_changes_in_database(): void
    {
        $user = $this->actingAsUser();

        $post = Post::factory()->create(['author_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content'
        ];

        $this->put('/posts/' . $post->id, $updatedData);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated Content'
        ]);
    }

    public function actingAsUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }
}
