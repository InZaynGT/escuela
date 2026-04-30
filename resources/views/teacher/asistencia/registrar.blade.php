@extends('layouts.base')

@section('title', 'Registrar Asistencia')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Asistencia —
            {{ $gradoSeccion->grado->nombre ?? '' }}
            {{ $gradoSeccion->seccion->nombre ?? '' }}
        </h1>
        <a href="{{ route('teacher.asistencia.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Registro del día</div>
        <a href="{{ route('teacher.asistencia.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">

        {{-- Selector de fecha y periodo --}}
        <form method="GET"
              action="{{ route('teacher.asistencia.registrar', $gradoSeccion->id) }}"
              class="d-flex flex-wrap align-items-end mb-3" style="gap:.5rem;">
            <div>
                <label class="d-block" style="font-size:.82rem;font-weight:500;color:#546e7a;">Fecha</label>
                @php
                    $dateMin = $periodoActivo ? $periodoActivo->fecha_inicio : null;
                    $dateMax = $periodoActivo
                        ? min($periodoActivo->fecha_fin, now()->toDateString())
                        : now()->toDateString();
                @endphp
                <input type="date" name="fecha" value="{{ $fecha }}"
                       class="form-control form-control-sm"
                       max="{{ $dateMax }}"
                       @if($dateMin) min="{{ $dateMin }}" @endif>
            </div>
            <div>
                <button type="submit" class="btn btn-sm btn-secondary">Cargar</button>
            </div>
        </form>

        @if(!$periodoActivo)
            <div class="alert alert-warning py-2 mb-3">
                <i class="fas fa-exclamation-triangle"></i>
                No hay un ciclo activo para hoy. Solo puedes registrar hasta la fecha de hoy.
            </div>
        @else
            <div class="alert alert-secondary py-2 mb-3">
                <i class="fas fa-calendar-check"></i>
                Ciclo activo: <strong>{{ $periodoActivo->nombre }} ({{ $periodoActivo->anio }})</strong>
                — {{ \Carbon\Carbon::parse($periodoActivo->fecha_inicio)->format('d/m/Y') }}
                al {{ \Carbon\Carbon::parse($periodoActivo->fecha_fin)->format('d/m/Y') }}
            </div>
        @endif

        @if($estudiantes->isEmpty())
            <div class="alert alert-secondary mb-0">No hay estudiantes inscritos en este grado-sección.</div>
        @else

        <form method="POST"
              action="{{ route('teacher.asistencia.guardar', $gradoSeccion->id) }}">
            @csrf
            <input type="hidden" name="fecha" value="{{ $fecha }}">

            {{-- Selector de periodo --}}
            <div class="mb-3 d-none" style="max-width:300px;">
                <label style="font-size:.82rem;font-weight:500;color:#546e7a;">Ciclo / Periodo</label>
                <select name="id_periodo" class="form-control form-control-sm">
                    <!-- <option value="">— Sin ciclo —</option> -->
                    <option value="{{$periodoActivo->id}}" selected>{{ $periodoActivo->nombre }} ({{ $periodoActivo->anio }})</option>
                </select>
            </div>
            

            {{-- Botones rápidos --}}
            <div class="mb-2 d-flex flex-wrap" style="gap:.4rem;">
                <button type="button" class="btn btn-sm btn-secondary" id="btnTodosPresente">
                    Marcar todos Presente
                </button>
                <button type="button" class="btn btn-sm btn-secondary" id="btnTodosAusente">
                    Marcar todos Ausente
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th style="width:2.5rem;">#</th>
                            <th>Estudiante</th>
                            <th style="min-width:170px;">Estado</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantes as $i => $estudiante)
                        @php
                            $registro      = $asistenciasHoy[$estudiante->id] ?? null;
                            $estadoActual  = $registro?->estado ?? 'presente';
                            $badgeMap = [
                                'presente'    => 'badge-success',
                                'ausente'     => 'badge-danger',
                                'tardanza'    => 'badge-warning',
                                'justificado' => 'badge-info',
                            ];
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                {{ $estudiante->nombre_completo }}
                                @if($registro)
                                    <br>
                                    <small class="text-muted" style="font-size:.72rem;">
                                        Última edición realizada por:
                                        @if($registro->updatedBy)
                                            {{ $registro->updatedBy->name }}
                                        @else
                                            —
                                        @endif
                                        · {{ $registro->updated_at->format('d/m H:i') }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <select name="asistencias[{{ $estudiante->id }}][estado]"
                                        class="form-control form-control-sm select-estado">
                                    @foreach(\App\Models\Asistencia::$estados as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ $estadoActual === $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text"
                                       name="asistencias[{{ $estudiante->id }}][observacion]"
                                       class="form-control form-control-sm"
                                       value="{{ $registro?->observacion ?? '' }}"
                                       placeholder="Opcional">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </small>
                <button type="submit" class="btn btn-dark btn-sm">
                    <i class="fas fa-save"></i> Guardar asistencia
                </button>
            </div>
        </form>

        @endif
    </div>
</div>

@stop

@section('js')
<script>
document.getElementById('btnTodosPresente').addEventListener('click', function () {
    document.querySelectorAll('.select-estado').forEach(function (sel) {
        sel.value = 'presente';
    });
});
document.getElementById('btnTodosAusente').addEventListener('click', function () {
    document.querySelectorAll('.select-estado').forEach(function (sel) {
        sel.value = 'ausente';
    });
});
</script>
@endsection
