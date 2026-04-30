@extends('layouts.base')

@section('title', 'Profesores')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Profesores</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Listado de Profesores</div>
            <div>
                <a href="{{ route('admin.profesores.create') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Profesor
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($profesores->count() > 0)
                <table class="table table-bordered mb-0" id="tablaProfesores">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Materias Asignadas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($profesores as $profesor)
                        <tr>
                            <td>{{ $profesor->id }}</td>
                            <td>{{ $profesor->nombre }}</td>
                            <td>{{ $profesor->apellidos }}</td>
                            <td>{{ $profesor->telefono ?? 'N/A' }}</td>
                            <td>
                                @if($profesor->materias->count() > 0)
                                    <span class="badge badge-secondary text-white">{{ $profesor->materias->count() }} materias</span>
                                @else
                                    <span class="badge badge-warning text-white">Sin asignar</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.profesores.edit', $profesor->id) }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('admin.profesores.asignar', $profesor->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-book"></i> Asignar Materias
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3">{{ $profesores->links() }}</div>
            @else
                <div class="p-3">
                    <div class="alert alert-secondary mb-0">No hay profesores registrados. ¡Crea el primero!</div>
                </div>
            @endif
        </div>
    </div>
@stop
