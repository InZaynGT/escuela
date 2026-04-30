@extends('layouts.base')

@section('title', 'Nueva Tarea')

@section('content_header')
    <h1>Nueva Tarea
        <small>{{ $materia->nombre }}</small>
    </h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Datos de la Tarea</div>
            <div>
                <a href="{{ route('teacher.tareas.index', $materia->id) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.tareas.store', $materia->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="titulo">Título de la Tarea *</label>
                    <input type="text"
                           name="titulo"
                           id="titulo"
                           class="form-control @error('titulo') is-invalid @enderror"
                           value="{{ old('titulo') }}"
                           placeholder="Ej: Examen Primer Bimestre, Tarea 1, Cuaderno"
                           required>
                    @error('titulo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="descripcion">Descripción (opcional)</label>
                    <textarea name="descripcion"
                              id="descripcion"
                              class="form-control"
                              rows="3"
                              placeholder="Ej: Resolver los ejercicios de la página 15 al 20">{{ old('descripcion') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ponderacion">Ponderación (%) *</label>
                            <input type="number"
                                   name="ponderacion"
                                   id="ponderacion"
                                   class="form-control @error('ponderacion') is-invalid @enderror"
                                   value="{{ old('ponderacion', 0) }}"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   required>
                            @error('ponderacion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_periodo">Periodo Académico *</label>
                            <select name="id_periodo" id="id_periodo" class="form-control" required>
                                <option value="">Seleccione un periodo</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}" {{ old('id_periodo') == $periodo->id ? 'selected' : '' }}>
                                        {{ $periodo->nombre }} ({{ $periodo->anio }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Guardar Tarea
                    </button>
                    <a href="{{ route('teacher.tareas.index', $materia->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
