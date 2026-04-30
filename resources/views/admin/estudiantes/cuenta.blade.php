@extends('layouts.base')

@section('title', 'Cuenta de Acceso')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Cuenta de Acceso
            <small>{{ $estudiante->nombre_completo }}</small>
        </h1>
        <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>
            @if($estudiante->user)
                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Cuenta activa</span>
                <span class="text-muted ml-2" style="font-size:.85rem;">{{ $estudiante->user->email }}</span>
            @else
                <span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Sin cuenta</span>
                <small class="text-muted ml-2">El estudiante no puede iniciar sesión aún</small>
            @endif
        </div>
        <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.estudiantes.cuenta.update', $estudiante->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="email">Correo electrónico *</label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $estudiante->user?->email) }}"
                       required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <hr>
            <p class="text-muted" style="font-size:.85rem;">
                @if($estudiante->user)
                    Deja los campos de contraseña vacíos para no cambiarla.
                @else
                    Si no ingresas contraseña se usará <strong>estudiante123</strong> por defecto.
                @endif
            </p>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password">Nueva contraseña</label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="form-control"
                               placeholder="Repetir contraseña">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-dark btn-sm">
                <i class="fas fa-save"></i>
                {{ $estudiante->user ? 'Actualizar cuenta' : 'Crear cuenta' }}
            </button>
        </form>
    </div>
</div>
@stop
