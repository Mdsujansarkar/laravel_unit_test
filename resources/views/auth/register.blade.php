@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h1>Register</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <p>
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}">
            @error('email')<span class="muted">{{ $message }}</span>@enderror
        </p>
        <p>
            <label for="password">Password</label>
            <input id="password" type="password" name="password">
        </p>
        <button type="submit">Log in</button>
    </form>
@endsection
