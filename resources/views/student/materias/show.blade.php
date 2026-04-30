@extends('layouts.base')

@section('title', $materia->nombre)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $materia->nombre }}
            @isset($periodo)
                <small>{{ $periodo->nombre }}</small>
            @endisset
        </h1>
        <a href="{{ route('student.materias.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')

<div class="mb-3">
    @isset($periodo)
        <a href="{{ route('student.materias.show', $materia->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a unidades
        </a>
    @else
        <a href="{{ route('student.materias.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a materias
        </a>
    @endisset
</div>

@isset($periodo)

{{-- ── Vista de tareas de un periodo ── --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <span>Tareas — {{ $periodo->nombre }}</span>
    </div>
    @if($tareas->isEmpty())
        <div class="card-body">
            <div class="alert alert-secondary mb-0">El docente aún no ha registrado tareas para esta unidad.</div>
        </div>
    @else
    <div class="card-body p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Tarea</th>
                    <th class="text-center">Ponderación</th>
                    <th class="text-center">Nota</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @php $sumaGanado = 0; $sumaMax = 0; $tieneAlguna = false; @endphp
                @foreach($tareas as $tarea)
                @php
                    $cal  = $calificaciones[$tarea->id] ?? null;
                    $nota = $cal?->calificacion;
                    $sumaMax += $tarea->ponderacion;
                    if ($nota !== null) { $sumaGanado += $nota; $tieneAlguna = true; }
                @endphp
                <tr>
                    <td>
                        <strong>{{ $tarea->titulo }}</strong>
                        @if($tarea->descripcion)
                            <br><small class="text-muted">{{ $tarea->descripcion }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $tarea->ponderacion }} pts</td>
                    <td class="text-center">
                        @if($nota !== null)
                            @php $cls = $nota >= 70 ? 'nota-alta' : ($nota >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                            <span class="{{ $cls }}">{{ number_format($nota, 1) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $cal?->observaciones ?? '—' }}</small></td>
                </tr>
                @endforeach
            </tbody>
            @if($tieneAlguna)
            <tfoot>
                <tr class="table-light">
                    <td colspan="2" class="text-right"><strong>Punteo obtenido:</strong></td>
                    <td class="text-center">
                        @php $cls = $sumaGanado >= 70 ? 'nota-alta' : ($sumaGanado >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                        <strong class="{{ $cls }}">{{ number_format($sumaGanado, 1) }} / {{ $sumaMax }}</strong>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    @endif
</div>

@else

{{-- ── Vista de unidades (tarjetas por periodo) ── --}}
<div class="row">
    @foreach($periodos as $periodo)
    @php $resumen = $resumenPorPeriodo[$periodo->id] ?? ['cantidad' => 0, 'max' => 0, 'ganado' => null]; @endphp
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <strong>{{ $periodo->nombre }}</strong>
            </div>
            <div class="card-body">
                @if($resumen['cantidad'] > 0)
                    <p class="mb-1">
                        <span class="badge badge-secondary">{{ $resumen['cantidad'] }} tarea(s)</span>
                        <span class="badge badge-light border">{{ $resumen['max'] }} pts</span>
                    </p>
                    @if($resumen['ganado'] !== null)
                        @php $cls = $resumen['ganado'] >= 70 ? 'nota-alta' : ($resumen['ganado'] >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                        <p class="mb-0">
                            Obtenido: <span class="{{ $cls }} font-weight-bold">{{ $resumen['ganado'] }}</span>
                            <small class="text-muted">/ {{ $resumen['max'] }} pts</small>
                        </p>
                    @else
                        <p class="mb-0 text-muted"><small>Sin calificaciones aún</small></p>
                    @endif
                @else
                    <p class="mb-0 text-muted"><small>Sin tareas registradas</small></p>
                @endif
            </div>
            <div class="card-footer p-2">
                <a href="{{ route('student.materias.show', [$materia->id, 'id_periodo' => $periodo->id]) }}"
                   class="btn btn-sm btn-outline-dark btn-block">
                    <i class="fas fa-list"></i> Ver tareas
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endisset

@stop
