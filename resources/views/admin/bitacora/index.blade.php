@extends('adminlte::page')

@section('title', 'Bitácora del sistema')

@section('content_header')
    <h1>Bitácora del sistema</h1>
@endsection

@section('adminlte_css')
<style>
.changes-cell { font-size: 0.78rem; max-width: 320px; }
.changes-cell .field-name { color: #546e7a; font-weight: 600; }
.changes-cell .arrow { color: #aaa; }
.changes-cell .old-val { color: #c62828; text-decoration: line-through; }
.changes-cell .new-val { color: #2e7d32; }
.badge-created  { background-color: #43a047; }
.badge-updated  { background-color: #fb8c00; color: #1a1a1a; }
.badge-deleted  { background-color: #e53935; }
.badge-acceso   { background-color: #039be5; }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span><i class="fas fa-history mr-1"></i> Filtros</span>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bitacora.index') }}" class="form-row align-items-end">
            <div class="form-group col-md-2 mb-2">
                <label>Módulo</label>
                <select name="modulo" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach($modulos as $m)
                        <option value="{{ $m }}" {{ request('modulo') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Evento</label>
                <select name="evento" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <option value="created"  {{ request('evento') == 'created'  ? 'selected' : '' }}>Creación</option>
                    <option value="updated"  {{ request('evento') == 'updated'  ? 'selected' : '' }}>Edición</option>
                    <option value="deleted"  {{ request('evento') == 'deleted'  ? 'selected' : '' }}>Eliminación</option>
                </select>
            </div>
            <div class="form-group col-md-3 mb-2">
                <label>Usuario</label>
                <select name="id_usuario" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ request('id_usuario') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}" class="form-control form-control-sm">
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control form-control-sm">
            </div>
            <div class="form-group col-md-1 mb-2">
                <label>&nbsp;</label>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary btn-block">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('admin.bitacora.index') }}" class="btn btn-sm btn-secondary btn-block">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list mr-1"></i> Registros</span>
        <small class="text-muted">{{ $registros->total() }} entradas</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="width:135px">Fecha y hora</th>
                        <th style="width:160px">Usuario</th>
                        <th style="width:110px">Módulo</th>
                        <th style="width:85px">Evento</th>
                        <th>Cambios</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $log)
                    @php
                        $evento    = $log->event ?? 'default';
                        $props     = $log->properties;
                        $attrs     = $props->get('attributes', []);
                        $old       = $props->get('old', []);
                        $badgeClass = match($evento) {
                            'created' => 'badge-created',
                            'updated' => 'badge-updated',
                            'deleted' => 'badge-deleted',
                            default   => 'badge-acceso',
                        };
                        $eventoLabel = match($evento) {
                            'created' => 'Creación',
                            'updated' => 'Edición',
                            'deleted' => 'Eliminación',
                            default   => 'Acceso',
                        };
                    @endphp
                    <tr>
                        <td class="text-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @if($log->causer)
                                <span class="font-weight-bold">{{ $log->causer->name }}</span><br>
                                <small class="text-muted">{{ $log->causer->rol }}</small>
                            @else
                                <span class="text-muted">Sistema</span>
                            @endif
                        </td>
                        <td><span class="badge badge-secondary">{{ $log->log_name }}</span></td>
                        <td><span class="badge text-white {{ $badgeClass }}">{{ $eventoLabel }}</span></td>
                        <td class="changes-cell">
                            @if($evento === 'created')
                                @foreach($attrs as $campo => $valor)
                                    <div>
                                        <span class="field-name">{{ $campo }}:</span>
                                        <span class="new-val">{{ $valor ?? '—' }}</span>
                                    </div>
                                @endforeach
                            @elseif($evento === 'updated')
                                @foreach($attrs as $campo => $nuevoValor)
                                    <div>
                                        <span class="field-name">{{ $campo }}:</span>
                                        <span class="old-val">{{ $old[$campo] ?? '?' }}</span>
                                        <span class="arrow">→</span>
                                        <span class="new-val">{{ $nuevoValor ?? '—' }}</span>
                                    </div>
                                @endforeach
                            @elseif($evento === 'deleted')
                                @foreach($old as $campo => $valor)
                                    <div>
                                        <span class="field-name">{{ $campo }}:</span>
                                        <span class="old-val">{{ $valor ?? '—' }}</span>
                                    </div>
                                @endforeach
                            @else
                                {{-- Evento manual (login, etc.) --}}
                                <span class="text-muted">{{ $log->description }}</span>
                                @if($props->has('ip'))
                                    <br><small class="text-muted">IP: {{ $props->get('ip') }}</small>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">No hay registros con los filtros aplicados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($registros->hasPages())
    <div class="card-footer">
        {{ $registros->links() }}
    </div>
    @endif
</div>
@endsection
