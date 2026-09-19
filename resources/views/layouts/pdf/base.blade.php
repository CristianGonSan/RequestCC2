<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="{{ $author ?? config('app.name') }}">
    <meta name="generator" content="DomPDF">
    <title>@yield('title', $title ?? config('app.name'))</title>

    @include('layouts.pdf.base-styles')

    <style>
        @page {
            margin: 100px 50px 70px 50px;
        }

        #page-header {
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            height: 60px;
        }

        #page-footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 30px;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>

    @yield('styles')
</head>

<body>

    <div id="page-header">
        @yield('header')
    </div>

    <main>
        @yield('content')
    </main>

    <div id="page-footer">
        @yield('footer')
    </div>

</body>

</html>
