@extends('layouts.base')

@section('title', 'Panel Administrativo')

@section('content_header')
    <h1>Panel Administrativo</h1>
@stop

@section('content')

@if($stats['periodo_activo'])
<div class="alert alert-info">
    <i class="fas fa-calendar-alt"></i>
    Periodo activo: <strong>{{ $stats['periodo_activo']->nombre }}</strong>
    ({{ \Carbon\Carbon::parse($stats['periodo_activo']->fecha_inicio)->format('d/m/Y') }}
    — {{ \Carbon\Carbon::parse($stats['periodo_activo']->fecha_fin)->format('d/m/Y') }})
</div>
@endif

<div class="row">
    <div class="col-6 col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div style="font-size:2rem;font-weight:700;color:#37474f;">{{ $stats['estudiantes'] }}</div>
                <div style="font-size:.78rem;color:#78909c;text-transform:uppercase;letter-spacing:.04em;">
                    Estudiantes
                </div>
                <a href="{{ route('admin.estudiantes.index') }}"
                   class="btn btn-sm btn-dark mt-2 d-block">Ver listado</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div style="font-size:2rem;font-weight:700;color:#37474f;">{{ $stats['inscritos'] }}</div>
                <div style="font-size:.78rem;color:#78909c;text-transform:uppercase;letter-spacing:.04em;">
                    Inscritos {{ date('Y') }}
                </div>
                <a href="{{ route('admin.estudiantes.index') }}"
                   class="btn btn-sm btn-secondary mt-2 d-block">Ver inscripciones</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div style="font-size:2rem;font-weight:700;color:#37474f;">{{ $stats['profesores'] }}</div>
                <div style="font-size:.78rem;color:#78909c;text-transform:uppercase;letter-spacing:.04em;">
                    Docentes
                </div>
                <a href="{{ route('admin.profesores.index') }}"
                   class="btn btn-sm btn-dark mt-2 d-block">Ver listado</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div style="font-size:2rem;font-weight:700;color:#37474f;">{{ $stats['materias'] }}</div>
                <div style="font-size:.78rem;color:#78909c;text-transform:uppercase;letter-spacing:.04em;">
                    Materias
                </div>
                <a href="{{ route('admin.materias.index') }}"
                   class="btn btn-sm btn-dark mt-2 d-block">Ver materias</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">Accesos rápidos — Estudiantes</div>
            <div class="card-body py-2">
                <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-sm btn-dark mb-1">
                    <i class="fas fa-user-plus"></i> Registrar estudiante
                </a>
                <a href="{{ route('admin.reportes.grado-seccion') }}" class="btn btn-sm btn-secondary mb-1">
                    <i class="fas fa-list-alt"></i> Listado por grado
                </a>
                <a href="{{ route('admin.reportes.boletas') }}" class="btn btn-sm btn-secondary mb-1">
                    <i class="fas fa-file-invoice"></i> Generar boleta
                </a>
                <a href="{{ route('admin.responsables.index') }}" class="btn btn-sm btn-secondary mb-1">
                    <i class="fas fa-user-friends"></i> Responsables
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">Accesos rápidos — Configuración</div>
            <div class="card-body py-2">
                <a href="{{ route('admin.profesores.create') }}" class="btn btn-sm btn-dark mb-1">
                    <i class="fas fa-user-plus"></i> Registrar docente
                </a>
                <a href="{{ route('admin.grado-seccion.index') }}" class="btn btn-sm btn-secondary mb-1">
                    <i class="fas fa-school"></i> Grados y secciones
                </a>
                <a href="{{ route('admin.periodos.index') }}" class="btn btn-sm btn-secondary mb-1">
                    <i class="fas fa-calendar-alt"></i> Periodos
                </a>
                <a href="{{ route('admin.reportes.asistencia') }}" class="btn btn-sm btn-secondary mb-1">
                    <i class="fas fa-clipboard-check"></i> Reporte asistencia
                </a>
            </div>
        </div>
    </div>
</div>

@stop
