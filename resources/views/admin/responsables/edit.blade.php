@extends('layouts.base')

@section('title', 'Editar Responsable')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Responsable</h1>
        <a href="{{ route('admin.responsables.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Datos del responsable</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.responsables.update', $responsable->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label>Nombre(s)</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $responsable->nombre) }}" required>
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror"
                       value="{{ old('apellidos', $responsable->apellidos) }}" required>
                @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control"
                       value="{{ old('telefono', $responsable->telefono) }}">
            </div>
            <div class="mb-3">
                <label>Parentesco</label>
                <select name="parentesco" class="form-control">
                    <option value="">— Seleccionar —</option>
                    @foreach(['Padre','Madre','Tutor/a','Abuelo/a','Tío/a','Hermano/a','Otro'] as $p)
                        <option value="{{ $p }}"
                            {{ old('parentesco', $responsable->parentesco) === $p ? 'selected' : '' }}>
                            {{ $p }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.responsables.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-dark btn-sm">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@stop
