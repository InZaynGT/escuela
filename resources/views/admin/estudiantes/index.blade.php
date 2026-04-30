@extends('layouts.base')

@section('title', 'Estudiantes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Estudiantes</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

{{-- ── Filtros ── --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Buscar estudiantes</div>
        <div class="d-flex" style="gap:.5rem;">
            <form action="{{ route('admin.estudiantes.generar-cuentas') }}" method="POST"
                  onsubmit="return confirm('¿Generar cuentas de acceso para todos los estudiantes que aún no tienen una? Se usará su CUI como usuario y contraseña.')">
                @csrf
                <button type="submit" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-key"></i> Generar cuentas
                </button>
            </form>
            <a href="{{ route('admin.estudiantes.importar') }}" class="btn btn-outline-dark btn-sm">
                <i class="fas fa-file-excel"></i> Importar Excel
            </a>
            <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-dark btn-sm">
                <i class="fas fa-plus"></i> Nuevo Estudiante
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.estudiantes.index') }}" id="formFiltro">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label for="selectGrado">Grado</label>
                    <select id="selectGrado" class="form-control form-control-sm">
                        <option value="">— Selecciona un grado —</option>
                        @foreach($gradoSecciones->pluck('grado')->unique('id')->sortBy('id') as $grado)
                            <option value="{{ $grado->id }}"
                                {{ request('id_grado_seccion') && $gradoSecciones->where('id', request('id_grado_seccion'))->first()?->id_grado == $grado->id ? 'selected' : '' }}>
                                {{ $grado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label for="selectSeccion">Sección</label>
                    <select id="selectSeccion" name="id_grado_seccion" class="form-control form-control-sm">
                        <option value="">Todas las secciones</option>
                    </select>
                </div>
                <input type="hidden" name="id_grado" id="inputGrado">

                <div class="col-md-4 mb-2">
                    <label for="inputNombre">Nombre o apellido</label>
                    <input type="text" name="nombre" id="inputNombre" class="form-control form-control-sm"
                           placeholder="Opcional — filtrar por nombre"
                           value="{{ request('nombre') }}">
                </div>

                <div class="col-md-3 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark btn-sm mr-2">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Resultados ── --}}
<div class="card">
    <div class="card-header">Resultados</div>
    <div class="card-body p-0">
        @if($estudiantes === null)
            <div class="p-4 text-center text-muted">
                <i class="fas fa-filter fa-2x mb-2"></i><br>
                Selecciona un grado y sección para ver los estudiantes.
            </div>
        @elseif($estudiantes->count() === 0)
            <div class="p-3">
                <div class="alert alert-secondary mb-0">No se encontraron estudiantes con esos filtros.</div>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Apellidos</th>
                        <th>Nombre</th>
                        <th>CUI</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantes as $est)
                    @php $ins = $est->inscripciones->first(); @endphp
                    <tr>
                        <td>{{ $est->apellidos }}</td>
                        <td>{{ $est->nombre }}</td>
                        <td>{{ $est->cui ?? '—' }}</td>
                        <td>{{ $est->telefono ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.estudiantes.edit', $est->id) }}" class="btn btn-dark btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('admin.estudiantes.inscribir', $est->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-graduation-cap"></i> Inscribir
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3">{{ $estudiantes->links() }}</div>
        </div>
        @endif
    </div>
</div>

@stop

@section('js')
<script>
{{-- Mapa grado_id → [{ id, nombre }] de secciones --}}
const gradoSecciones = @json(
    $gradoSecciones->groupBy(fn($gs) => $gs->id_grado ?? $gs->grado?->id)
        ->map(fn($grupo) => $grupo->map(fn($gs) => [
            'id'     => $gs->id,
            'nombre' => $gs->seccion?->nombre ?? '—',
        ])->values())
);

const selectGrado   = document.getElementById('selectGrado');
const selectSeccion = document.getElementById('selectSeccion');
const valorActual   = "{{ request('id_grado_seccion') }}";

function poblarSecciones(gradoId, seleccionar) {
    selectSeccion.innerHTML = '<option value="">— Selecciona una sección —</option>';
    const secciones = gradoSecciones[gradoId] ?? [];
    secciones.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.nombre;
        if (String(s.id) === String(seleccionar)) opt.selected = true;
        selectSeccion.appendChild(opt);
    });
}

const inputGrado = document.getElementById('inputGrado');

selectGrado.addEventListener('change', function () {
    inputGrado.value = this.value;
    poblarSecciones(this.value, null);
});

// Restaurar estado si ya hay filtros activos
if (selectGrado.value) {
    inputGrado.value = selectGrado.value;
    poblarSecciones(selectGrado.value, valorActual);
}
</script>
@endsection
