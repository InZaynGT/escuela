@extends('layouts.base')

@section('title', 'Mis Tareas')

@section('content_header')
    <h1>Tareas
        <small>{{ $materia->nombre }} - {{ $materia->gradoSeccion->grado->nombre ?? '' }} {{ $materia->gradoSeccion->seccion->nombre ?? '' }}</small>
    </h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Lista de Tareas</div>
            <div class="d-flex flex-wrap" style="gap:.4rem;">
                <a href="{{ route('teacher.tareas.create', $materia->id) }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nueva Tarea
                </a>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver a Mis Materias
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($tareas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="tablaTareas">
                        <thead class="thead-light">
                            <tr>
                                <th>Título</th>
                                <th>Periodo</th>
                                <th>Ponderación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tareas as $tarea)
                            <tr>
                                <td>
                                    <strong>{{ $tarea->titulo }}</strong>
                                    @if($tarea->descripcion)
                                        <br><small class="text-muted">{{ $tarea->descripcion }}</small>
                                    @endif
                                </td>
                                <td>{{ $tarea->periodo->nombre ?? 'N/A' }} ({{ $tarea->periodo->anio ?? '' }})</td>
                                <td><span class="badge badge-secondary text-white">{{ $tarea->ponderacion }}%</span></td>
                                <td>
                                    <a href="{{ route('teacher.tareas.edit', [$materia->id, $tarea->id]) }}"
                                       class="btn btn-dark btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('teacher.tareas.destroy', [$materia->id, $tarea->id]) }}"
                                          method="POST"
                                          style="display:inline"
                                          data-confirm="¿Eliminar '{{ $tarea->titulo }}'? Se perderán todas las calificaciones asociadas.">
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
                </div>
            @else
                <div class="p-3">
                    <div class="alert alert-info mb-0">No hay tareas creadas para esta materia.</div>
                </div>
            @endif
        </div>
    </div>
@stop
