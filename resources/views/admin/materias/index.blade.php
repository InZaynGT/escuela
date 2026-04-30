@extends('layouts.base')

@section('title', 'Materias')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Materias</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Panel
        </a>
    </div>
@stop

@section('content')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.materias.create') }}" class="btn btn-dark btn-sm">
        <i class="fas fa-plus"></i> Nueva Materia
    </a>
</div>

@if($grados->isEmpty())
    <div class="alert alert-secondary">No hay grados registrados.</div>
@else

<div id="acordeonGrados">
    @foreach($grados as $grado)
    @php $primerGrado = $loop->first; @endphp

    <div class="card mb-2">

        {{-- Cabecera del grado --}}
        <div class="card-header p-0">
            <button class="btn btn-block text-left d-flex justify-content-between align-items-center px-3 py-2"
                    style="font-weight:600;font-size:.95rem;background:transparent;border:none;"
                    type="button"
                    data-toggle="collapse"
                    data-target="#collapse-grado-{{ $grado->id }}"
                    aria-expanded="{{ $primerGrado ? 'true' : 'false' }}">
                <span>
                    <i class="fas fa-graduation-cap mr-2" style="color:#546e7a;"></i>
                    {{ $grado->nombre }}
                    <small class="text-muted font-weight-normal ml-2">
                        {{ $grado->gradoSecciones->count() }} sección(es)
                        · {{ $grado->gradoSecciones->sum(fn($gs) => $gs->materias->count()) }} materias
                    </small>
                </span>
                <i class="fas fa-chevron-down" style="font-size:.75rem;color:#90a4ae;"></i>
            </button>
        </div>

        {{-- Panel colapsable --}}
        <div id="collapse-grado-{{ $grado->id }}"
             class="collapse">
            <div class="card-body p-0">

                @if($grado->gradoSecciones->isEmpty())
                    <div class="p-3">
                        <div class="alert alert-secondary mb-0">
                            No hay secciones asignadas a este grado.
                        </div>
                    </div>
                @else

                {{-- Tabs de secciones --}}
                <ul class="nav nav-tabs px-3 pt-2" id="tabs-grado-{{ $grado->id }}" role="tablist">
                    @foreach($grado->gradoSecciones->sortBy('seccion.nombre') as $gs)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                           id="tab-link-{{ $gs->id }}"
                           data-toggle="tab"
                           href="#tab-pane-{{ $gs->id }}"
                           role="tab">
                            Sección {{ $gs->seccion->nombre ?? '—' }}
                            <span class="badge badge-secondary ml-1">{{ $gs->materias->count() }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>

                {{-- Contenido de cada tab --}}
                <div class="tab-content px-3 pb-3 pt-2">
                    @foreach($grado->gradoSecciones->sortBy('seccion.nombre') as $gs)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                         id="tab-pane-{{ $gs->id }}"
                         role="tabpanel">

                        @if($gs->materias->isEmpty())
                            <div class="alert alert-secondary mb-2">
                                No hay materias registradas para esta sección.
                            </div>
                        @else
                        <table class="table table-bordered table-hover table-sm mb-2">
                            <thead class="thead-light">
                                <tr>
                                    <th>Materia</th>
                                    <th style="width:130px;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gs->materias->sortBy('nombre') as $materia)
                                <tr>
                                    <td style="vertical-align:middle;">{{ $materia->nombre }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.materias.edit', $materia->id) }}"
                                           class="btn btn-dark btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.materias.destroy', $materia->id) }}"
                                              method="POST" style="display:inline"
                                              data-confirm="¿Eliminar '{{ $materia->nombre }}'?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                        <a href="{{ route('admin.materias.create', ['id_grado_seccion' => $gs->id]) }}"
                           class="btn btn-sm btn-secondary">
                            <i class="fas fa-plus"></i> Agregar materia
                        </a>

                    </div>
                    @endforeach
                </div>

                @endif
            </div>
        </div>
    </div>

    @endforeach
</div>

@endif

@stop
