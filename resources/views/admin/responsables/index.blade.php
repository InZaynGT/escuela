@extends('layouts.base')

@section('title', 'Responsables')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Responsables</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Listado de responsables</span>
        <a href="{{ route('admin.responsables.create') }}" class="btn btn-sm btn-dark">
            <i class="fas fa-plus"></i> Registrar
        </a>
    </div>
    <div class="card-body p-0">
        @if($responsables->isEmpty())
            <div class="p-3">
                <div class="alert alert-secondary mb-0">No hay responsables registrados.</div>
            </div>
        @else
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>Teléfono</th>
                    <th>Parentesco</th>
                    <th class="text-center">Estudiantes a su cargo</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($responsables as $r)
                <tr>
                    <td>{{ $r->nombre_completo }}</td>
                    <td>{{ $r->telefono ?? '—' }}</td>
                    <td>{{ $r->parentesco ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge badge-secondary">{{ $r->estudiantes_count }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.responsables.asignar', $r->id) }}"
                           class="btn btn-sm btn-secondary" title="Asignar estudiantes">
                            <i class="fas fa-user-plus"></i>
                        </a>
                        <a href="{{ route('admin.responsables.edit', $r->id) }}"
                           class="btn btn-sm btn-dark" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST"
                              action="{{ route('admin.responsables.destroy', $r->id) }}"
                              class="d-inline"
                              data-confirm="¿Eliminar a {{ $r->nombre_completo }}? Esta acción no se puede deshacer.">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $responsables->links() }}</div>
        @endif
    </div>
</div>
@stop
