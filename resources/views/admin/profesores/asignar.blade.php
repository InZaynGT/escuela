@extends('layouts.base')

@section('title', 'Asignar Materias')

@section('content_header')
    <h1>Asignar Materias a {{ $profesor->nombre }} {{ $profesor->apellidos }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Seleccione las materias que impartirá</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profesores.asignar.guardar', $profesor->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Materias disponibles</label>
                    <select name="materias[]" id="materias" class="form-control select2" multiple style="width: 100%;" required>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->id }}"
                                {{ in_array($materia->id, $materiasAsignadas) ? 'selected' : '' }}>
                                {{ $materia->nombre }} -
                                {{ $materia->gradoSeccion->grado->nombre ?? '' }}
                                {{ $materia->gradoSeccion->seccion->nombre ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Guardar Asignación
                    </button>
                    <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Seleccione las materias",
                allowClear: true
            });
        });
    </script>
@stop
