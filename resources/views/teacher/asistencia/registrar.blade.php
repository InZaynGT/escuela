@extends('layouts.base')

@section('title', 'Registrar Asistencia')

@section('content_header')
    <h1>Asistencia — {{ $materia->nombre }}
        <small class="text-muted" style="font-size:.75rem;">
            {{ $materia->gradoSeccion->grado->nombre ?? '' }}
            {{ $materia->gradoSeccion->seccion->nombre ?? '' }}
        </small>
    </h1>
@stop

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Registro de asistencia</div>
        <div>
            <a href="{{ route('teacher.asistencia.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">

        {{-- Selector de fecha --}}
        <form method="GET" action="{{ route('teacher.asistencia.registrar', $materia->id) }}"
              class="form-inline mb-3">
            <label class="mr-2">Fecha:</label>
            <input type="date" name="fecha" value="{{ $fecha }}"
                   class="form-control form-control-sm mr-2">
            <button type="submit" class="btn btn-sm btn-secondary">Cargar</button>
        </form>

        @if($estudiantes->isEmpty())
            <div class="alert alert-info mb-0">No hay estudiantes inscritos en esta materia.</div>
        @else
        <form method="POST"
              action="{{ route('teacher.asistencia.guardar', $materia->id) }}">
            @csrf
            <input type="hidden" name="fecha" value="{{ $fecha }}">

            <table class="table table-bordered mb-0" id="tablaAsistencia">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th>Estado</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantes as $i => $estudiante)
                    @php
                        $registro = $asistenciasHoy[$estudiante->id] ?? null;
                        $estadoActual = $registro ? $registro->estado : 'presente';
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $estudiante->nombre_completo }}</td>
                        <td style="min-width:180px">
                            <select name="asistencias[{{ $estudiante->id }}][estado]"
                                    class="form-control form-control-sm">
                                <option value="presente"    {{ $estadoActual === 'presente'    ? 'selected' : '' }}>Presente</option>
                                <option value="ausente"     {{ $estadoActual === 'ausente'     ? 'selected' : '' }}>Ausente</option>
                                <option value="tardanza"    {{ $estadoActual === 'tardanza'    ? 'selected' : '' }}>Tardanza</option>
                                <option value="justificado" {{ $estadoActual === 'justificado' ? 'selected' : '' }}>Justificado</option>
                            </select>
                        </td>
                        <td>
                            <input type="text"
                                   name="asistencias[{{ $estudiante->id }}][observacion]"
                                   class="form-control form-control-sm"
                                   value="{{ $registro->observacion ?? '' }}"
                                   placeholder="Opcional">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted">Fecha: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</small>
                <button type="submit" class="btn btn-dark btn-sm">
                    <i class="fas fa-save"></i> Guardar asistencia
                </button>
            </div>
        </form>
        @endif

    </div>
</div>
@stop
