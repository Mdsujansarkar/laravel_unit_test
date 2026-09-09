<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Seed a demo author and their posts.
     */
    public function run(): void
    {
        $author = User::factory()->create([
            'name' => 'Demo Author',
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        Post::factory()->published()->count(5)->for($author)->create();
        Post::factory()->unpublished()->count(2)->for($author)->create();
    }
}
