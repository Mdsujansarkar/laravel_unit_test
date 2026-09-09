<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sample Blog' }}</title>
    <style>
        body { font-family: sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; line-height: 1.6; }
        nav a { margin-right: 1rem; }
        article { margin-bottom: 2rem; }
        .muted { color: #777; font-size: 0.9rem; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('posts.index') }}">Blog</a>
        @auth
            <a href="{{ route('posts.create') }}">New Post</a>
        @endauth
    </nav>
    <hr>
    @yield('content')
</body>
</html>
