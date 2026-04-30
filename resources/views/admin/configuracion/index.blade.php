@extends('layouts.base')

@section('title', 'Configuración del sistema')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Configuración del sistema</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

@php
    $nombreCentro = \App\Models\Configuracion::get('nombre_centro', 'EORM');
    $logoImg      = \App\Models\Configuracion::get('logo_img');
@endphp

<form action="{{ route('admin.configuracion.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ── Identidad del centro ── --}}
    <div class="card mb-3">
        <div class="card-header">Identidad del centro educativo</div>
        <div class="card-body">
            <div class="form-group">
                <label for="nombre_centro">Nombre del centro educativo</label>
                <input type="text" name="nombre_centro" id="nombre_centro"
                       class="form-control @error('nombre_centro') is-invalid @enderror"
                       value="{{ old('nombre_centro', $nombreCentro) }}"
                       placeholder="Ej: EORM Caserío La Esperanza">
                <small class="text-muted">Aparece en la barra lateral y en el encabezado del login.</small>
                @error('nombre_centro')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- ── Logo ── --}}
    <div class="card mb-3">
        <div class="card-header">Logo</div>
        <div class="card-body">

            @if($logoImg)
            <div class="mb-3 d-flex align-items-center" style="gap:1rem;">
                <img src="{{ asset($logoImg) }}" alt="Logo actual"
                     style="max-height:64px; max-width:180px; object-fit:contain; border:1px solid #ddd; padding:4px; border-radius:4px;">
                <div>
                    <div class="text-muted" style="font-size:.82rem;">Logo actual</div>
                    <div class="custom-control custom-checkbox mt-1">
                        <input type="checkbox" class="custom-control-input" id="eliminar_logo" name="eliminar_logo" value="1">
                        <label class="custom-control-label text-danger" for="eliminar_logo">Eliminar logo</label>
                    </div>
                </div>
            </div>
            @endif

            <div class="form-group mb-1">
                <label for="logo">{{ $logoImg ? 'Reemplazar logo' : 'Subir logo' }}</label>
                <input type="file" name="logo" id="logo"
                       class="form-control-file @error('logo') is-invalid @enderror"
                       accept=".png,.jpg,.jpeg,.svg">
                <small class="text-muted">PNG, JPG o SVG · Máx. 1 MB · Se muestra en la barra lateral y en el login. Recomendado: fondo transparente, máx. 200×60 px.</small>
                @error('logo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div id="preview-wrap" class="mt-2" style="display:none;">
                <img id="preview-img" src="" alt="Vista previa"
                     style="max-height:60px; max-width:200px; object-fit:contain; border:1px solid #ddd; padding:4px; border-radius:4px;">
                <div class="text-muted mt-1" style="font-size:.78rem;">Vista previa</div>
            </div>

        </div>
    </div>

    <div class="d-flex" style="gap:.5rem;">
        <button type="submit" class="btn btn-dark">
            <i class="fas fa-save mr-1"></i> Guardar cambios
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

@stop

@section('js')
<script>
document.getElementById('logo').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('preview-wrap').style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection
