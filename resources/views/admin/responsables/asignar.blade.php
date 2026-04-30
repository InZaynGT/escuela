@extends('layouts.base')

@section('title', 'Asignar Estudiantes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Asignar estudiantes a {{ $responsable->nombre_completo }}</h1>
        <a href="{{ route('admin.responsables.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
<div class="card" style="max-width:650px">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Selecciona los estudiantes a cargo de este responsable</div>
        <a href="{{ route('admin.responsables.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">

        {{-- Filtro client-side --}}
        <div class="row mb-3">
            <div class="col-md-5">
                <label for="filtroGrado" class="small">Filtrar por grado</label>
                <select id="filtroGrado" class="form-control form-control-sm">
                    <option value="">Todos los grados</option>
                    @foreach($gradoSecciones->pluck('grado')->unique('id')->sortBy('id') as $grado)
                        <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="filtroSeccion" class="small">Sección</label>
                <select id="filtroSeccion" class="form-control form-control-sm">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroNombre" class="small">Nombre</label>
                <input type="text" id="filtroNombre" class="form-control form-control-sm" placeholder="Buscar...">
            </div>
        </div>

        <form method="POST" action="{{ route('admin.responsables.asignar.guardar', $responsable->id) }}">
            @csrf

            @if($estudiantes->isEmpty())
                <div class="alert alert-secondary mb-0">No hay estudiantes registrados.</div>
            @else
            <div id="listaEstudiantes" style="max-height:380px;overflow-y:auto;border:1px solid #e0e0e0;border-radius:3px;padding:.5rem;">
                @foreach($estudiantes as $est)
                @php
                    $ins = $est->inscripcionActiva;
                    $gsId   = $ins?->id_grado_seccion ?? '';
                    $gradoId = $ins?->gradoSeccion?->id_grado ?? '';
                @endphp
                <div class="form-check py-1 est-item"
                     data-grado="{{ $gradoId }}"
                     data-gs="{{ $gsId }}"
                     data-nombre="{{ strtolower($est->nombre_completo) }}">
                    <input class="form-check-input" type="checkbox"
                           name="estudiantes[]"
                           value="{{ $est->id }}"
                           id="est_{{ $est->id }}"
                           {{ in_array($est->id, $asignados) ? 'checked' : '' }}>
                    <label class="form-check-label" for="est_{{ $est->id }}">
                        {{ $est->nombre_completo }}
                        @if($ins)
                            <small class="text-muted">
                                — {{ $ins->gradoSeccion->grado->nombre ?? '' }}
                                {{ $ins->gradoSeccion->seccion->nombre ?? '' }}
                            </small>
                        @endif
                    </label>
                </div>
                @endforeach
            </div>
            <div id="sinResultados" class="text-muted text-center p-3 d-none">
                Ningún estudiante coincide con el filtro.
            </div>
            @endif

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('admin.responsables.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-dark btn-sm">Guardar asignación</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
const gradoSecciones = @json(
    $gradoSecciones->groupBy('id_grado')
        ->map(fn($g) => $g->map(fn($gs) => ['id' => $gs->id, 'nombre' => $gs->seccion?->nombre ?? '—'])->values())
);

const filtroGrado   = document.getElementById('filtroGrado');
const filtroSeccion = document.getElementById('filtroSeccion');
const filtroNombre  = document.getElementById('filtroNombre');

function actualizarFiltro() {
    const gradoVal   = filtroGrado.value;
    const seccionVal = filtroSeccion.value;
    const nombreVal  = filtroNombre.value.toLowerCase().trim();
    let visibles = 0;

    document.querySelectorAll('.est-item').forEach(el => {
        const okGrado   = !gradoVal   || el.dataset.grado === gradoVal;
        const okSeccion = !seccionVal || el.dataset.gs    === seccionVal;
        const okNombre  = !nombreVal  || el.dataset.nombre.includes(nombreVal);
        const mostrar = okGrado && okSeccion && okNombre;
        el.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    document.getElementById('sinResultados')?.classList.toggle('d-none', visibles > 0);
}

filtroGrado.addEventListener('change', function () {
    filtroSeccion.innerHTML = '<option value="">Todas</option>';
    (gradoSecciones[this.value] ?? []).forEach(s => {
        const o = document.createElement('option');
        o.value = s.id; o.textContent = s.nombre;
        filtroSeccion.appendChild(o);
    });
    actualizarFiltro();
});

filtroSeccion.addEventListener('change', actualizarFiltro);
filtroNombre.addEventListener('input', actualizarFiltro);
</script>
@endsection
