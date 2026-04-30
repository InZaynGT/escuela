@extends('layouts.base')

@section('title', 'Grados')

@section('content_header')
    <h1>Grados</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Listado de Grados</div>
            <div>
                <a href="{{ route('admin.grados.create') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Grado
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($grados->count() > 0)
                <table class="table table-bordered mb-0" id="tablaGrados">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grados as $grado)
                        <tr>
                            <td>{{ $grado->id }}</td>
                            <td>{{ $grado->nombre }}</td>
                            <td>
                                <a href="{{ route('admin.grados.edit', $grado) }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.grados.destroy', $grado) }}" method="POST" style="display:inline" data-confirm="¿Eliminar este grado?">
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
                <div class="p-3">{{ $grados->links() }}</div>
            @else
                <div class="p-3">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> No hay grados registrados. ¡Crea el primero!
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop
