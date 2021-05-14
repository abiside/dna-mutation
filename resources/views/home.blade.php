<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DNA Mutation API</title>
    </head>
    <body class="antialiased">
        <div class="relative items-top min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                @markdown($content)
            </div>
        </div>
    </body>
</html>
