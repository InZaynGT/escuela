@extends('layouts.base')

@section('title', 'Mis Notas')

@section('content_header')
    <h1>Mis Notas</h1>
@stop

@section('content')

@if(!$inscripcion)
    <div class="alert alert-info">No tienes una inscripción activa para este año.</div>
@elseif($resumen->isEmpty())
    <div class="alert alert-info">No hay materias registradas.</div>
@else

@foreach($resumen as $item)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <span>{{ $item['materia']->nombre }}</span>
        @if($item['promedio'] !== null)
            @php $cls = $item['promedio'] >= 70 ? 'badge-success' : ($item['promedio'] >= 60 ? 'badge-warning' : 'badge-danger'); @endphp
            <span class="badge {{ $cls }} text-white">
                Promedio: {{ number_format($item['promedio'], 1) }}
                — {{ $item['aprobado'] ? 'Aprobado' : 'Reprobado' }}
            </span>
        @else
            <span class="badge badge-secondary text-white">Sin notas aún</span>
        @endif
    </div>
    @if($item['tareas']->isNotEmpty())
    <div class="card-body p-0">
        <table class="table table-sm table-bordered mb-0">
            <thead>
                <tr>
                    <th>Tarea</th>
                    <th>Ponderación</th>
                    <th class="text-center">Nota</th>
                    <th>Comentario del docente</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item['tareas'] as $detalle)
                @php $nota = $detalle['calificacion']?->calificacion ?? null; @endphp
                <tr>
                    <td>{{ $detalle['tarea']->titulo }}</td>
                    <td>{{ $detalle['tarea']->ponderacion }}%</td>
                    <td class="text-center">
                        @if($nota !== null)
                            @php $cls = $nota >= 70 ? 'nota-alta' : ($nota >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                            <span class="{{ $cls }}">{{ number_format($nota, 1) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $detalle['calificacion']?->observaciones ?? '—' }}
                        </small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endforeach

@endif
@stop
