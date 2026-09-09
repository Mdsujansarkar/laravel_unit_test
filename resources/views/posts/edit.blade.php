@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <h1>Edit Post</h1>

    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        @method('PUT')
        <p>
            <label for="title">Title</label>
            <input id="title" type="text" name="title" value="{{ old('title', $post->title) }}">
            @error('title')<span class="muted">{{ $message }}</span>@enderror
        </p>
        <p>
            <label for="body">Body</label>
            <textarea id="body" name="body" rows="8">{{ old('body', $post->body) }}</textarea>
            @error('body')<span class="muted">{{ $message }}</span>@enderror
        </p>
        <label>
            <input type="checkbox" name="published" value="1" @checked(old('published', $post->published))>
            Published
        </label>
        <button type="submit">Save</button>
    </form>
@endsection
