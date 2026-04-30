@extends('layouts.base')

@section('title', 'Tareas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $periodo->nombre }}
            <small>{{ $materia->nombre }} — {{ $materia->gradoSeccion->grado->nombre ?? '' }} {{ $materia->gradoSeccion->seccion->nombre ?? '' }}</small>
        </h1>
        <a href="{{ route('teacher.materias.show', $materia->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')

@if($periodo->bloqueado)
<div class="alert alert-danger mb-3">
    <i class="fas fa-lock mr-1"></i>
    <strong>Período bloqueado.</strong>
    No se pueden crear, editar ni eliminar tareas en <strong>{{ $periodo->nombre }}</strong>.
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>
            Tareas
            @if($periodo->bloqueado)
                <span class="badge badge-danger ml-1"><i class="fas fa-lock"></i> Bloqueado</span>
            @elseif($ptsUsados > 0)
                <span class="badge {{ $ptsUsados >= 100 ? 'badge-success' : 'badge-secondary' }} ml-1">
                    {{ $ptsUsados }} / 100 pts
                </span>
            @endif
        </div>
        <div class="d-flex flex-wrap" style="gap:.4rem;">
            @if(!$periodo->bloqueado && $ptsUsados < 100)
            <a href="{{ route('teacher.tareas.create', ['idMateria' => $materia->id, 'id_periodo' => $periodo->id]) }}"
               class="btn btn-dark btn-sm">
                <i class="fas fa-plus"></i> Nueva tarea
            </a>
            @endif
            @if($tareas->isNotEmpty())
            <a href="{{ route('teacher.calificaciones.index', ['idMateria' => $materia->id, 'id_periodo' => $periodo->id]) }}"
               class="btn btn-secondary btn-sm">
                <i class="fas fa-table"></i> {{ $periodo->bloqueado ? 'Ver notas' : 'Calificar' }}
            </a>
            @endif
            <a href="{{ route('teacher.materias.show', $materia->id) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        @if($tareas->isEmpty())
            <div class="p-3">
                <div class="alert alert-secondary mb-0">No hay tareas para esta unidad todavía.</div>
            </div>
        @else
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Tarea</th>
                    <th class="text-center" style="width:90px;">Puntos</th>
                    <th class="text-center" style="width:110px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tareas as $tarea)
                <tr>
                    <td style="vertical-align:middle;">
                        {{ $tarea->titulo }}
                        @if($tarea->descripcion)
                            <br><small class="text-muted">{{ $tarea->descripcion }}</small>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align:middle;">
                        <span class="badge badge-secondary text-white">{{ $tarea->ponderacion }} pts</span>
                    </td>
                    <td class="text-center" style="vertical-align:middle;">
                        @if(!$periodo->bloqueado)
                        <a href="{{ route('teacher.tareas.edit', [$materia->id, $tarea->id]) }}"
                           class="btn btn-dark btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('teacher.tareas.destroy', [$materia->id, $tarea->id]) }}"
                              method="POST" style="display:inline"
                              data-confirm="¿Eliminar '{{ $tarea->titulo }}'?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @else
                        <span class="text-muted" style="font-size:.8rem;"><i class="fas fa-lock"></i></span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="thead-light">
                <tr>
                    <td class="text-right text-muted"><small>Total</small></td>
                    <td class="text-center"><strong>{{ $ptsUsados }} pts</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        @endif
    </div>
</div>

@stop
