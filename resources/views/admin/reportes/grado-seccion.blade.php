@extends('layouts.base')

@section('title', 'Listado por Grado y Sección')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Listado por Grado y Sección</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

<div class="card no-print mb-3">
    <div class="card-header">Filtros</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reportes.grado-seccion') }}">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label for="selectGrado">Grado</label>
                    <select id="selectGrado" class="form-control form-control-sm">
                        <option value="">— Selecciona un grado —</option>
                        @foreach($gradoSecciones->pluck('grado')->unique('id')->sortBy('id') as $grado)
                            <option value="{{ $grado->id }}"
                                {{ $idGradoSeccion && $gradoSecciones->where('id', $idGradoSeccion)->first()?->id_grado == $grado->id ? 'selected' : '' }}>
                                {{ $grado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="selectSeccion">Sección</label>
                    <select id="selectSeccion" name="id_grado_seccion" class="form-control form-control-sm" required>
                        <option value="">— Primero selecciona grado —</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="inputAnio">Año</label>
                    <input type="number" id="inputAnio" name="anio" value="{{ $anio }}"
                           class="form-control form-control-sm" min="2020">
                </div>
                <div class="col-md-3 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-dark">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($seleccionado)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            {{ $seleccionado->grado->nombre ?? '' }} — {{ $seleccionado->seccion->nombre ?? '' }}
            <small class="text-muted">Año {{ $anio }}</small>
        </span>
        <button onclick="window.print()" class="btn btn-sm btn-secondary no-print">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>
    <div class="card-body p-0">
        @if($estudiantes->isEmpty())
            <div class="p-3">
                <div class="alert alert-secondary mb-0">No hay estudiantes inscritos.</div>
            </div>
        @else
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Apellidos</th>
                    <th>Nombre(s)</th>
                    <th>CUI</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estudiantes as $i => $est)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $est->apellidos }}</td>
                    <td>{{ $est->nombre }}</td>
                    <td>{{ $est->cui ?? '—' }}</td>
                    <td>{{ $est->telefono ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-2 text-right">
            <small class="text-muted">Total: {{ $estudiantes->count() }} estudiante(s)</small>
        </div>
        @endif
    </div>
</div>
@endif

@stop

@section('js')
<script>
const gradoSecciones = @json(
    $gradoSecciones->groupBy('id_grado')
        ->map(fn($g) => $g->map(fn($gs) => ['id' => $gs->id, 'nombre' => $gs->seccion?->nombre ?? '—'])->values())
);

const selectGrado   = document.getElementById('selectGrado');
const selectSeccion = document.getElementById('selectSeccion');
const valorActual   = "{{ $idGradoSeccion }}";

function poblarSecciones(gradoId, seleccionar) {
    selectSeccion.innerHTML = '<option value="">— Selecciona una sección —</option>';
    (gradoSecciones[gradoId] ?? []).forEach(s => {
        const o = document.createElement('option');
        o.value = s.id; o.textContent = s.nombre;
        if (String(s.id) === String(seleccionar)) o.selected = true;
        selectSeccion.appendChild(o);
    });
}

selectGrado.addEventListener('change', () => poblarSecciones(selectGrado.value, null));

if (selectGrado.value) poblarSecciones(selectGrado.value, valorActual);
</script>
@endsection
