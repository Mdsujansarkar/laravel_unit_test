<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthenticatedPostCrudTest extends TestCase
{
    #[Test]
    public function guest_is_redirected_to_login_when_creating_post(): void
    {
        $this->get(route('posts.create'))->assertRedirect(route('login'));
    }

    #[Test]
    public function user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'A Brand New Post',
            'body' => 'Some interesting content.',
        ]);

        $post = Post::where('slug', 'a-brand-new-post')->firstOrFail();
        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', [
            'title' => 'A Brand New Post',
            'user_id' => $user->id,
            'published' => false,
        ]);
    }

    #[Test]
    public function validation_fails_without_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => '',
            'body' => 'Some interesting content.',
        ]);

        $response->assertInvalid('title');
        $this->assertDatabaseCount('posts', 0);
    }

    #[Test]
    public function validation_fails_without_body(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Only a title',
        ]);

        $response->assertInvalid('body');
        $this->assertDatabaseCount('posts', 0);
    }

    #[Test]
    public function user_can_edit_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'body' => $post->body,
        ]);

        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    #[Test]
    public function user_can_delete_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    #[Test]
    public function create_form_shows_to_logged_in_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('posts.create'))->assertOk();
    }

    #[Test]
    public function edit_form_shows_to_owner(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user)->get(route('posts.edit', $post))->assertOk();
    }
}
