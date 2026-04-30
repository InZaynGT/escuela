@extends('layouts.base')

@section('title', 'Cuadro de Notas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Cuadro de Notas
            <small>{{ $materia->nombre }} — {{ $materia->gradoSeccion->grado->nombre ?? '' }} {{ $materia->gradoSeccion->seccion->nombre ?? '' }}</small>
        </h1>
        <a href="{{ route('teacher.materias.show', $materia->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')

{{-- Selector de Unidad (siempre visible) --}}
<div class="card mb-3 d-none">
    <div class="card-header">Seleccionar unidad</div>
    <div class="card-body">
        <div class="d-flex align-items-center flex-wrap" style="gap:.5rem;">
            <select id="selectPeriodo" class="form-control form-control-sm" style="max-width:280px;">
                <option value="">— Seleccionar unidad —</option>
                @foreach($periodos as $p)
                <option value="{{ route('teacher.calificaciones.index', ['idMateria' => $materia->id, 'id_periodo' => $p->id]) }}"
                        {{ isset($periodo) && $periodo->id === $p->id ? 'selected' : '' }}>
                    {{ $p->nombre }} ({{ $p->anio }})
                </option>
                @endforeach
            </select>
            <a href="{{ route('teacher.tareas.index', $materia->id) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Tareas
            </a>
        </div>
    </div>
</div>

@if(!isset($periodo))
    <div class="alert alert-secondary">
        Selecciona una unidad arriba para ver o ingresar calificaciones.
    </div>
@else

{{-- Cuadro de notas del Unidad seleccionado --}}
@if($periodo->bloqueado)
<div class="alert alert-danger mb-3">
    <i class="fas fa-lock mr-1"></i>
    <strong>Período bloqueado.</strong>
    El período <strong>{{ $periodo->nombre }}</strong> está cerrado. Las calificaciones son de solo lectura.
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-start flex-wrap" style="gap:.5rem;">
        <div>
            <div style="font-size:1rem;font-weight:600;color:#263238;">
                {{ $materia->nombre }} — {{ $periodo->nombre }}
                @if($periodo->bloqueado)
                    <span class="badge badge-danger ml-1"><i class="fas fa-lock"></i> Bloqueado</span>
                @endif
            </div>
            <div style="font-size:.82rem;color:#78909c;margin-top:.15rem;">
                {{ $materia->gradoSeccion->grado->nombre ?? '' }}
                {{ $materia->gradoSeccion->seccion->nombre ?? '' }}
                &nbsp;·&nbsp;
                Prof. {{ $profesor->nombre }} {{ $profesor->apellidos }}
            </div>
        </div>
        <div class="d-flex flex-wrap" style="gap:.4rem;">
            @if(!$periodo->bloqueado)
            <button type="button" id="btnGuardar" class="btn btn-sm btn-dark">
                <i class="fas fa-save"></i> Guardar notas
            </button>
            @endif
            <button type="button" id="btnImprimir" class="btn btn-sm btn-secondary">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <form id="formCalificaciones">
                @csrf
                <input type="hidden" name="id_periodo" value="{{ $periodo->id }}">
                @php $maxTotal = $tareas->sum('ponderacion'); @endphp
                <table class="table table-bordered mb-0" id="tablaNotas">
                    <thead>
                        <tr>
                            <th style="width:2rem;text-align:center;">#</th>
                            <th style="min-width:170px;">Estudiante</th>
                            @foreach($tareas as $tarea)
                            <th class="text-center" style="min-width:115px;">
                                {{ $tarea->titulo }}
                                <div style="font-size:.72rem;font-weight:400;color:#90a4ae;">
                                    {{ $tarea->ponderacion }} pts
                                </div>
                            </th>
                            @endforeach
                            <th class="text-center" style="min-width:80px;">
                                Total
                                <div style="font-size:.72rem;font-weight:400;color:#90a4ae;">
                                    máx {{ number_format($maxTotal, 0) }}
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantes as $i => $estudiante)
                        <tr>
                            <td class="text-center text-muted" style="font-size:.78rem;">{{ $i + 1 }}</td>
                            <td style="vertical-align:middle;">
                                {{ $estudiante->apellidos }}, {{ $estudiante->nombre }}
                            </td>

                            @foreach($tareas as $tarea)
                            @php
                                $cal       = $calificaciones[$estudiante->id][$tarea->id] ?? null;
                                $noEntrego = $cal && $cal->entrego == 0;
                                $nota      = $cal ? $cal->calificacion : '';
                                $obs       = $cal ? ($cal->observaciones ?? '') : '';
                            @endphp
                            <td class="text-center p-1" style="vertical-align:top;">
                                <input type="hidden"
                                       name="notas[{{ $estudiante->id }}][{{ $tarea->id }}][entrego]"
                                       class="input-entrego"
                                       value="{{ $noEntrego ? '0' : '1' }}">
                                <input type="number"
                                       name="notas[{{ $estudiante->id }}][{{ $tarea->id }}][calificacion]"
                                       class="form-control form-control-sm text-center nota-input"
                                       value="{{ $nota }}"
                                       data-est="{{ $estudiante->id }}"
                                       data-pond="{{ $tarea->ponderacion }}"
                                       min="0" max="{{ $tarea->ponderacion }}" step="0.01"
                                       placeholder="—"
                                       {{ $noEntrego || $periodo->bloqueado ? 'readonly' : '' }}
                                       style="width:72px;margin:0 auto;{{ $noEntrego || $periodo->bloqueado ? 'background:#f5f5f5;color:#9e9e9e;' : '' }}">
                                @if(!$periodo->bloqueado)
                                <label class="mt-1 mb-0 d-flex align-items-center justify-content-center"
                                       style="font-size:.7rem;cursor:pointer;gap:.25rem;color:#78909c;">
                                    <input type="checkbox" class="chk-no-entrego" {{ $noEntrego ? 'checked' : '' }}>
                                    No entregó
                                </label>
                                @elseif($noEntrego)
                                <div style="font-size:.7rem;color:#9e9e9e;margin-top:.15rem;">No entregó</div>
                                @endif
                                <input type="text"
                                       name="notas[{{ $estudiante->id }}][{{ $tarea->id }}][observaciones]"
                                       class="form-control form-control-sm mt-1"
                                       value="{{ $obs }}"
                                       placeholder="Observaciones"
                                       {{ $periodo->bloqueado ? 'readonly' : '' }}
                                       style="font-size:.7rem;padding:.15rem .4rem;{{ $periodo->bloqueado ? 'background:#f5f5f5;' : '' }}">
                            </td>
                            @endforeach

                            <td class="text-center" id="total-{{ $estudiante->id }}"
                                style="vertical-align:middle;font-weight:600;font-size:.9rem;">—</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    @if(!$periodo->bloqueado)
    <div class="card-footer text-right">
        <button type="button" id="btnGuardarFooter" class="btn btn-dark btn-sm">
            <i class="fas fa-save"></i> Guardar notas
        </button>
    </div>
    @endif
