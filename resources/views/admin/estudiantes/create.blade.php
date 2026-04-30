@extends('layouts.base')

@section('title', 'Nuevo Estudiante')

@section('content_header')
    <h1>Nuevo Estudiante</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Registrar Estudiante</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.estudiantes.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre">Nombre *</label>
                            <input type="text"
                                   name="nombre"
                                   id="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre') }}"
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
                                   value="{{ old('apellidos') }}"
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
                            <label for="cui">CUI (Cédula)</label>
                            <input type="text"
                                   name="cui"
                                   id="cui"
                                   class="form-control @error('cui') is-invalid @enderror"
                                   value="{{ old('cui') }}"
                                   placeholder="Ej: 1234567890123">
                            @error('cui')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telefono">Teléfono</label>
                            <input type="text"
                                   name="telefono"
                                   id="telefono"
                                   class="form-control @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono') }}">
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
                                   value="{{ old('fecha_nacimiento') }}">
                            @error('fecha_nacimiento')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Email (para usuario)</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="Ej: estudiante@escuela.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
