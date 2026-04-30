@extends('layouts.base')

@section('title', 'Mis Materias')

@section('content_header')
    <h1>Mis Materias</h1>
@stop

@section('content')

@if(!$inscripcion)
    <div class="alert alert-info">No tienes una inscripción activa para este año.</div>
@else
<div class="row mb-3">
    <div class="col-12">
        <small class="text-muted">
            {{ $inscripcion->gradoSeccion->grado->nombre ?? '' }}
            {{ $inscripcion->gradoSeccion->seccion->nombre ?? '' }} — {{ $inscripcion->anio }}
        </small>
    </div>
</div>

@if($materias->isEmpty())
    <div class="alert alert-info">No hay materias registradas para tu grado y sección.</div>
@else
<div class="row">
    @foreach($materias as $materia)
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                {{ $materia->nombre }}
            </div>
            <div class="card-body">
                <p class="text-muted mb-2" style="font-size:.82rem;">
                    {{ $materia->tareas->count() }} tarea(s) registrada(s)
                </p>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('student.materias.show', $materia->id) }}"
                   class="btn btn-sm btn-dark">
                    <i class="fas fa-eye"></i> Ver tareas y notas
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endif

@stop
