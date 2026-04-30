@extends('layouts.base')

@section('title', 'Editar Sección')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Sección</h1>
        <a href="{{ route('admin.secciones.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Editar: {{ $seccion->nombre }}</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.secciones.update', $seccion) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre">Nombre de la Sección *</label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $seccion->nombre) }}"
                           required>
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.secciones.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