</div>

{{-- Tabla oculta para impresión --}}
<div id="areaPrint" style="display:none;">
    <div style="text-align:center;margin-bottom:.75rem;">
        <strong style="font-size:1rem;">CUADRO GENERAL DE NOTAS</strong><br>
        <span>{{ $materia->nombre }} — {{ $periodo->nombre }} ({{ $periodo->anio }})</span><br>
        <span>{{ $materia->gradoSeccion->grado->nombre ?? '' }} {{ $materia->gradoSeccion->seccion->nombre ?? '' }}</span><br>
        <small style="color:#555;">Prof. {{ $profesor->nombre }} {{ $profesor->apellidos }} &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i') }}</small>
    </div>
    <table id="tablaPrint" style="width:100%;border-collapse:collapse;font-size:.78rem;font-family:sans-serif;">
        <thead style="background:#eceff1;">
            <tr>
                <th style="border:1px solid #ccc;padding:4px;text-align:center;width:28px;">#</th>
                <th style="border:1px solid #ccc;padding:4px;">Estudiante</th>
                @foreach($tareas as $tarea)
                <th style="border:1px solid #ccc;padding:4px;text-align:center;">
                    {{ $tarea->titulo }}<br>
                    <span style="font-weight:normal;font-size:.68rem;">{{ $tarea->ponderacion }} pts</span>
                </th>
                @endforeach
                <th style="border:1px solid #ccc;padding:4px;text-align:center;">
                    Total<br><span style="font-weight:normal;font-size:.68rem;">máx {{ $maxTotal }}</span>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($estudiantes as $i => $estudiante)
            <tr data-est="{{ $estudiante->id }}">
                <td style="border:1px solid #ccc;padding:4px;text-align:center;">{{ $i + 1 }}</td>
                <td style="border:1px solid #ccc;padding:4px;">{{ $estudiante->apellidos }}, {{ $estudiante->nombre }}</td>
                @foreach($tareas as $tarea)
                <td class="print-nota" style="border:1px solid #ccc;padding:4px;text-align:center;">—</td>
                @endforeach
                <td class="print-prom" style="border:1px solid #ccc;padding:4px;text-align:center;font-weight:600;">—</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif

