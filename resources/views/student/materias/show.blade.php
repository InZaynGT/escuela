@extends('layouts.base')

@section('title', $materia->nombre)

@section('content_header')
    <h1>{{ $materia->nombre }}</h1>
@stop

@section('content')

<div class="mb-2">
    <a href="{{ route('student.materias.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Tareas y calificaciones</div>
    </div>
    <div class="card-body p-0">
        @if($materia->tareas->isEmpty())
            <div class="p-3">
                <div class="alert alert-info mb-0">El docente aún no ha registrado tareas.</div>
            </div>
        @else
        <table class="table table-bordered table-hover mb-0" id="tablaTareas">
            <thead>
                <tr>
                    <th>Tarea</th>
                    <th>Ponderación</th>
                    <th class="text-center">Nota</th>
                    <th>Observación</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @php $sumaNotas = 0; $totalPonderacion = 0; @endphp
                @foreach($materia->tareas as $tarea)
                @php
                    $cal = $calificaciones[$tarea->id] ?? null;
                    $nota = $cal?->calificacion;
                    if ($nota !== null) {
                        $sumaNotas += $nota * ($tarea->ponderacion / 100);
                        $totalPonderacion += $tarea->ponderacion;
                    }
                @endphp
                <tr>
                    <td>
                        <strong>{{ $tarea->titulo }}</strong>
                        @if($tarea->descripcion)
                            <br><small class="text-muted">{{ $tarea->descripcion }}</small>
                        @endif
                    </td>
                    <td>{{ $tarea->ponderacion }}%</td>
                    <td class="text-center">
                        @if($nota !== null)
                            @php $cls = $nota >= 70 ? 'nota-alta' : ($nota >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                            <span class="{{ $cls }}">{{ number_format($nota, 1) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">{{ $cal?->observaciones ?? '—' }}</small>
                    </td>
                    <td class="text-center">
                        @if($nota !== null)
                            <span class="badge {{ $nota >= 60 ? 'badge-success' : 'badge-danger' }} text-white">
                                {{ $nota >= 60 ? 'Aprobado' : 'Reprobado' }}
                            </span>
                        @else
                            <span class="badge badge-secondary text-white">Pendiente</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            @if($totalPonderacion > 0)
            <tfoot>
                <tr class="table-light">
                    <td colspan="4" class="text-right"><strong>Promedio acumulado:</strong></td>
                    @php
                        $promedio = round(($sumaNotas / $totalPonderacion) * 100, 2);
                        $cls = $promedio >= 70 ? 'nota-alta' : ($promedio >= 60 ? 'nota-media' : 'nota-baja');
                    @endphp
                    <td class="text-center"><strong class="{{ $cls }}">{{ number_format($promedio, 1) }}</strong></td>
                </tr>
            </tfoot>
            @endif
        </table>
        @endif
    </div>
</div>
@stop
