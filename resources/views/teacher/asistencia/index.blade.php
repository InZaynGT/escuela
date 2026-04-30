@extends('layouts.base')

@section('title', 'Asistencia')

@section('content_header')
    <h1>Registro de Asistencia</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Selecciona una materia para registrar asistencia</div>
    </div>
    <div class="card-body p-0">
        @if($materias->isEmpty())
            <div class="p-3">
                <div class="alert alert-info mb-0">No tienes materias asignadas.</div>
            </div>
        @else
            <table class="table table-bordered table-hover mb-0" id="tablaMaterias">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Grado</th>
                        <th>Sección</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materias as $materia)
                    <tr>
                        <td>{{ $materia->nombre }}</td>
                        <td>{{ $materia->gradoSeccion->grado->nombre ?? '—' }}</td>
                        <td>{{ $materia->gradoSeccion->seccion->nombre ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('teacher.asistencia.registrar', $materia->id) }}"
                               class="btn btn-sm btn-dark">
                                <i class="fas fa-clipboard-check"></i> Registrar hoy
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@stop
