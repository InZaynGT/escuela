@extends('layouts.base')

@section('title', 'Grado-Sección')

@section('content_header')
    <h1>Combinaciones Grado-Sección</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Listado de Combinaciones</div>
            <div>
                <a href="{{ route('admin.grado-seccion.create') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nueva Combinación
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($combinaciones->count() > 0)
                <table class="table table-bordered mb-0" id="tablaGradoSeccion">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Grado</th>
                            <th>Sección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($combinaciones as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->grado->nombre }}</td>
                            <td>{{ $item->seccion->nombre }}</td>
                            <td>
                                <form action="{{ route('admin.grado-seccion.destroy', $item->id) }}" method="POST" style="display:inline" data-confirm="¿Eliminar la combinación {{ $item->grado->nombre }} — {{ $item->seccion->nombre }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3">{{ $combinaciones->links() }}</div>
            @else
                <div class="p-3">
                    <div class="alert alert-info mb-0">No hay combinaciones registradas. ¡Crea la primera!</div>
                </div>
            @endif
        </div>
    </div>
@stop
