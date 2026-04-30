@extends('layouts.base')

@section('title', 'Inscribir Estudiante')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Inscribir Estudiante</h1>
        <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Inscribir a: {{ $estudiante->nombre }} {{ $estudiante->apellidos }}</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.estudiantes.inscribir.guardar', $estudiante->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="id_grado_seccion">Grado - Sección *</label>
                    <select name="id_grado_seccion" id="id_grado_seccion" class="form-control" required>
                        <option value="">Seleccione un grado y sección</option>
                        @foreach($combinaciones as $combinacion)
                            <option value="{{ $combinacion->id }}">
                                {{ $combinacion->grado->nombre }} - Sección {{ $combinacion->seccion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="anio">Año *</label>
                    <input type="number"
                           name="anio"
                           id="anio"
                           class="form-control"
                           value="{{ date('Y') }}"
                           required>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Inscribir
                    </button>
                    <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($inscripcionesAnteriores->count() > 0)
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
                <div>Historial de Inscripciones</div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0" id="tablaHistorial">
                    <thead>
                        <tr>
                            <th>Año</th>
                            <th>Grado - Sección</th>
                            <th>Estado</th>
                            <th>Fecha de Inscripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inscripcionesAnteriores as $inscripcion)
                        <tr>
                            <td>{{ $inscripcion->anio }}</td>
                            <td>
                                {{ $inscripcion->gradoSeccion->grado->nombre ?? '' }}
                                {{ $inscripcion->gradoSeccion->seccion->nombre ?? '' }}
                            </td>
                            <td>
                                @if($inscripcion->estado == 'activo')
                                    <span class="badge badge-success text-white">Activa</span>
                                @else
                                    <span class="badge badge-secondary text-white">Inactiva</span>
                                @endif
                            </td>
                            <td>{{ $inscripcion->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop
