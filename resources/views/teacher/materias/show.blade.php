@extends('layouts.base')

@section('title', $materia->nombre)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $materia->nombre }}
            <small>{{ $materia->gradoSeccion->grado->nombre ?? '' }} {{ $materia->gradoSeccion->seccion->nombre ?? '' }}</small>
        </h1>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

@if($periodos->isEmpty())
    <div class="alert alert-warning">No hay unidades configuradas. Pide al administrador que cree los periodos.</div>
@else
<div class="row">
    @foreach($periodos as $periodo)
    @php
        $ptsUsados  = $puntosUsadosPorPeriodo[$periodo->id] ?? 0;
        $cantTareas = $tareasPorPeriodo[$periodo->id] ?? 0;
        $completo   = $ptsUsados >= 100;
    @endphp
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card h-100 {{ $periodo->bloqueado ? 'border-danger' : '' }}">
            <div class="card-header text-center {{ $periodo->bloqueado ? 'bg-danger text-white' : 'bg-light' }}">
                <strong>{{ $periodo->nombre }}</strong>
                @if($periodo->bloqueado)
                    <i class="fas fa-lock ml-1" title="Período bloqueado"></i>
                @endif
                <div style="font-size:.78rem;opacity:.75;">{{ $periodo->anio }}</div>
            </div>
            <div class="card-body text-center d-flex flex-column justify-content-between">
                <div class="mb-3">
                    @if($cantTareas > 0)
                        <div style="font-size:2rem;font-weight:700;color:#263238;">{{ $cantTareas }}</div>
                        <div style="font-size:.78rem;color:#78909c;">
                            {{ $cantTareas === 1 ? 'tarea' : 'tareas' }}
                        </div>
                        <div class="mt-2">
                            @if($completo)
                                <span class="badge badge-success">100 / 100 pts usados</span>
                            @else
                                <span class="badge badge-secondary">{{ $ptsUsados }} / 100 pts usados</span>
                            @endif
                        </div>
                    @else
                        <div style="font-size:.85rem;color:#b0bec5;margin-top:.5rem;">Sin tareas aún</div>
                    @endif
                </div>

                <div class="d-flex flex-column" style="gap:.4rem;">
                    <a href="{{ route('teacher.tareas.index', ['idMateria' => $materia->id, 'id_periodo' => $periodo->id]) }}"
                       class="btn {{ $periodo->bloqueado ? 'btn-outline-danger' : 'btn-dark' }} btn-sm btn-block">
                        <i class="fas fa-tasks"></i> {{ $periodo->bloqueado ? 'Ver tareas' : 'Gestionar tareas' }}
                    </a>
                    @if($cantTareas > 0)
                    <a href="{{ route('teacher.calificaciones.index', ['idMateria' => $materia->id, 'id_periodo' => $periodo->id]) }}"
                       class="btn btn-secondary btn-sm btn-block">
                        <i class="fas fa-table"></i> {{ $periodo->bloqueado ? 'Ver notas' : 'Calificar' }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@stop
