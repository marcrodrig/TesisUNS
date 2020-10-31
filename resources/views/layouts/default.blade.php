<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="twitter:widgets:theme" content="dark">
        <title>{{ config('app.name') }} | @yield('title')</title>
        @include('includes.style')
    </head>

    <body>

        {{-- Include the Navbar Menu at the top of the page --}}
        @include('includes.navbar')
        <div class="d-flex align-items-stretch"> 
            {{-- Include the Sidebar Menu at the right of the page --}}
            @include('includes.sidebar')

            {{-- Include the content in the middle of the page --}}
            <div class="content p-4">
                @include('includes.messages')
                @yield('content')
            </div>
        </div>
        {{-- Include the Javascript script (Bootstrap/jQuery/Popper) at the end of the page --}}
        @include('includes.script')

    </body>
</html>
