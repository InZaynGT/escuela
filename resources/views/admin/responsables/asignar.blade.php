@extends('layouts.base')

@section('title', 'Asignar Estudiantes')

@section('content_header')
    <h1>Asignar estudiantes a {{ $responsable->nombre_completo }}</h1>
@stop

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Selecciona los estudiantes a cargo de este responsable</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.responsables.asignar.guardar', $responsable->id) }}">
            @csrf

            @if($estudiantes->isEmpty())
                <div class="alert alert-info mb-0">No hay estudiantes registrados.</div>
            @else
            <div style="max-height:380px;overflow-y:auto;border:1px solid #e0e0e0;border-radius:3px;padding:.5rem;">
                @foreach($estudiantes as $est)
                <div class="form-check py-1">
                    <input class="form-check-input" type="checkbox"
                           name="estudiantes[]"
                           value="{{ $est->id }}"
                           id="est_{{ $est->id }}"
                           {{ in_array($est->id, $asignados) ? 'checked' : '' }}>
                    <label class="form-check-label" for="est_{{ $est->id }}">
                        {{ $est->nombre_completo }}
                        @if($est->inscripcionActiva)
                            <small class="text-muted">
                                — {{ $est->inscripcionActiva->gradoSeccion->grado->nombre ?? '' }}
                                {{ $est->inscripcionActiva->gradoSeccion->seccion->nombre ?? '' }}
                            </small>
                        @endif
                    </label>
                </div>
                @endforeach
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
