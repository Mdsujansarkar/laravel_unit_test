<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function password_is_auto_hashed_by_cast(): void
    {
        $user = User::factory()->create(['password' => 'plain-secret']);

        $this->assertNotSame('plain-secret', $user->password);
        $this->assertTrue(Hash::check('plain-secret', $user->password));
    }

    #[Test]
    public function posts_relation_returns_authors_posts(): void
    {
        $user = User::factory()->create();
        $ownPost = Post::factory()->for($user)->create();
        Post::factory()->count(2)->create();

        $posts = $user->posts;

        $this->assertCount(1, $posts);
        $this->assertTrue($posts->first()->is($ownPost));
    }

    #[Test]
    public function deleting_user_cascades_to_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(2)->for($user)->create();

        $user->delete();

        $this->assertDatabaseCount('posts', 0);
    }

    #[Test]
    public function hidden_attributes_are_not_serialized(): void
    {
        $user = User::factory()->create();

        $serialized = $user->toArray();

        $this->assertArrayNotHasKey('password', $serialized);
        $this->assertArrayNotHasKey('remember_token', $serialized);
    }
}
