@extends('layouts.base')

@section('title', 'Editar Estudiante')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Estudiante</h1>
        <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Editar: {{ $estudiante->nombre }} {{ $estudiante->apellidos }}</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.estudiantes.update', $estudiante->id) }}" method="POST">
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
                                   value="{{ old('nombre', $estudiante->nombre) }}"
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
                                   value="{{ old('apellidos', $estudiante->apellidos) }}"
                                   required>
                            @error('apellidos')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cui">CUI *</label>
                            <input type="text"
                                   name="cui"
                                   id="cui"
                                   class="form-control @error('cui') is-invalid @enderror"
                                   value="{{ old('cui', $estudiante->cui) }}"
                                   required>
                            @error('cui')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Es el usuario y contraseña de acceso del estudiante.</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telefono">Teléfono</label>
                            <input type="text"
                                   name="telefono"
                                   id="telefono"
                                   class="form-control @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono', $estudiante->telefono) }}">
                            @error('telefono')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                            <input type="date"
                                   name="fecha_nacimiento"
                                   id="fecha_nacimiento"
                                   class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                   value="{{ old('fecha_nacimiento', $estudiante->fecha_nacimiento) }}">
                            @error('fecha_nacimiento')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.estudiantes.cuenta', $estudiante->id) }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-key"></i>
                        {{ $estudiante->user ? 'Gestionar cuenta' : 'Crear cuenta de acceso' }}
                    </a>
                    <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
