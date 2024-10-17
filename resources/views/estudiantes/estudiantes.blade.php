<!-- resources/views/estudiantes.blade.php -->
@extends('adminlte::page')

@section('title', 'Grados y Secciones')

@section('content')
<h2 class="text-center display-4 font-weight-bold mb-4" style="color: #4e73df; position: relative;">
    <i class="fas fa-layer-group" style="color: #f6c23e; margin-right: 10px;"></i> 
    Grados y Secciones
    <span style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); height: 3px; width: 60px; background-color: #f6c23e;"></span>
</h2>

<div class="container-fluid">
    @foreach($gradoSecciones->groupBy('grado_nombre') as $gradoNombre => $secciones)
        <div class="mt-4 mb-3">
            <h3 class="font-weight-bold" style="color: #4e73df;">{{ $gradoNombre }}</h3>
            <div class="list-group">
                @foreach($secciones as $gradoSeccion)
                    <a href="{{ route('listadoEstudiantes', $gradoSeccion->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $gradoSeccion->grado_seccion }}</h5>
                            <p class="mb-1">Estudiantes: <strong>{{ $gradoSeccion->total_estudiantes }}</strong></p>
                        </div>
                        <span class="badge badge-primary badge-pill">Ver Estudiantes</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

@endsection
