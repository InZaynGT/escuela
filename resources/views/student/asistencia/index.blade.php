@extends('layouts.base')

@section('title', 'Mi Asistencia')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Mi Asistencia</h1>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

{{-- Resumen de contadores --}}
<div class="row mb-3">
    <div class="col-6 col-md-3 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div style="font-size:1.6rem;font-weight:700;color:#2e7d32;">{{ $presentes }}</div>
                <div style="font-size:.78rem;color:#546e7a;">Presentes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div style="font-size:1.6rem;font-weight:700;color:#b71c1c;">{{ $ausentes }}</div>
                <div style="font-size:.78rem;color:#546e7a;">Ausentes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div style="font-size:1.6rem;font-weight:700;color:#e65100;">{{ $tardanzas }}</div>
                <div style="font-size:.78rem;color:#546e7a;">Tardanzas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div style="font-size:1.6rem;font-weight:700;color:#01579b;">{{ $justificados }}</div>
                <div style="font-size:.78rem;color:#546e7a;">Justificados</div>
            </div>
        </div>
    </div>
</div>

@if($porcentaje !== null)
<div class="alert alert-secondary">
    Porcentaje de asistencia: <strong>{{ $porcentaje }}%</strong>
    ({{ $presentes }} presentes de {{ $total }} días registrados)
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Historial de asistencia</div>
        {{-- Filtro por ciclo --}}
        @if($periodos->isNotEmpty())
        <form method="GET" action="{{ route('student.asistencia.index') }}"
              class="d-flex align-items-center" style="gap:.4rem;">
            <select name="id_periodo" class="form-control form-control-sm" style="min-width:160px;">
                <option value="">Todos los ciclos</option>
                @foreach($periodos as $p)
                    <option value="{{ $p->id }}"
                        {{ request('id_periodo') == $p->id ? 'selected' : '' }}>
                        {{ $p->nombre }} ({{ $p->anio }})
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-secondary">Filtrar</button>
        </form>
        @endif
    </div>
    <div class="card-body p-0">
        @if($asistencias->isEmpty())
            <div class="p-3">
                <div class="alert alert-secondary mb-0">Aún no hay registros de asistencia.</div>
            </div>
        @else
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Grado / Sección</th>
                    <th>Ciclo</th>
                    <th class="text-center">Estado</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asistencias as $a)
                @php
                    $mapa = [
                        'presente'    => ['badge-success', 'Presente'],
                        'ausente'     => ['badge-danger',  'Ausente'],
                        'tardanza'    => ['badge-warning', 'Tardanza'],
                        'justificado' => ['badge-info',    'Justificado'],
                    ];
                    [$cls, $texto] = $mapa[$a->estado] ?? ['badge-secondary', $a->estado];
                @endphp
                <tr>
                    <td>{{ $a->fecha->format('d/m/Y') }}</td>
                    <td>
                        {{ $a->gradoSeccion->grado->nombre ?? '—' }}
                        {{ $a->gradoSeccion->seccion->nombre ?? '' }}
                    </td>
                    <td>{{ $a->periodo?->nombre ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $cls }} text-white">{{ $texto }}</span>
                    </td>
                    <td><small class="text-muted">{{ $a->observacion ?? '—' }}</small></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $asistencias->links() }}</div>
        @endif
    </div>
</div>

@stop
