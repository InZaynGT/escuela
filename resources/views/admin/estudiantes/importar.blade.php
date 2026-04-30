@extends('layouts.base')

@section('title', 'Importar Estudiantes')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Importar Estudiantes desde Excel</h1>
    <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Volver
    </a>
</div>
@stop

@section('content')

{{-- Resultado de importación --}}
@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
</div>
@if(session('advertencias') && count(session('advertencias')) > 0)
<div class="alert alert-warning">
    <strong>Advertencias:</strong>
    <ul class="mb-0 mt-1">
        @foreach(session('advertencias') as $adv)
        <li>{{ $adv }}</li>
        @endforeach
    </ul>
</div>
@endif
@endif

<div class="row">
    {{-- Formulario de importación --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-file-excel mr-1"></i> Subir archivo
            </div>
            <div class="card-body">
                <form action="{{ route('admin.estudiantes.importar.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="id_grado_seccion">Grado / Sección <span class="text-danger">*</span></label>
                        <select name="id_grado_seccion" id="id_grado_seccion"
                            class="form-control @error('id_grado_seccion') is-invalid @enderror" required>
                            <option value="">— Selecciona —</option>
                            @foreach($gradoSecciones->groupBy('id_grado') as $gradoId => $secciones)
                            <optgroup label="{{ $secciones->first()->grado->nombre ?? '' }}">
                                @foreach($secciones as $gs)
                                <option value="{{ $gs->id }}"
                                    {{ old('id_grado_seccion') == $gs->id ? 'selected' : '' }}>
                                    Sección {{ $gs->seccion->nombre ?? '—' }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        @error('id_grado_seccion')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="anio">Año escolar <span class="text-danger">*</span></label>
                        <input type="number" name="anio" id="anio"
                            class="form-control @error('anio') is-invalid @enderror"
                            value="{{ old('anio', date('Y')) }}"
                            min="2020" max="{{ date('Y') + 1 }}" style="width:120px" required>
                        @error('anio')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="archivo">Archivo Excel <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" name="archivo" id="archivo"
                                class="custom-file-input @error('archivo') is-invalid @enderror"
                                accept=".xlsx,.xls,.csv" required>
                            <label class="custom-file-label" for="archivo">Seleccionar archivo...</label>
                        </div>
                        <small class="text-muted">Formatos aceptados: .xlsx, .xls, .csv — Máximo 2 MB</small>
                        @error('archivo')
                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-upload mr-1"></i> Importar
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Instrucciones y plantilla --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle mr-1"></i> Instrucciones
            </div>
            <div class="card-body">
                <p class="mb-2">El archivo debe tener las siguientes columnas en la primera fila:</p>
                <table class="table table-sm table-bordered mb-3">
                    <thead class="thead-dark">
                        <tr>
                            <th>Columna</th>
                            <th>Requerido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>Nombre</code></td>
                            <td><span class="badge badge-danger">Sí</span></td>
                        </tr>
                        <tr>
                            <td><code>Apellidos</code></td>
                            <td><span class="badge badge-danger">Sí</span></td>
                        </tr>
                        <tr>
                            <td><code>CUI</code></td>
                            <td><span class="badge badge-secondary">No</span></td>
                        </tr>
                        <tr>
                            <td><code>Fecha_Nacimiento</code></td>
                            <td><span class="badge badge-secondary">No</span></td>
                        </tr>
                        <tr>
                            <td><code>Telefono</code></td>
                            <td><span class="badge badge-secondary">No</span></td>
                        </tr>
                    </tbody>
                </table>

                <ul class="pl-3 mb-3" style="font-size:.875rem;">
                    <li>Las filas sin Nombre o Apellidos serán omitidas.</li>
                    <li>Si el CUI ya existe, el estudiante no se duplica — solo se inscribe.</li>
                    <li>Fecha en formato <code>YYYY-MM-DD</code> (ej. 2010-05-15).</li>
                    <li>El orden de las columnas no importa, solo los encabezados.</li>
                </ul>

                <a href="{{ route('admin.estudiantes.plantilla') }}" class="btn btn-sm btn-outline-dark">
                    <i class="fas fa-file-download mr-1"></i> Descargar plantilla Excel
                </a>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    // Mostrar nombre del archivo seleccionado en el custom-file-input
    document.getElementById('archivo').addEventListener('change', function() {
        const label = this.nextElementSibling;
        label.textContent = this.files[0] ? this.files[0].name : 'Seleccionar archivo...';
    });
</script>
@endsection