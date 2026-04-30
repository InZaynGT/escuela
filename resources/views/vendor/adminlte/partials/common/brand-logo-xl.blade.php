@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');
    $dashboard_url = config('adminlte.use_route_url', false)
        ? ($dashboard_url ? route($dashboard_url) : '')
        : ($dashboard_url ? url($dashboard_url) : '');

    $logoImg      = \App\Models\Configuracion::get('logo_img');
    $nombreCentro = \App\Models\Configuracion::get('nombre_centro', config('adminlte.logo', '<b>LAS</b> EORM'));
@endphp

<a href="{{ $dashboard_url }}"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand logo-switch {{ config('adminlte.classes_brand') }}"
    @else
        class="brand-link logo-switch {{ config('adminlte.classes_brand') }}"
    @endif>

    @if($logoImg)
        <img src="{{ asset($logoImg) }}" alt="{{ strip_tags($nombreCentro) }}"
             class="brand-image img-circle elevation-3 logo-xs" style="opacity:.85; max-height:33px; width:auto;">
    @endif

    <span class="brand-text font-weight-bold logo-xl">{!! $nombreCentro !!}</span>

</a>
