@extends('layouts.base')

@section('title', 'Mi Asistencia')

@section('content_header')
    <h1>Mi Asistencia</h1>
@stop

@section('content')

{{-- Resumen --}}
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
<div class="alert alert-info">
    Porcentaje de asistencia: <strong>{{ $porcentaje }}%</strong>
    ({{ $presentes }} de {{ $total }} registros)
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
        <div>Historial de asistencia</div>
    </div>
    <div class="card-body p-0">
        @if($asistencias->isEmpty())
            <div class="p-3">
                <div class="alert alert-info mb-0">Aún no hay registros de asistencia.</div>
            </div>
        @else
        <table class="table table-bordered table-hover mb-0" id="tablaAsistencia">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Materia</th>
                    <th class="text-center">Estado</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asistencias as $a)
                <tr>
                    <td>{{ $a->fecha->format('d/m/Y') }}</td>
                    <td>{{ $a->materia?->nombre ?? '—' }}</td>
                    <td class="text-center">
                        @php
                            $mapa = [
                                'presente'    => ['badge-success', 'Presente'],
                                'ausente'     => ['badge-danger',  'Ausente'],
                                'tardanza'    => ['badge-warning', 'Tardanza'],
                                'justificado' => ['badge-info',    'Justificado'],
                            ];
                            [$cls, $texto] = $mapa[$a->estado] ?? ['badge-secondary', $a->estado];
                        @endphp
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
