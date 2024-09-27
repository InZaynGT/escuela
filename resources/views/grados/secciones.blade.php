<!-- resources/views/grados/index.blade.php -->
@extends('adminlte::page')

@section('title', 'Secciones')


@section('content')
<h2 class="text-center display-4 font-weight-bold mb-4" style="color: #4e73df; position: relative;">
    <i class="fa fa-graduation-cap" style="color: #f6c23e; margin-right: 10px;"></i> 
    Lista de Secciones
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

    <!-- Botón para agregar nuevo grado -->
    <div class="text-right mb-3">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addGradoModal">
            <i class="fas fa-plus-circle"></i> Agregar Grado
        </button>
    </div>

    <!-- Tabla de secciones -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped text-center">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($secciones as $Grado)
                    <tr>
                        <td>{{ $Grado->id }}</td>
                        <td>{{ $Grado->nombre }}</td>
                        <td>
                            <!-- Botón para editar Grado -->
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editGradoModal{{ $Grado->id }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>

                            {{-- <!-- Botón para eliminar Grado -->
                            <form action="{{ route('grado.destroy', $Grado->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta Grado?');">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </form> --}}
                        </td>
                    </tr>

                    <!-- Modal para editar Grado -->
                    <div class="modal fade" id="editGradoModal{{ $Grado->id }}" tabindex="-1" role="dialog" aria-labelledby="editGradoModalLabel{{ $Grado->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editGradoModalLabel{{ $Grado->id }}">Editar Grado</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('secciones.update', $Grado->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" name="nombre" class="form-control" value="{{ $Grado->nombre }}" required>
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

<!-- Modal para agregar Grado -->
<div class="modal fade" id="addGradoModal" tabindex="-1" role="dialog" aria-labelledby="addGradoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGradoModalLabel">Agregar Nueva Grado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('secciones.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Grado</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
