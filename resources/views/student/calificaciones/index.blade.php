@extends('layouts.base')

@section('title', 'Mis Notas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Mis Notas</h1>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

@if(!$inscripcion)
    <div class="alert alert-secondary">No tienes una inscripción activa para este año.</div>
@elseif($resumen->isEmpty())
    <div class="alert alert-secondary">No hay materias registradas.</div>
@else

<div class="card">
    <div class="card-header">Resumen de calificaciones {{ date('Y') }}</div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-bordered table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Materia</th>
                    @foreach($periodos as $periodo)
                        <th class="text-center">{{ $periodo->nombre }}</th>
                    @endforeach
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resumen as $item)
                <tr>
                    <td>
                        <a href="{{ route('student.materias.show', $item['materia']->id) }}">
                            {{ $item['materia']->nombre }}
                        </a>
                    </td>
                    @foreach($periodos as $periodo)
                    @php $pp = $item['porPeriodo'][$periodo->id] ?? ['ganado' => null, 'max' => 0]; @endphp
                    <td class="text-center">
                        @if($pp['ganado'] !== null)
                            @php $cls = $pp['ganado'] >= 70 ? 'nota-alta' : ($pp['ganado'] >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                            <span class="{{ $cls }}">{{ $pp['ganado'] }}</span>
                            @if($pp['max'] > 0)
                                <small class="text-muted">/ {{ $pp['max'] }}</small>
                            @endif
                        @elseif($pp['max'] > 0)
                            <span class="text-muted">— / {{ $pp['max'] }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center">
                        @if($item['total'] !== null)
                            @php $cls = $item['total'] >= 70 ? 'nota-alta' : ($item['total'] >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                            <strong class="{{ $cls }}">{{ $item['total'] }}</strong>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>

@endif
@stop
