<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar (tetap, tidak ikut scroll) -->
        <div class="w-64 bg-white shadow h-full overflow-y-auto">
            @include('components.sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col p-5">
            <!-- Navbar -->
                @include('components.navbar')

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>