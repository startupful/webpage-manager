<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page: {{ $page->title ?? 'Unknown' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- React and ReactDOM -->
    <script src="https://unpkg.com/react@17/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"></script>
    
    <!-- Gutenberg scripts -->
    <script src="https://unpkg.com/@wordpress/element@2.19.1/build/index.js"></script>
    <script src="https://unpkg.com/@wordpress/blocks@6.23.0/build/index.js"></script>
    <script src="https://unpkg.com/@wordpress/components@9.9.0/build/index.js"></script>
    <script src="https://unpkg.com/@wordpress/i18n@3.18.0/build/index.js"></script>
    <script src="https://unpkg.com/@wordpress/editor@9.25.4/build/index.js"></script>
    
    <!-- Laraberg styles and scripts -->
    <link rel="stylesheet" href="{{ app('webpage-manager-asset')->url('css/laraberg.css') }}">
    <script src="{{ app('webpage-manager-asset')->url('js/laraberg.js') }}"></script>
    
    <style>
           #editor-container {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: calc(100vh - 60px);
        }

        #header {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
            background-color: #f8f8f8;
            border-bottom: 1px solid #ddd;
        }

        #editor {
            flex: 1;
            min-height: calc(100vh - 60px);
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        #save-button {
            padding: 10px 20px;
            background-color: #6366f1;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div id="editor-container">
        <div id="header">
            <h1>{{ $page->title ?? 'Unknown' }}</h1>
            <button id="save-button" onclick="document.getElementById('page-form').submit()">Save</button>
        </div>

        @if($page)
            <form id="page-form" action="{{ route('page.builder.update', ['page' => $page->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <textarea id="content" name="content" hidden>{{ $page->content }}</textarea>
            </form>

            <script>
                window.addEventListener('load', function() {
                    if (typeof Laraberg !== 'undefined') {
                        Laraberg.init('content', { 
                            height: 'calc(100vh - 125px)',
                            laravelFilemanager: false,
                            sidebar: true,
                            contentCss: [
                                'https://cdn.tailwindcss.com',
                            ]
                        });
                    } else {
                        console.error('Laraberg is not defined. Make sure the script is loaded correctly.');
                    }
                });
            </script>
        @else
            <p>Page not found.</p>
        @endif
    </div>
</body>
</html>