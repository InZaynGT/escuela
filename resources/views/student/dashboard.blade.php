@extends('layouts.base')

@section('title', 'Mi Panel')

@section('content_header')
    <h1>Bienvenido, {{ auth()->user()->name }}</h1>
@stop

@section('content')

@if(!$estudiante)
    <div class="alert alert-warning">
        Tu cuenta no tiene un perfil de estudiante asociado. Contacta al administrador.
    </div>
@else

@php
    $inscripcion = $estudiante->inscripcionActiva()->first();
@endphp

<div class="row">
    <div class="col-md-5 mb-3">
        <div class="card">
            <div class="card-header">Mis datos</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th style="width:120px">Nombre:</th><td>{{ $estudiante->nombre_completo }}</td></tr>
                    <tr><th>CUI:</th><td>{{ $estudiante->cui ?? '—' }}</td></tr>
                    <tr><th>Teléfono:</th><td>{{ $estudiante->telefono ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header">Información académica</div>
            <div class="card-body">
                @if($inscripcion)
                <table class="table table-sm table-borderless mb-0">
                    <tr><th style="width:80px">Grado:</th>
                        <td>{{ $inscripcion->gradoSeccion->grado->nombre ?? '—' }}</td></tr>
                    <tr><th>Sección:</th>
                        <td>{{ $inscripcion->gradoSeccion->seccion->nombre ?? '—' }}</td></tr>
                    <tr><th>Año:</th><td>{{ $inscripcion->anio }}</td></tr>
                </table>
                @else
                <p class="text-muted mb-0">Sin inscripción activa para {{ date('Y') }}.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">Acceso rápido</div>
            <div class="card-body py-2">
                <a href="{{ route('student.materias.index') }}" class="btn btn-sm btn-dark d-block mb-1">
                    <i class="fas fa-book-open"></i> Mis materias
                </a>
                <a href="{{ route('student.calificaciones.index') }}" class="btn btn-sm btn-secondary d-block mb-1">
                    <i class="fas fa-chart-bar"></i> Mis notas
                </a>
                <a href="{{ route('student.asistencia.index') }}" class="btn btn-sm btn-secondary d-block">
                    <i class="fas fa-calendar-check"></i> Mi asistencia
                </a>
            </div>
        </div>
    </div>
</div>

@endif
@stop
