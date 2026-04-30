@extends('layouts.base')

@section('title', 'Editar Materia')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Materia</h1>
        <a href="{{ route('admin.materias.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Editar: {{ $materia->nombre }}</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.materias.update', $materia->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre">Nombre de la Materia *</label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $materia->nombre) }}"
                           required>
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_grado_seccion">Grado - Sección *</label>
                    <select name="id_grado_seccion" id="id_grado_seccion" class="form-control" required>
                        <option value="">Seleccione un grado y sección</option>
                        @foreach($combinaciones as $combinacion)
                            <option value="{{ $combinacion->id }}"
                                {{ old('id_grado_seccion', $materia->id_grado_seccion) == $combinacion->id ? 'selected' : '' }}>
                                {{ $combinacion->grado->nombre }} - Sección {{ $combinacion->seccion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.materias.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
