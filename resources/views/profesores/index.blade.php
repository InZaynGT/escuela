@extends('adminlte::page')

@section('title', 'Profesores')

@section('content')
<h2 class="text-center display-4 font-weight-bold mb-4" style="color: #4e73df; position: relative;">
    <i class="fas fa-chalkboard-teacher" style="color: #f6c23e; margin-right: 10px;"></i> 
    Lista de Profesores
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

<!-- Botón para agregar nuevo profesor -->
<div class="text-right mb-3">
    <button class="btn btn-primary" data-toggle="modal" data-target="#addProfesorModal">
        <i class="fas fa-plus-circle"></i> Agregar Profesor
    </button>
</div>

<!-- Tabla de profesores -->
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped text-center">
        <thead class="thead-dark">
            <tr>
                <th>Nombre</th>
                <th>CUI</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profesores as $profesor)
                <tr>
                    <td>{{ $profesor->nombre_completo }}</td>
                    <td>{{ $profesor->id }}</td>
                    <td>{{ $profesor->usuario }}</td>
                    <td>
                        <!-- Botón para editar Profesor -->
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editProfesorModal{{ $profesor->id }}">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </td>
                </tr>

                <!-- Modal para editar Profesor -->
                <div class="modal fade" id="editProfesorModal{{ $profesor->id }}" tabindex="-1" role="dialog" aria-labelledby="editProfesorModalLabel{{ $profesor->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProfesorModalLabel{{ $profesor->id }}">Editar Profesor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('profesores.update', $profesor->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="nombre">Nombres</label>
                                        <input type="text" name="nombres" class="form-control" value="{{ $profesor->nombre_completo }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="usuario">Usuario</label>
                                        <select name="usuario_id" class="form-control" required>
                                            <option value="">Seleccionar Usuario</option>
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}" {{ $profesor->ID_USUARIO == $usuario->id ? 'selected' : '' }}>
                                                    {{ $usuario->name }}
                                                </option>
                                            @endforeach
                                        </select>
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

<!-- Modal para agregar Profesor -->
<div class="modal fade" id="addProfesorModal" tabindex="-1" role="dialog" aria-labelledby="addProfesorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProfesorModalLabel">Agregar Nuevo Profesor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addProfesorForm" action="{{ route('profesores.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombres">Nombres</label>
                        <input type="text" name="nombres" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="CUI">CUI</label>
                        <input type="text" name="CUI" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <select name="usuario_id" id="usuarioSelect" class="form-control" required>
                            <option value="">Seleccionar Usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Profesor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lista de usuarios ya asignados en JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Lista de usuarios ya asignados
        const usuariosAsignados = @json($profesores->pluck('ID_USUARIO')->toArray());

        // Agregar un listener al formulario para agregar profesor
        const form = document.getElementById('addProfesorForm');
        form.addEventListener('submit', function (event) {
            // Obtener el usuario seleccionado en el select
            const usuarioSeleccionado = document.getElementById('usuarioSelect').value;

            // Validar si el usuario ya está asignado
            if (usuariosAsignados.includes(parseInt(usuarioSeleccionado))) {
                // Evitar el envío del formulario
                event.preventDefault();

                // Mostrar alerta
                alert('Este usuario ya está asignado a otro profesor.');
            }
        });
    });
</script>

@endsection
