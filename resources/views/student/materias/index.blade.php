@extends('layouts.base')

@section('title', 'Mis Materias')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Mis Materias</h1>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

@if(!$inscripcion)
    <div class="alert alert-secondary">No tienes una inscripción activa para este año.</div>
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
    <div class="alert alert-secondary">No hay materias registradas para tu grado y sección.</div>
@else
<div class="row">
    @foreach($materias as $materia)
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                {{ $materia->nombre }}
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('student.materias.show', $materia->id) }}"
                   class="btn btn-sm btn-dark">
                    <i class="fas fa-eye"></i> Ver unidades y notas
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endif

@stop
