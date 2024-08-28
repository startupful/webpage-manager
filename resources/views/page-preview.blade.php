<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $page->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-4xl font-bold">Preview: {{ $page->title }}</h1>
        </header>

        <main class="bg-white shadow-md rounded-lg p-6">
            {!! $page->render_content() !!}
        </main>

        <footer class="mt-8 text-center text-gray-600">
            <p>This is an unpublished page preview.</p>
        </footer>
    </div>

    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
</body>
</html>