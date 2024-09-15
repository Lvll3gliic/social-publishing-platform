<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_display_users_posts_successfully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posts = Post::factory()->count(3)->create(['author_id' => $user->id]);

        $response = $this->get('/profile/' . $user->id);

        $response->assertStatus(200);

        foreach ($posts as $post) {
            $response->assertSee($post->title);
        }
    }

    public function test_show_no_posts_message_for_user_with_no_posts(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/profile/' . $user->id);

        $response->assertStatus(200);

        $response->assertSee('No posts available');
    }

    public function test_verify_posts_are_displayed_in_descending_order(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post1 = Post::factory()->create(['author_id' => $user->id, 'created_at' => now()->subDays(2)]);
        $post2 = Post::factory()->create(['author_id' => $user->id, 'created_at' => now()->subDay()]);
        $post3 = Post::factory()->create(['author_id' => $user->id, 'created_at' => now()]);

        $response = $this->get('/profile/' . $user->id);

        $response->assertStatus(200);

        $response->assertSeeInOrder([$post3->title, $post2->title, $post1->title]);
    }

    public function test_show_user_profile_information(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/profile/' . $user->id);

        $response->assertStatus(200);

        $response->assertSee($user->name);
    }
}
