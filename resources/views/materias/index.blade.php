<!-- resources/views/materias/index.blade.php -->
@extends('adminlte::page')

@section('title', 'Materias')


@section('content')
<h2 class="text-center display-4 font-weight-bold mb-4" style="color: #4e73df; position: relative;">
    <i class="fas fa-book-open" style="color: #f6c23e; margin-right: 10px;"></i> 
    Lista de Materias
    <span style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); height: 3px; width: 60px; background-color: #f6c23e;"></span>
</h2>

    <!-- Mostrar mensajes de éxito -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Botón para agregar nueva materia -->
    <div class="text-right mb-3">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addMateriaModal">
            <i class="fas fa-plus-circle"></i> Agregar Materia
        </button>
    </div>

    <!-- Tabla de materias -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped text-center">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Grado y Sección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($materias as $materia)
                    <tr>
                        <td>{{ $materia->id }}</td>
                        <td>{{ $materia->nombre }}</td>
                        <td>{{ $materia->grado }}</td>
                        <td>
                            <!-- Botón para editar materia -->
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editMateriaModal{{ $materia->id }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>

                    <!-- Modal para editar materia -->
                    <div class="modal fade" id="editMateriaModal{{ $materia->id }}" tabindex="-1" role="dialog" aria-labelledby="editMateriaModalLabel{{ $materia->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editMateriaModalLabel{{ $materia->id }}">Editar Materia</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('materias.update', $materia->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" name="nombre" class="form-control" value="{{ $materia->nombre }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

<!-- Modal para agregar materia -->
<div class="modal fade" id="addMateriaModal" tabindex="-1" role="dialog" aria-labelledby="addMateriaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMateriaModalLabel">Agregar Nueva Materia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('materias.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-body">
                    <label for="grado_seccion">Grado y Sección</label>
                    <select name="grado_id" class="form-control" required>
                        <option value="">Seleccionar Grado</option>
                        @foreach($grados as $grado)
                            <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Materia</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
