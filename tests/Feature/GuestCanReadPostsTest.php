<?php

namespace Tests\Feature;

use App\Models\Post;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GuestCanReadPostsTest extends TestCase
{
    #[Test]
    public function guest_can_view_index_of_published_posts(): void
    {
        $published = Post::factory()->published()->count(2)->create();
        $unpublished = Post::factory()->unpublished()->create();

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        foreach ($published as $post) {
            $response->assertSee($post->title);
        }
        $response->assertDontSee($unpublished->title);
    }

    #[Test]
    public function guest_can_view_a_single_published_post(): void
    {
        $post = Post::factory()->published()->create([
            'title' => 'My Great Post',
            'body' => 'A body worth reading.',
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee($post->title);
        $response->assertSee($post->body);
        $response->assertSee($post->author->name);
    }

    #[Test]
    public function guest_gets_404_for_missing_post(): void
    {
        $this->get(route('posts.show', 999))->assertNotFound();
    }

    #[Test]
    public function root_url_redirects_to_posts_index(): void
    {
        $this->get('/')->assertRedirect(route('posts.index'));
    }
}
