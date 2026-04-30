@extends('layouts.base')

@section('title', 'Nuevo Grado')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Nuevo Grado</h1>
        <a href="{{ route('admin.grados.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Crear Grado</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.grados.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre">Nombre del Grado *</label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}"
                           placeholder="Ej: 1ro Primaria, 2do Primaria, 1ro Básico"
                           required>
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('admin.grados.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
