<!-- resources/views/grados/index.blade.php -->
@extends('adminlte::page')

@section('title', 'Grados')


@section('content')
<h2 class="text-center display-4 font-weight-bold mb-4" style="color: #4e73df; position: relative;">
    <i class="fa fa-school" style="color: #f6c23e; margin-right: 10px;"></i> 
    Lista de Grados con Secciones
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
            <i class="fas fa-plus-circle"></i> Asociar Grado con Sección
        </button>
    </div>

    <!-- Tabla de grados -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped text-center">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gradoSecciones as $Grado)
                    <tr>
                        <td>{{ $Grado->id }}</td>
                        <td>{{ $Grado->grado_seccion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<!-- Modal para agregar Grado -->
<div class="modal fade" id="addGradoModal" tabindex="-1" role="dialog" aria-labelledby="addGradoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGradoModalLabel">Asociar nuevo Grado con Sección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('asocia.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="grado">Grado</label>
                        <select name="grado_id" class="form-control" required>
                            <option value="">Seleccionar Grado</option>
                            @foreach($grados as $grado)
                                <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                
                    <div class="form-group">
                        <label for="seccion">Sección</label>
                        <select name="seccion_id" class="form-control" required>
                            <option value="">Seleccionar Sección</option>
                            @foreach($seccion as $seccion)
                                <option value="{{ $seccion->id }}">{{ $seccion->nombre }}</option>
                            @endforeach
                        </select>
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
