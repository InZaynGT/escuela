@extends('layouts.base')

@section('title', 'Materias')

@section('content_header')
    <h1>Materias</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <div>Listado de Materias</div>
            <div>
                <a href="{{ route('admin.materias.create') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-plus"></i> Nueva Materia
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($materias->count() > 0)
                <table class="table table-bordered mb-0" id="tablaMaterias">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Materia</th>
                            <th>Grado</th>
                            <th>Sección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materias as $materia)
                        <tr>
                            <td>{{ $materia->id }}</td>
                            <td><strong>{{ $materia->nombre }}</strong></td>
                            <td>{{ $materia->gradoSeccion->grado->nombre ?? 'N/A' }}</td>
                            <td>{{ $materia->gradoSeccion->seccion->nombre ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.materias.edit', $materia->id) }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.materias.destroy', $materia->id) }}" method="POST" style="display:inline" data-confirm="¿Eliminar la materia '{{ $materia->nombre }}'?">
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
                <div class="p-3">{{ $materias->links() }}</div>
            @else
                <div class="p-3">
                    <div class="alert alert-info mb-0">No hay materias registradas. ¡Crea la primera!</div>
                </div>
            @endif
        </div>
    </div>
@stop
