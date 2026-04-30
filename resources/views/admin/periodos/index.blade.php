@extends('layouts.base')

@section('title', 'Unidades Académicos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Unidades Académicos</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Listado de Unidades</div>
            <div>
                <a href="{{ route('admin.periodos.create') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nueva Unidad
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
                                    @if($periodo->bloqueado)
                                        <span class="badge badge-danger text-white"><i class="fas fa-lock mr-1"></i>Bloqueado</span>
                                    @else
                                        @php
                                            $activo = $periodo->fecha_inicio && $periodo->fecha_fin &&
                                                      now()->between($periodo->fecha_inicio, $periodo->fecha_fin);
                                        @endphp
                                        @if($activo)
                                            <span class="badge badge-success text-white">Activo</span>
                                        @else
                                            <span class="badge badge-secondary text-white">Inactivo</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.periodos.edit', $periodo->id) }}" class="btn btn-dark btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.periodos.toggle-bloqueo', $periodo->id) }}" method="POST" style="display:inline"
                                          data-confirm="{{ $periodo->bloqueado ? '¿Desbloquear el período \''.$periodo->nombre.'\'?' : '¿Bloquear el período \''.$periodo->nombre.'\'? Los docentes no podrán crear tareas ni calificar.' }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $periodo->bloqueado ? 'btn-success' : 'btn-warning' }}">
                                            <i class="fas fa-{{ $periodo->bloqueado ? 'lock-open' : 'lock' }}"></i>
                                            {{ $periodo->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                                        </button>
                                    </form>
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
                    <div class="alert alert-secondary mb-0">No hay periodos registrados. ¡Crea el primero!</div>
                </div>
            @endif
        </div>
    </div>
@stop
