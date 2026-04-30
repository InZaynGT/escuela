@extends('layouts.base')

@section('title', 'Secciones')

@section('content_header')
    <h1>Secciones</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Listado de Secciones</div>
            <div>
                <a href="{{ route('admin.secciones.create') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nueva Sección
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($secciones->count() > 0)
                <table class="table table-bordered mb-0" id="tablaSecciones">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($secciones as $seccion)
                        <tr>
                            <td>{{ $seccion->id }}</td>
                            <td>{{ $seccion->nombre }}</td>
                            <td>
                                <a href="{{ route('admin.secciones.edit', $seccion) }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.secciones.destroy', $seccion) }}" method="POST" style="display:inline" data-confirm="¿Eliminar esta sección?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3">{{ $secciones->links() }}</div>
            @else
                <div class="p-3">
                    <div class="alert alert-info mb-0">No hay secciones registradas. ¡Crea la primera!</div>
                </div>
            @endif
        </div>
    </div>
@stop
