@extends('layouts.base')

@section('title', 'Estudiantes')

@section('content_header')
    <h1>Estudiantes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Listado de Estudiantes</div>
            <div>
                <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Estudiante
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($estudiantes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="tablaEstudiantes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>CUI</th>
                                <th>Teléfono</th>
                                <th>Inscripción Actual</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->id }}</td>
                                <td>{{ $estudiante->nombre }}</td>
                                <td>{{ $estudiante->apellidos }}</td>
                                <td>{{ $estudiante->cui ?? 'N/A' }}</td>
                                <td>{{ $estudiante->telefono ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $inscripcionActual = $estudiante->inscripciones()->where('estado', 'activo')->first();
                                    @endphp
                                    @if($inscripcionActual)
                                        <span class="badge badge-success text-white">
                                            {{ $inscripcionActual->gradoSeccion->grado->nombre ?? '' }}
                                            {{ $inscripcionActual->gradoSeccion->seccion->nombre ?? '' }}
                                            ({{ $inscripcionActual->anio }})
                                        </span>
                                    @else
                                        <span class="badge badge-warning text-white">No inscrito</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}" class="btn btn-dark btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="{{ route('admin.estudiantes.inscribir', $estudiante->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-graduation-cap"></i> Inscribir
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-3">{{ $estudiantes->links() }}</div>
                </div>
            @else
                <div class="p-3">
                    <div class="alert alert-info mb-0">No hay estudiantes registrados. ¡Crea el primero!</div>
                </div>
            @endif
        </div>
    </div>
@stop
