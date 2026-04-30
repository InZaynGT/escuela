@extends('layouts.base')

@section('title', 'Asistencia')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registro de Asistencia</h1>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Mis Grados / Secciones</div>
    </div>
    <div class="card-body p-0">
        @if($gradoSecciones->isEmpty())
            <div class="p-3">
                <div class="alert alert-secondary mb-0">No tienes grados-secciones asignados.</div>
            </div>
        @else
            <table class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th>Grado</th>
                        <th class="text-center">Asistencia de hoy</th>
                        <th class="text-center">Otra fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gradoSecciones as $gs)
                    <tr>
                        <td>{{ $gs->grado->nombre}} "{{ $gs->seccion->nombre}}"</td>
                        <td class="text-center">
                            <a href="{{ route('teacher.asistencia.registrar', ['idGradoSeccion' => $gs->id, 'fecha' => now()->toDateString()]) }}"
                               class="btn btn-sm btn-dark">
                                <i class="fas fa-clipboard-check"></i> Registrar hoy
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('teacher.asistencia.registrar', $gs->id) }}"
                               class="btn btn-sm btn-secondary">
                                <i class="fas fa-calendar-alt"></i> Otra fecha
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
