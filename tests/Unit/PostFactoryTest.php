<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostFactoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function factory_creates_a_persisted_post_with_an_author(): void
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $post->title,
        ]);
        $this->assertInstanceOf(User::class, $post->author);
    }

    #[Test]
    public function published_casts_to_boolean(): void
    {
        $post = Post::factory()->create(['published' => 1]);

        $this->assertIsBool($post->published);
        $this->assertTrue($post->published);
    }

    #[Test]
    public function slug_is_auto_generated_from_title(): void
    {
        $post = Post::factory()->create(['title' => 'Hello Testing World']);

        $this->assertSame('hello-testing-world', $post->slug);
        $this->assertDatabaseHas('posts', ['slug' => 'hello-testing-world']);
    }

    #[Test]
    public function factory_states_control_publication_status(): void
    {
        $published = Post::factory()->published()->create();
        $unpublished = Post::factory()->unpublished()->create();

        $this->assertTrue($published->published);
        $this->assertFalse($unpublished->published);
    }
}