@stop

@section('js')
<script>
document.getElementById('selectPeriodo').addEventListener('change', function () {
    if (this.value) window.location.href = this.value;
});
</script>
@if(isset($periodo))
<script>
$(document).ready(function () {
    var maxTotal = {{ $maxTotal }};

    function calcularTotal(estId) {
        var suma = 0, hayAlguna = false;
        $('.nota-input[data-est="' + estId + '"]').each(function () {
            var v = parseFloat($(this).val());
            if (!isNaN(v)) { suma += v; hayAlguna = true; }
        });
        var $cell = $('#total-' + estId);
        if (!hayAlguna) { $cell.text('—').css('color', ''); return; }
        var color = suma >= maxTotal * 0.61 ? '#2e7d32' : (suma >= maxTotal * 0.5 ? '#e65100' : '#b71c1c');
        $cell.text(suma.toFixed(1)).css('color', color);
    }

    $('.nota-input').each(function () { calcularTotal($(this).data('est')); });
    $(document).on('input', '.nota-input', function () { calcularTotal($(this).data('est')); });

    $(document).on('change', '.chk-no-entrego', function () {
        var $td = $(this).closest('td');
        var $nota = $td.find('.nota-input'), $entrego = $td.find('.input-entrego');
        if ($(this).is(':checked')) {
            $nota.val('0').prop('readonly', true).css({ background: '#f5f5f5', color: '#9e9e9e' });
            $entrego.val('0');
        } else {
            $nota.val('').prop('readonly', false).css({ background: '', color: '' });
            $entrego.val('1');
        }
        calcularTotal($nota.data('est'));
    });

    function guardarNotas() {
        $.ajax({
            url: '{{ route("teacher.calificaciones.store", $materia->id) }}',
            type: 'POST',
            data: $('#formCalificaciones').serialize(),
            success: function (res) {
                Swal.fire({ icon: 'success', title: '¡Guardado!', text: res.success,
                    timer: 2200, timerProgressBar: true, showConfirmButton: false });
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron guardar las notas.',
                    confirmButtonColor: '#37474f' });
            }
        });
    }

    $('#btnGuardar, #btnGuardarFooter').on('click', guardarNotas);
    $(document).on('keydown', '.nota-input', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); guardarNotas(); }
    });

    $('#btnImprimir').on('click', function () {
        $('#tablaNotas tbody tr').each(function () {
            var estId = $(this).find('.nota-input').first().data('est');
            var notas = [];
            $(this).find('.nota-input').each(function () {
                var v = $(this).val();
                notas.push(v !== '' ? parseFloat(v).toFixed(1) : '—');
            });
            var $fila = $('#tablaPrint tbody tr[data-est="' + estId + '"]');
            $fila.find('.print-nota').each(function (j) { $(this).text(notas[j]); });
            $fila.find('.print-prom').text($('#total-' + estId).text());
        });
        var ventana = window.open('', '_blank', 'width=960,height=680');
        ventana.document.write(
            '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Cuadro de notas</title>' +
            '<style>body{font-family:sans-serif;padding:1.2rem;}table{width:100%;border-collapse:collapse;font-size:.78rem;}' +
            'th,td{border:1px solid #ccc;padding:4px;}thead{background:#eceff1;}' +
            '.btn-p{margin-top:1rem;padding:.4rem 1.2rem;background:#263238;color:#fff;border:none;cursor:pointer;border-radius:3px;font-size:.82rem;}' +
            '@media print{.no-print{display:none!important}}</style></head><body>' +
            document.getElementById('areaPrint').innerHTML +
            '<div class="no-print" style="text-align:center;"><button class="btn-p" onclick="window.print()">Imprimir</button></div>' +
            '</body></html>'
        );
        ventana.document.close();
    });
});
</script>
@endif
@endsection
