<!-- resources/views/listadoEstudiantes.blade.php -->
@extends('adminlte::page')

@section('title', 'Listado de Estudiantes')

@section('content')
<h2 class="text-center display-4 font-weight-bold mb-4" style="color: #4e73df; position: relative;">
    <i class="fas fa-user-graduate" style="color: #f6c23e; margin-right: 10px;"></i> 
    Estudiantes de {{ $gradoSeccion->grado_seccion }}
    <span style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); height: 3px; width: 60px; background-color: #f6c23e;"></span>
</h2>

<!-- Botón para retornar a la vista de estudiantes -->
<div class="mb-4">
    <a href="{{ route('estudiantes') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Regresar a Estudiantes
    </a>
</div>

<!-- Tabla de estudiantes -->
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped text-center">
        <thead class="thead-dark">
            <tr>
                <th>No. de Carné</th>
                <th>Nombre</th>
                <th>CUI</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiantes as $estudiante)
            <tr>
                <td>{{ $estudiante->carne }}</td>
                <td>{{ $estudiante->nombre }}</td>
                <td>{{ $estudiante->CUI }}</td>
                <td>
                    <!-- Botón para editar -->
                    <button class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
