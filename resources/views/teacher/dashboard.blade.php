@extends('layouts.base')

@section('title', 'Mi Panel')

@section('content_header')
    <h1>Bienvenido, {{ auth()->user()->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Mis Materias</div>
        </div>
        <div class="card-body">
            @if($materias->count() > 0)
                <div class="row">
                    @foreach($materias as $materia)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $materia->nombre }}</h5>
                                    <p class="text-muted mb-2">
                                        {{ $materia->gradoSeccion->grado->nombre ?? '' }}
                                        {{ $materia->gradoSeccion->seccion->nombre ?? '' }}
                                    </p>
                                    <hr>
                                    <div class="btn-group-vertical w-100">
                                        <a href="{{ route('teacher.tareas.index', $materia->id) }}"
                                           class="btn btn-dark btn-sm btn-block mb-2">
                                            <i class="fas fa-tasks"></i> Gestionar Tareas
                                        </a>
                                        <a href="{{ route('teacher.calificaciones.index', $materia->id) }}"
                                           class="btn btn-secondary btn-sm btn-block">
                                            <i class="fas fa-edit"></i> Ingresar Notas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info mb-0">
                    No tienes materias asignadas. Contacta al administrador para que te asigne materias.
                </div>
            @endif
        </div>
    </div>
@stop
