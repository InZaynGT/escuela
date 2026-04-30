@extends('layouts.base')

@section('title', 'Boleta de Notas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Boleta de Notas</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

{{-- ── Imprimir todas las boletas por grado-sección ── --}}
<div class="card no-print mb-3">
    <div class="card-header">Imprimir todas las boletas por grado-sección</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reportes.boletas.print-all') }}" target="_blank">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label for="zipGrado">Grado</label>
                    <select id="zipGrado" class="form-control form-control-sm">
                        <option value="">— Selecciona un grado —</option>
                        @foreach($gradoSecciones->pluck('grado')->unique('id')->sortBy('id') as $grado)
                            <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="zipSeccion">Sección</label>
                    <select id="zipSeccion" name="id_grado_seccion" class="form-control form-control-sm" required>
                        <option value="">— Primero selecciona grado —</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="zipAnio">Año</label>
                    <input type="number" id="zipAnio" name="anio" value="{{ date('Y') }}"
                           class="form-control form-control-sm" min="2020">
                </div>
                <div class="col-md-3 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-dark">
                        <i class="fas fa-print"></i> Imprimir todas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Selector de estudiante ── --}}
<div class="card no-print mb-3">
    <div class="card-header">Seleccionar estudiante</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reportes.boletas') }}">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label for="boletaGrado">Grado</label>
                    <select id="boletaGrado" class="form-control form-control-sm">
                        <option value="">— Selecciona un grado —</option>
                        @foreach($gradoSecciones->pluck('grado')->unique('id')->sortBy('id') as $grado)
                            <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="boletaSeccion">Sección</label>
                    <select id="boletaSeccion" class="form-control form-control-sm">
                        <option value="">Todas las secciones</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label for="boletaEstudiante">Estudiante</label>
                    <select id="boletaEstudiante" name="id_estudiante" class="form-control form-control-sm" required>
                        <option value="">— Selecciona grado primero —</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="boletaAnio">Año</label>
                    <input type="number" id="boletaAnio" name="anio" value="{{ $anio }}"
                           class="form-control form-control-sm" min="2020">
                </div>
                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-dark">
                        <i class="fas fa-eye"></i> Ver boleta
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($seleccionado && $inscripcion)
<div class="card">
    <div class="card-body">

        <div class="text-center mb-3">
            <h5 style="font-weight:700;margin-bottom:.25rem;">ESCUELA OFICIAL RURAL MIXTA</h5>
            <p style="margin:0;font-size:.85rem;color:#546e7a;">
                {{ $inscripcion->gradoSeccion->grado->nombre ?? '' }}
                {{ $inscripcion->gradoSeccion->seccion->nombre ?? '' }} — Año {{ $anio }}
            </p>
            <hr>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr><th style="width:130px">Nombre:</th><td>{{ $seleccionado->nombre_completo }}</td></tr>
                    <tr><th>CUI:</th><td>{{ $seleccionado->cui ?? '—' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6 text-right no-print">
                <a href="{{ route('admin.reportes.boletas.print', [$seleccionado->id, 'anio' => $anio]) }}"
                   class="btn btn-sm btn-dark" target="_blank">
                    <i class="fas fa-print"></i> Imprimir / Guardar PDF
                </a>
            </div>
        </div>

        @if($materias->isEmpty())
            <div class="alert alert-secondary">No hay materias registradas para este año.</div>
        @else
        <table class="table table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Materia</th>
                    @foreach($periodos as $periodo)
                        <th class="text-center" style="min-width:90px;">{{ $periodo->nombre }}</th>
                    @endforeach
                    <th class="text-center" style="min-width:90px;">Nota Final</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materias as $item)
                @php
                    $nf = $item['notaFinal'];
                    $periodosConNota = collect($item['notasPorPeriodo'])->filter(fn($n) => $n !== null)->count();
                    $totalPeriodos   = $periodos->count();
                    if ($nf === null) { $estadoTxt = '—'; $estadoCls = 'text-muted'; }
                    elseif ($periodosConNota < $totalPeriodos) { $estadoTxt = 'EN CURSO'; $estadoCls = 'text-secondary'; }
                    elseif ($nf >= 60) { $estadoTxt = 'APROBADO'; $estadoCls = 'nota-alta'; }
                    else { $estadoTxt = 'REPROBADO'; $estadoCls = 'nota-baja'; }
                @endphp
                <tr>
                    <td>{{ $item['materia']->nombre }}</td>
                    @foreach($periodos as $periodo)
                        @php $nota = $item['notasPorPeriodo'][$periodo->id] ?? null; @endphp
                        <td class="text-center">
                            @if($nota !== null)
                                @php $cls = $nota >= 70 ? 'nota-alta' : ($nota >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                                <span class="{{ $cls }}">{{ number_format($nota, 1) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center">
                        @if($nf !== null)
                            @php $cls = $nf >= 70 ? 'nota-alta' : ($nf >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                            <strong class="{{ $cls }}">{{ number_format($nf, 1) }}</strong>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center"><span class="{{ $estadoCls }}"><strong>{{ $estadoTxt }}</strong></span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3 text-center" style="font-size:.78rem;color:#78909c;">
            Generado el {{ now()->format('d/m/Y') }}
        </div>
        @endif
    </div>
</div>
@elseif($seleccionado && !$inscripcion)
<div class="alert alert-warning">
    El estudiante seleccionado no tiene inscripción activa para el año {{ $anio }}.
</div>
@endif

@stop

@section('js')
<script>
const gradoSecciones = @json(
    $gradoSecciones->groupBy('id_grado')
        ->map(fn($g) => $g->map(fn($gs) => ['id' => $gs->id, 'nombre' => $gs->seccion?->nombre ?? '—'])->values())
);
const apiUrl = "{{ route('admin.api.estudiantes') }}";

function poblarSecciones(selectGrado, selectSeccion, onDone) {
    selectSeccion.innerHTML = '<option value="">Todas las secciones</option>';
    const secciones = gradoSecciones[selectGrado.value] ?? [];
    secciones.forEach(s => {
        const o = document.createElement('option');
        o.value = s.id; o.textContent = s.nombre;
        selectSeccion.appendChild(o);
    });
    if (onDone) onDone();
}

function poblarEstudiantes(selectSeccion, selectGrado, selectEst, anioInput) {
    selectEst.innerHTML = '<option value="">Cargando...</option>';
    const params = new URLSearchParams({ anio: anioInput.value });
    if (selectSeccion.value) {
        params.set('id_grado_seccion', selectSeccion.value);
    } else if (selectGrado.value) {
        params.set('id_grado', selectGrado.value);
    } else {
        selectEst.innerHTML = '<option value="">— Selecciona grado primero —</option>';
        return;
    }
    fetch(apiUrl + '?' + params)
        .then(r => r.json())
        .then(data => {
            selectEst.innerHTML = '<option value="">— Selecciona un estudiante —</option>';
            data.forEach(e => {
                const o = document.createElement('option');
                o.value = e.id; o.textContent = e.texto;
                if (String(e.id) === "{{ $idEstudiante }}") o.selected = true;
                selectEst.appendChild(o);
            });
        });
}

// ── Selector de boleta ──
const bGrado   = document.getElementById('boletaGrado');
const bSeccion = document.getElementById('boletaSeccion');
const bEst     = document.getElementById('boletaEstudiante');
const bAnio    = document.getElementById('boletaAnio');

bGrado.addEventListener('change', () => {
    poblarSecciones(bGrado, bSeccion, () => poblarEstudiantes(bSeccion, bGrado, bEst, bAnio));
});
bSeccion.addEventListener('change', () => poblarEstudiantes(bSeccion, bGrado, bEst, bAnio));

// ── Selector ZIP ──
const zGrado   = document.getElementById('zipGrado');
const zSeccion = document.getElementById('zipSeccion');

function poblarSeccionesZip() {
    zSeccion.innerHTML = '<option value="">— Selecciona una sección —</option>';
    (gradoSecciones[zGrado.value] ?? []).forEach(s => {
        const o = document.createElement('option');
        o.value = s.id; o.textContent = s.nombre;
        zSeccion.appendChild(o);
    });
}
zGrado.addEventListener('change', poblarSeccionesZip);

// ── Restaurar si hay estudiante activo ──
@if($idEstudiante && $seleccionado)
(function() {
    const gsId = "{{ $inscripcion?->id_grado_seccion }}";
    const gradoId = "{{ $inscripcion?->gradoSeccion?->id_grado }}";
    if (gradoId) {
        bGrado.value = gradoId;
        poblarSecciones(bGrado, bSeccion, () => {
            if (gsId) bSeccion.value = gsId;
            poblarEstudiantes(bSeccion, bGrado, bEst, bAnio);
        });
    }
})();
@endif
</script>
@endsection
