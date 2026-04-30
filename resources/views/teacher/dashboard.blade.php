@extends('layouts.base')

@section('title', 'Mi Panel')

@section('content_header')
    <h1>Bienvenido, {{ auth()->user()->name }}</h1>
@stop

@section('content')

{{-- Período activo --}}
@if($periodoActual)
<div class="alert alert-light border mb-3 py-2 px-3" style="font-size:.88rem;">
    <i class="fas fa-calendar-alt text-muted mr-1"></i>
    Progreso mostrado para: <strong>{{ $periodoActual->nombre }} {{ $periodoActual->anio }}</strong> (unidad actual)
</div>
@else
<div class="alert alert-warning mb-3 py-2 px-3" style="font-size:.88rem;">
    <i class="fas fa-exclamation-triangle mr-1"></i>
    No hay un período configurado para este año. Contacta al administrador.
</div>
@endif

{{-- Tarjetas de resumen global --}}
@php
    $totalEst       = $materias->sum('est_count');
    $totalTareas    = $materias->sum('tarea_count');
    $totalPendiente = $materias->sum('pendientes');
    $totalCalif     = $materias->sum('calificado_count');
    $totalExpected  = $materias->sum(fn($m) => $m->est_count * $m->tarea_count);
    $globalPct      = $totalExpected > 0 ? (int) round($totalCalif / $totalExpected * 100) : 100;
@endphp

<div class="row mb-4">
    <div class="col-6 col-md-3">
        <div class="small-box bg-dark">
            <div class="inner">
                <h3>{{ $materias->count() }}</h3>
                <p>Materias</p>
            </div>
            <div class="icon"><i class="fas fa-book"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $totalEst }}</h3>
                <p>Estudiantes (total)</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="small-box {{ $totalPendiente > 0 ? 'bg-warning' : 'bg-success' }}">
            <div class="inner">
                <h3>{{ $totalPendiente }}</h3>
                <p>Calificaciones pendientes</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $globalPct }}%</h3>
                <p>Progreso global</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
</div>

{{-- Tarjetas por materia --}}
<div class="card">
    <div class="card-header"><b>Mis Materias</b></div>
    <div class="card-body">
        @if($materias->count() > 0)
        <div class="row">
            @foreach($materias as $materia)
            @php
                $pct = $materia->porcentaje;
                if ($materia->tarea_count === 0) {
                    $barColor  = 'secondary';
                    $badgeColor = 'secondary';
                } elseif ($pct === 100) {
                    $barColor  = 'success';
                    $badgeColor = 'success';
                } elseif ($pct >= 75) {
                    $barColor  = 'info';
                    $badgeColor = 'info';
                } elseif ($pct >= 50) {
                    $barColor  = 'warning';
                    $badgeColor = 'warning';
                } else {
                    $barColor  = 'danger';
                    $badgeColor = 'danger';
                }
            @endphp
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm" style="border-top: 3px solid var(--{{ $barColor === 'secondary' ? 'gray-500' : $barColor }});">
                    <div class="card-body d-flex flex-column">

                        {{-- Nombre y grado/sección --}}
                        <h6 class="font-weight-bold mb-0">{{ $materia->nombre }}</h6>
                        <p class="text-muted mb-2" style="font-size:.82rem;">
                            {{ $materia->gradoSeccion->grado->nombre ?? '' }}
                            {{ $materia->gradoSeccion->seccion->nombre ?? '' }}
                        </p>

                        {{-- Estadísticas --}}
                        <div class="d-flex justify-content-between mb-1" style="font-size:.82rem;">
                            <span><i class="fas fa-users text-muted mr-1"></i>{{ $materia->est_count }} est.</span>
                            <span><i class="fas fa-tasks text-muted mr-1"></i>{{ $materia->tarea_count }} tareas</span>
                            @if($materia->tarea_count > 0)
                                <span class="badge badge-{{ $badgeColor }} align-self-center">
                                    {{ $materia->pendientes }} pendiente{{ $materia->pendientes !== 1 ? 's' : '' }}
                                </span>
                            @else
                                <span class="badge badge-secondary align-self-center">Sin tareas</span>
                            @endif
                        </div>

                        {{-- Barra de progreso --}}
                        <div class="progress mb-3" style="height:8px;">
                            <div class="progress-bar bg-{{ $barColor }}"
                                 role="progressbar"
                                 style="width:{{ $pct }}%"
                                 aria-valuenow="{{ $pct }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <div class="text-right mb-3" style="font-size:.78rem; color:#666;">
                            @if($materia->tarea_count > 0)
                                {{ $materia->calificado_count }} / {{ $materia->est_count * $materia->tarea_count }} calificaciones ({{ $pct }}%)
                            @else
                                Crea tareas para ver el progreso
                            @endif
                        </div>

                        {{-- Botón --}}
                        <div class="mt-auto">
                            <a href="{{ route('teacher.materias.show', $materia->id) }}"
                               class="btn btn-dark btn-sm btn-block">
                                <i class="fas fa-book-open mr-1"></i> Ver materia
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-secondary mb-0">
            No tienes materias asignadas. Contacta al administrador.
        </div>
        @endif
    </div>
</div>
@stop
