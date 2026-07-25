<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    @include('layout.header')
    @include('layout.sidebar')

    <div class="content-wrapper">
        @yield('content') {{-- ini diisi oleh halaman anak --}}
    </div>

    @include('layout.footer')
</body>

</html>