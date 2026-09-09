@extends('layouts.app')

@section('title', 'New Post')

@section('content')
    <h1>New Post</h1>

    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        <p>
            <label for="title">Title</label>
            <input id="title" type="text" name="title" value="{{ old('title') }}">
            @error('title')<span class="muted">{{ $message }}</span>@enderror
        </p>
        <p>
            <label for="body">Body</label>
            <textarea id="body" name="body" rows="8">{{ old('body') }}</textarea>
            @error('body')<span class="muted">{{ $message }}</span>@enderror
        </p>
        <label>
            <input type="checkbox" name="published" value="1" @checked(old('published'))>
            Published
        </label>
        <button type="submit">Save</button>
    </form>
@endsection
