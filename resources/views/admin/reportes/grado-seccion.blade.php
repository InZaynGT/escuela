@extends('layouts.base')

@section('title', 'Listado por Grado y Sección')

@section('content_header')
    <h1>Listado por Grado y Sección</h1>
@stop

@section('content')

<div class="card no-print">
    <div class="card-header">Filtros</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reportes.grado-seccion') }}" class="form-inline">
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
        <button onclick="window.print()" class="btn btn-sm btn-secondary no-print">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>
    <div class="card-body p-0">
        @if($estudiantes->isEmpty())
            <div class="p-3">
                <div class="alert alert-info mb-0">No hay estudiantes inscritos.</div>
            </div>
        @else
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Apellidos</th>
                    <th>Nombre(s)</th>
                    <th>CUI</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estudiantes as $i => $est)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $est->apellidos }}</td>
                    <td>{{ $est->nombre }}</td>
                    <td>{{ $est->cui ?? '—' }}</td>
                    <td>{{ $est->telefono ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-2 text-right">
            <small class="text-muted">Total: {{ $estudiantes->count() }} estudiante(s)</small>
        </div>
        @endif
    </div>
</div>
@endif

@stop
