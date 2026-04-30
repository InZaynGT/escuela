@extends('layouts.base')

@section('title', 'Boleta de Notas')

@section('content_header')
    <h1>Boleta de Notas</h1>
@stop

@section('content')

<div class="card no-print">
    <div class="card-header">Seleccionar estudiante</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reportes.boletas') }}" class="form-inline">
            <div class="form-group mr-2">
                <label class="mr-1">Estudiante:</label>
                <select name="id_estudiante" class="form-control form-control-sm" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($estudiantes as $est)
                        <option value="{{ $est->id }}"
                            {{ $idEstudiante == $est->id ? 'selected' : '' }}>
                            {{ $est->apellidos }}, {{ $est->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mr-2">
                <label class="mr-1">Año:</label>
                <input type="number" name="anio" value="{{ $anio }}"
                       class="form-control form-control-sm" style="width:90px" min="2020">
            </div>
            <button type="submit" class="btn btn-sm btn-dark">Ver boleta</button>
        </form>
    </div>
</div>

@if($seleccionado && $inscripcion)
<div class="card">
    <div class="card-body">

        {{-- Encabezado imprimible --}}
        <div class="text-center mb-3">
            <h5 style="font-weight:700;margin-bottom:.25rem;">ESCUELA OFICIAL RURAL MIXTA</h5>
            <p style="margin:0;font-size:.85rem;color:#546e7a;">
                {{ $inscripcion->gradoSeccion->grado->nombre ?? '' }}
                {{ $inscripcion->gradoSeccion->seccion->nombre ?? '' }} — Año {{ $anio }}
            </p>
            <hr>
        </div>

        {{-- Datos del estudiante --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr><th style="width:130px">Nombre:</th><td>{{ $seleccionado->nombre_completo }}</td></tr>
                    <tr><th>CUI:</th><td>{{ $seleccionado->cui ?? '—' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6 text-right no-print">
                <button onclick="window.print()" class="btn btn-sm btn-secondary">
                    <i class="fas fa-print"></i> Imprimir boleta
                </button>
            </div>
        </div>

        {{-- Notas por materia --}}
        @if($materias->isEmpty())
            <div class="alert alert-info">No hay materias registradas para este año.</div>
        @else
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Materia</th>
                    @foreach($materias->first()['materia']->tareas as $tarea)
                        <th class="text-center" style="min-width:90px">
                            {{ $tarea->titulo }}<br>
                            <small class="text-muted">({{ $tarea->ponderacion }}%)</small>
                        </th>
                    @endforeach
                    <th class="text-center">Promedio</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materias as $item)
                <tr>
                    <td>{{ $item['materia']->nombre }}</td>
                    @foreach($item['materia']->tareas as $tarea)
                        <td class="text-center">
                            @php $cal = $item['calificaciones'][$tarea->id] ?? null; @endphp
                            @if($cal)
                                {{ number_format($cal->calificacion, 1) }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center">
                        @if($item['promedio'] !== null)
                            @php $cls = $item['promedio'] >= 70 ? 'nota-alta' : ($item['promedio'] >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                            <strong class="{{ $cls }}">{{ number_format($item['promedio'], 1) }}</strong>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item['aprobado'] === true)
                            <span class="badge badge-success">Aprobado</span>
                        @elseif($item['aprobado'] === false)
                            <span class="badge badge-danger">Reprobado</span>
                        @else
                            <span class="badge badge-secondary">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3 text-center" style="font-size:.78rem;color:#78909c;">
            Generado el {{ now()->format('d/m/Y') }}
        </div>
        @endif
    </div>
</div>
@elseif($seleccionado && !$inscripcion)
<div class="alert alert-warning">
    El estudiante seleccionado no tiene inscripción activa para el año {{ $anio }}.
</div>
@endif

@stop
