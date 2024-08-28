<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-16">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-4xl font-bold mb-4">500 - Server Error</h1>
            <p class="text-xl mb-4">Sorry, something went wrong on our end. Please try again later.</p>
            @if(app()->environment('local'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error Details:</strong>
                    <span class="block sm:inline">{{ $exception->getMessage() }}</span>
                </div>
            @endif
        </div>
    </div>
</body>
</html>