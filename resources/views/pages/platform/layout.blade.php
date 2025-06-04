<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Платформа | {{ $title }}</title>

    <link rel="stylesheet" href="{{ asset('css/style-platform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adaptation.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('icon-logo.svg') }}" type="image/svg+xml">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body class="d-md-flex vh-100 overflow-hidden bg-light">

    @include('layout.sidebar', ['activePage' => $activePage])

    <main class="d-flex flex-column w-100 overflow-y-auto px-md-4 py-md-3 position-relative">
        @include('layout.topbar')

        <main class="flex-grow-1 overflow-y-auto bg-white px-md-4 py-md-3">
            <div class="container-fluid p-3 p-md-4">
                @if (session('success'))
                    <div class="toast-notification success">
                        <div class="toast-line"></div>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="toast-notification error">
                        <div class="toast-line"></div>
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </main>

    <button class="btn bg-[#6E76C1]  d-md-none position-fixed start-0 bottom-0 m-3" data-bs-toggle="offcanvas"
        data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
        <i class="fas fa-bars text-[#ffffff]"></i>
    </button>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
        <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-3">
            @include('layout.sidebar-mobile', ['activePage' => $activePage])
        </div>
    </div>

    @if (auth()->user()->role === 'teacher' && $quick_action !== 'null')
        <a href="{{ route($quick_action, ['return_url' => url()->current()]) }}" class="floating-btn">
            <button><i class="fas fa-plus"></i></button>
        </a>
    @endif

    @include('layout.modal-delete')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
