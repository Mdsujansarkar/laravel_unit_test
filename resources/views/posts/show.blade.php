@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <article>
        <h1>{{ $post->title }}</h1>
        <p class="muted">by {{ $post->author->name }}</p>
        <div>{!! nl2br(e($post->body)) !!}</div>
    </article>
@endsection
