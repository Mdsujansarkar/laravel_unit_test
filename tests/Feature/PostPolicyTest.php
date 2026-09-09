<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    #[Test]
    public function user_cannot_edit_another_users_post(): void
    {
        $author = User::factory()->create();
        $intruder = User::factory()->create();
        $post = Post::factory()->for($author)->create();

        $response = $this->actingAs($intruder)->put(route('posts.update', $post), [
            'title' => 'Hijacked Title',
            'body' => 'Hijacked body.',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $post->title,
        ]);
    }

    #[Test]
    public function user_cannot_delete_another_users_post(): void
    {
        $author = User::factory()->create();
        $intruder = User::factory()->create();
        $post = Post::factory()->for($author)->create();

        $response = $this->actingAs($intruder)->delete(route('posts.destroy', $post));

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
