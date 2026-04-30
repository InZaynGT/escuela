@extends('layouts.base')

@section('title', 'Editar Grado')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Grado</h1>
        <a href="{{ route('admin.grados.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Editar: {{ $grado->nombre }}</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.grados.update', $grado) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre">Nombre del Grado *</label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $grado->nombre) }}"
                           required>
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.grados.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
