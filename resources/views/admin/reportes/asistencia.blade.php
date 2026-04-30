@extends('layouts.base')

@section('title', 'Reporte de Asistencia')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Reporte de Asistencia</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

<div class="card no-print">
    <div class="card-header">Filtros</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reportes.asistencia') }}" class="form-inline">
            <div class="form-group mr-2">
                <label class="mr-1">Grado / Sección:</label>
                <select name="id_grado_seccion" class="form-control form-control-sm" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($gradoSecciones as $gs)
                        <option value="{{ $gs->id }}"
                            {{ $idGradoSeccion == $gs->id ? 'selected' : '' }}>
                            {{ $gs->grado->nombre ?? '' }} — {{ $gs->seccion->nombre ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mr-2">
                <label class="mr-1">Año:</label>
                <input type="number" name="anio" value="{{ $anio }}"
                       class="form-control form-control-sm" style="width:90px" min="2020">
            </div>
            <button type="submit" class="btn btn-sm btn-dark">Buscar</button>
        </form>
    </div>
</div>

@if($seleccionado)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            {{ $seleccionado->grado->nombre ?? '' }} — {{ $seleccionado->seccion->nombre ?? '' }}
            <small class="text-muted">Año {{ $anio }}</small>
        </span>
        <a href="{{ route('admin.reportes.asistencia.print', ['id_grado_seccion' => $idGradoSeccion, 'anio' => $anio]) }}"
           class="btn btn-sm btn-dark no-print" target="_blank">
            <i class="fas fa-print"></i> Imprimir / Guardar PDF
        </a>
    </div>
    <div class="card-body p-0">
        @if($resumen->isEmpty())
            <div class="p-3">
                <div class="alert alert-secondary mb-0">No hay estudiantes inscritos.</div>
            </div>
        @else
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Estudiante</th>
                    <th class="text-center">Presentes</th>
                    <th class="text-center">Ausentes</th>
                    <th class="text-center">Tardanzas</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">% Asistencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resumen as $i => $fila)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $fila['est']->nombre_completo }}</td>
                    <td class="text-center nota-alta">{{ $fila['presentes'] }}</td>
                    <td class="text-center nota-baja">{{ $fila['ausentes'] }}</td>
                    <td class="text-center nota-media">{{ $fila['tardanzas'] }}</td>
                    <td class="text-center">{{ $fila['total'] }}</td>
                    <td class="text-center">
                        @if($fila['porcentaje'] !== null)
                            @php $cls = $fila['porcentaje'] >= 80 ? 'nota-alta' : ($fila['porcentaje'] >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                            <span class="{{ $cls }}">{{ $fila['porcentaje'] }}%</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endif

@stop
