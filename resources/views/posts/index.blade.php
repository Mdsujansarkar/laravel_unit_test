@extends('layouts.app')

@section('content')
    <h1>Posts</h1>

    @forelse ($posts as $post)
        <article>
            <h2><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h2>
            <p class="muted">by {{ $post->author->name }}</p>
            <p>{{ Str::limit($post->body, 140) }}</p>
        </article>
    @empty
        <p>No posts yet.</p>
    @endforelse
@endsection
