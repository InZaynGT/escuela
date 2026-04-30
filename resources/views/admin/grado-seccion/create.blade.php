@extends('layouts.base')

@section('title', 'Nueva Combinación')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Nueva Combinación Grado-Sección</h1>
        <a href="{{ route('admin.grado-seccion.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Crear Combinación</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.grado-seccion.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="id_grado">Grado *</label>
                    <select name="id_grado" id="id_grado" class="form-control" required>
                        <option value="">Seleccione un grado</option>
                        @foreach($grados as $grado)
                            <option value="{{ $grado->id }}" {{ old('id_grado') == $grado->id ? 'selected' : '' }}>
                                {{ $grado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_seccion">Sección *</label>
                    <select name="id_seccion" id="id_seccion" class="form-control" required>
                        <option value="">Seleccione una sección</option>
                        @foreach($secciones as $seccion)
                            <option value="{{ $seccion->id }}" {{ old('id_seccion') == $seccion->id ? 'selected' : '' }}>
                                {{ $seccion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">Guardar</button>
                    <a href="{{ route('admin.grado-seccion.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@stop
