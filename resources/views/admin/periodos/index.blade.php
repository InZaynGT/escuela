@extends('layouts.base')

@section('title', 'Periodos Académicos')

@section('content_header')
    <h1>Periodos Académicos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Listado de Periodos</div>
            <div>
                <a href="{{ route('admin.periodos.create') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Periodo
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($periodos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="tablaPeriodos">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Año</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($periodos as $periodo)
                            <tr>
                                <td>{{ $periodo->id }}</td>
                                <td><strong>{{ $periodo->nombre }}</strong></td>
                                <td>{{ $periodo->anio }}</td>
                                <td>{{ $periodo->fecha_inicio ? date('d/m/Y', strtotime($periodo->fecha_inicio)) : 'N/A' }}</td>
                                <td>{{ $periodo->fecha_fin ? date('d/m/Y', strtotime($periodo->fecha_fin)) : 'N/A' }}</td>
                                <td>
                                    @php
                                        $activo = $periodo->fecha_inicio && $periodo->fecha_fin &&
                                                  now()->between($periodo->fecha_inicio, $periodo->fecha_fin);
                                    @endphp
                                    @if($activo)
                                        <span class="badge badge-success text-white">Activo</span>
                                    @else
                                        <span class="badge badge-secondary text-white">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.periodos.edit', $periodo->id) }}" class="btn btn-dark btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.periodos.destroy', $periodo->id) }}" method="POST" style="display:inline" data-confirm="¿Eliminar el periodo '{{ $periodo->nombre }}'?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-3">{{ $periodos->links() }}</div>
                </div>
            @else
                <div class="p-3">
                    <div class="alert alert-info mb-0">No hay periodos registrados. ¡Crea el primero!</div>
                </div>
            @endif
        </div>
    </div>
@stop
