<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EORM') | LAS EORM</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('css')
</head>
<body>

{{-- Sidebar: offcanvas en móvil, fijo en escritorio --}}
<aside class="app-sidebar offcanvas-lg offcanvas-start" id="sidebar" tabindex="-1">
    @include('partials.sidebar-nav')
</aside>

{{-- Wrapper principal --}}
<div class="app-main">

    {{-- Navbar superior --}}
    <nav class="app-navbar">
        <button class="sidebar-toggle d-lg-none"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebar"
                aria-controls="sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="navbar-user d-none d-md-inline">
                {{ auth()->user()->name ?? '' }}
            </span>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-sm-inline ms-1">Salir</span>
                </button>
            </form>
        </div>
    </nav>

    {{-- Cabecera de página --}}
    <div class="page-header">
        @yield('content_header')
    </div>

    {{-- Contenido principal --}}
    <div class="page-content">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@include('partials.sweetalert')
@yield('js')
</body>
</html>
