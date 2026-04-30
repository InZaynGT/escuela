@extends('layouts.base')

@section('title', 'Editar Profesor')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Profesor</h1>
        <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Editar: {{ $profesor->nombre }} {{ $profesor->apellidos }}</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profesores.update', $profesor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre">Nombre *</label>
                            <input type="text"
                                   name="nombre"
                                   id="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $profesor->nombre) }}"
                                   required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="apellidos">Apellidos *</label>
                            <input type="text"
                                   name="apellidos"
                                   id="apellidos"
                                   class="form-control @error('apellidos') is-invalid @enderror"
                                   value="{{ old('apellidos', $profesor->apellidos) }}"
                                   required>
                            @error('apellidos')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="telefono">Teléfono</label>
                    <input type="text"
                           name="telefono"
                           id="telefono"
                           class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono', $profesor->telefono) }}">
                    @error('telefono')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.profesores.cuenta', $profesor->id) }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-key"></i>
                        {{ $profesor->user ? 'Gestionar cuenta' : 'Crear cuenta de acceso' }}
                    </a>
                    <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
