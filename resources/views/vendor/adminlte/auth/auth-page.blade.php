@extends('adminlte::master')

@php
    $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');
    $dashboard_url = config('adminlte.use_route_url', false)
        ? ($dashboard_url ? route($dashboard_url) : '')
        : ($dashboard_url ? url($dashboard_url) : '');

    $logoImg      = \App\Models\Configuracion::get('logo_img');
    $nombreCentro = \App\Models\Configuracion::get('nombre_centro', config('adminlte.logo', '<b>LAS</b> EORM'));
@endphp

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')
    <div class="{{ $auth_type ?? 'login' }}-box">

        {{-- Logo --}}
        <div class="{{ $auth_type ?? 'login' }}-logo">
            <a href="{{ $dashboard_url }}">
                @if($logoImg)
                    <img src="{{ asset($logoImg) }}" alt="{{ strip_tags($nombreCentro) }}"
                         style="max-height:55px; width:auto; margin-bottom:4px; display:block; margin-inline:auto;">
                @endif
                <span>{!! $nombreCentro !!}</span>
            </a>
        </div>

        {{-- Card Box --}}
        <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }}">

            {{-- Card Header --}}
            @hasSection('auth_header')
                <div class="card-header {{ config('adminlte.classes_auth_header', '') }}">
                    <h3 class="card-title float-none text-center">
                        @yield('auth_header')
                    </h3>
                </div>
            @endif

            {{-- Card Body --}}
            <div class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
                @yield('auth_body')
            </div>

            {{-- Card Footer --}}
            @hasSection('auth_footer')
                <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                    @yield('auth_footer')
                </div>
            @endif

        </div>

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
