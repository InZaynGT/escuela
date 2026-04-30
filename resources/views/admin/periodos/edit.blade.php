@extends('layouts.base')

@section('title', 'Editar Periodo')

@section('content_header')
    <h1>Editar Periodo</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Editar: {{ $periodo->nombre }}</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.periodos.update', $periodo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre">Nombre del Periodo *</label>
                            <input type="text"
                                   name="nombre"
                                   id="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $periodo->nombre) }}"
                                   required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="anio">Año *</label>
                            <input type="number"
                                   name="anio"
                                   id="anio"
                                   class="form-control @error('anio') is-invalid @enderror"
                                   value="{{ old('anio', $periodo->anio) }}"
                                   required>
                            @error('anio')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date"
                                   name="fecha_inicio"
                                   id="fecha_inicio"
                                   class="form-control @error('fecha_inicio') is-invalid @enderror"
                                   value="{{ old('fecha_inicio', $periodo->fecha_inicio) }}">
                            @error('fecha_inicio')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha_fin">Fecha de Fin</label>
                            <input type="date"
                                   name="fecha_fin"
                                   id="fecha_fin"
                                   class="form-control @error('fecha_fin') is-invalid @enderror"
                                   value="{{ old('fecha_fin', $periodo->fecha_fin) }}">
                            @error('fecha_fin')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.periodos.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
