@extends('layouts.base')

@section('title', 'Asignar Materias')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Asignar materias a {{ $profesor->nombre_completo }}</h1>
        <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <span>Selecciona las materias que impartirá este docente</span>
        <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">

        @php $hayMaterias = $gradoSecciones->flatMap->materias->isNotEmpty(); @endphp

        @if(!$hayMaterias)
            <div class="alert alert-secondary mb-0">No hay materias registradas en el sistema.</div>
        @else
        <form method="POST" action="{{ route('admin.profesores.asignar.guardar', $profesor->id) }}">
            @csrf

            @foreach($gradoSecciones as $gs)
                @if($gs->materias->isEmpty()) @continue @endif
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>{{ $gs->grado->nombre ?? '—' }} — {{ $gs->seccion->nombre ?? '—' }}</strong>
                        <a href="#" class="toggle-grupo text-secondary small" data-grupo="gs{{ $gs->id }}">
                            Seleccionar todo
                        </a>
                    </div>
                    <div class="border rounded p-2" id="gs{{ $gs->id }}">
                        @foreach($gs->materias as $materia)
                            @php
                                $otrosProfs = $materia->profesores->where('id', '!=', $profesor->id);
                                $bloqueada  = $otrosProfs->isNotEmpty();
                                $asignada   = in_array($materia->id, $materiasAsignadas);
                            @endphp
                            <div class="form-check py-1">
                                <input class="form-check-input mat-check"
                                       type="checkbox"
                                       name="materias[]"
                                       value="{{ $materia->id }}"
                                       id="mat{{ $materia->id }}"
                                       data-grupo="gs{{ $gs->id }}"
                                       {{ $asignada ? 'checked' : '' }}
                                       {{ $bloqueada ? 'disabled' : '' }}>
                                <label class="form-check-label {{ $bloqueada ? 'text-muted' : '' }}"
                                       for="mat{{ $materia->id }}">
                                    {{ $materia->nombre }}
                                    @if($bloqueada)
                                        <small class="text-muted">
                                            ({{ $otrosProfs->first()->nombre_completo }})
                                        </small>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between mt-2">
                <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
                <button type="submit" class="btn btn-dark btn-sm">
                    <i class="fas fa-save"></i> Guardar asignación
                </button>
            </div>
        </form>
        @endif

    </div>
</div>
@stop

@section('js')
<script>
document.querySelectorAll('.toggle-grupo').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const checks = document.querySelectorAll(`#${this.dataset.grupo} .mat-check:not(:disabled)`);
        const allChecked = [...checks].every(c => c.checked);
        checks.forEach(c => c.checked = !allChecked);
        this.textContent = allChecked ? 'Seleccionar todo' : 'Deseleccionar todo';
    });
});
</script>
@endsection
