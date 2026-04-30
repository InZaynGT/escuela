@extends('layouts.base')

@section('title', 'Editar Tarea')

@section('content_header')
    <h1>
        <i class="fas fa-edit"></i> Editar Tarea
        <small>{{ $materia->nombre }}</small>
    </h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title mb-0">
                <i class="fas fa-pencil-alt"></i> Editando: {{ $tarea->titulo }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.tareas.update', [$materia->id, $tarea->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="titulo">Título de la Tarea *</label>
                    <input type="text" 
                           name="titulo" 
                           id="titulo" 
                           class="form-control @error('titulo') is-invalid @enderror" 
                           value="{{ old('titulo', $tarea->titulo) }}"
                           required>
                    @error('titulo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" 
                              id="descripcion" 
                              class="form-control" 
                              rows="3">{{ old('descripcion', $tarea->descripcion) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ponderacion">Ponderación (%) *</label>
                            <input type="number" 
                                   name="ponderacion" 
                                   id="ponderacion" 
                                   class="form-control @error('ponderacion') is-invalid @enderror" 
                                   value="{{ old('ponderacion', $tarea->ponderacion) }}"
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
                        <div class="form-group">
                            <label for="id_periodo">Periodo Académico *</label>
                            <select name="id_periodo" id="id_periodo" class="form-control" required>
                                <option value="">Seleccione un periodo</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}" 
                                        {{ old('id_periodo', $tarea->id_periodo) == $periodo->id ? 'selected' : '' }}>
                                        {{ $periodo->nombre }} ({{ $periodo->anio }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('teacher.tareas.index', $materia->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop