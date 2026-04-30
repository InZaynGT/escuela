<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asistencia — {{ $seleccionado->grado->nombre ?? '' }} {{ $seleccionado->seccion->nombre ?? '' }} {{ $anio }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

.toolbar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    background: #1a1a2e; color: #fff;
    padding: 10px 20px;
    display: flex; justify-content: space-between; align-items: center;
    font-family: Arial, sans-serif; font-size: 13px;
}
.toolbar span { opacity: .85; }
.toolbar .acciones { display: flex; gap: 8px; }
.toolbar button, .toolbar a {
    padding: 6px 14px; border-radius: 4px; font-size: 12px;
    cursor: pointer; text-decoration: none; border: none;
}
.btn-print { background: #fff; color: #1a1a2e; font-weight: bold; }
.btn-close  { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.4) !important; }

body {
    background: #e0e0e0;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10.5pt;
    padding-top: 52px;
}
.pagina {
    width: 21.59cm;
    min-height: 27.94cm;
    margin: 24px auto;
    background: #fff;
    padding: 2cm 2.5cm;
    box-shadow: 0 2px 12px rgba(0,0,0,.25);
    color: #000;
}

.encabezado {
    text-align: center; border-bottom: 2.5px solid #000;
    padding-bottom: 8px; margin-bottom: 10px;
}
.encabezado .mineduc { font-size: 8pt; letter-spacing: .4px; text-transform: uppercase; color: #555; }
.encabezado .titulo  { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 4px 0 2px; }
.encabezado .escuela { font-size: 10.5pt; font-weight: bold; }
.encabezado .anio    { font-size: 9pt; color: #555; margin-top: 3px; }

.datos { margin: 10px 0; }
.datos-fila { display: flex; gap: 16px; margin-bottom: 5px; }
.datos-campo { display: flex; align-items: baseline; gap: 6px; }
.datos-campo.col-8 { flex: 8; }
.datos-campo.col-4 { flex: 4; }
.datos-campo .lbl { font-weight: bold; white-space: nowrap; font-size: 10pt; }
.datos-campo .val { flex: 1; border-bottom: 1px solid #777; font-size: 10pt; padding-bottom: 1px; }

.tabla-asistencia { width: 100%; border-collapse: collapse; margin-top: 12px; table-layout: fixed; }
.tabla-asistencia th {
    background: #1a1a2e; color: #fff; padding: 5px 3px; text-align: center;
    font-size: 8.5pt; text-transform: uppercase; border: 1px solid #333; word-wrap: break-word;
}
.tabla-asistencia th.col-nombre { text-align: left; padding-left: 6px; }
.tabla-asistencia td { border: 1px solid #ccc; padding: 4px 3px; font-size: 10pt; vertical-align: middle; text-align: center; }
.tabla-asistencia td.col-nombre { text-align: left; padding-left: 6px; }
.tabla-asistencia tr:nth-child(even) td { background: #f8f8f8; }
.tabla-asistencia tr.fila-total td { background: #eeeeee; font-weight: bold; border-top: 2px solid #555; }

.nota-alta  { color: #1a6b1a; font-weight: bold; }
.nota-media { color: #8a6000; font-weight: bold; }
.nota-baja  { color: #8b0000; font-weight: bold; }

.leyenda { margin-top: 7px; font-size: 8pt; color: #666; }

.firmas { margin-top: 36px; width: 100%; border-collapse: collapse; }
.firmas td { text-align: center; padding: 0 16px; vertical-align: bottom; width: 50%; }
.linea { border-top: 1px solid #000; padding-top: 4px; font-size: 8.5pt; }
.cargo { font-weight: bold; font-size: 8.5pt; }
.sub   { font-size: 8pt; color: #666; }

.pie { margin-top: 12px; border-top: 1px solid #ddd; padding-top: 5px; font-size: 8pt; color: #999; text-align: center; }

@media print {
    .toolbar { display: none !important; }
    body { background: white; padding-top: 0; }
    .pagina { width: 100%; min-height: 100%; margin: 0; padding: 2cm 2.5cm; box-shadow: none; }
    @page { size: letter portrait; margin: 0; }
}
</style>
</head>
<body>

<div class="toolbar">
    <span>
        Reporte de Asistencia &mdash;
        {{ $seleccionado->grado->nombre ?? '' }} {{ $seleccionado->seccion->nombre ?? '' }}
        &mdash; {{ $anio }}
    </span>
    <div class="acciones">
        <button class="btn-print" onclick="window.print()">&#128438; Imprimir / Guardar PDF</button>
        <a class="btn-close" href="javascript:history.back()">&#10005; Cerrar</a>
    </div>
</div>

@php
    $totalGeneral     = $resumen->sum('total');
    $presentesGeneral = $resumen->sum('presentes');
    $ausentesGeneral  = $resumen->sum('ausentes');
    $tardanzasGeneral = $resumen->sum('tardanzas');
    $pctGeneral       = $totalGeneral > 0 ? round(($presentesGeneral / $totalGeneral) * 100, 1) : null;
@endphp

<div class="pagina">

    <div class="encabezado">
        <div class="mineduc">República de Guatemala &mdash; Ministerio de Educación</div>
        <div class="titulo">Reporte de Asistencia</div>
        <div class="escuela">Escuela Oficial Rural Mixta</div>
        <div class="anio">Año Lectivo {{ $anio }}</div>
    </div>

    <div class="datos">
        <div class="datos-fila">
            <div class="datos-campo col-8">
                <span class="lbl">Grado / Sección:</span>
                <span class="val">{{ strtoupper($seleccionado->grado->nombre ?? '') }} {{ strtoupper($seleccionado->seccion->nombre ?? '') }}</span>
            </div>
            <div class="datos-campo col-4">
                <span class="lbl">Ciclo Escolar:</span>
                <span class="val">{{ $anio }}</span>
            </div>
        </div>
        <div class="datos-fila">
            <div class="datos-campo col-8">
                <span class="lbl">Total de alumnos:</span>
                <span class="val">{{ $resumen->count() }}</span>
            </div>
            <div class="datos-campo col-4">
                <span class="lbl">Fecha de emisión:</span>
                <span class="val">{{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
            </div>
        </div>
    </div>

    <table class="tabla-asistencia">
        <colgroup>
            <col style="width:5%">
            <col style="width:41%">
            <col style="width:12%">
            <col style="width:12%">
            <col style="width:12%">
            <col style="width:10%">
            <col style="width:8%">
        </colgroup>
        <thead>
            <tr>
                <th>#</th>
                <th class="col-nombre">Estudiante</th>
                <th>Presentes</th>
                <th>Ausentes</th>
                <th>Tardanzas</th>
                <th>Total</th>
                <th>% Asist.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumen as $i => $fila)
            @php
                $pct = $fila['porcentaje'];
                $cls = $pct === null ? '' : ($pct >= 80 ? 'nota-alta' : ($pct >= 60 ? 'nota-media' : 'nota-baja'));
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="col-nombre">{{ $fila['est']->nombre_completo }}</td>
                <td><span class="nota-alta">{{ $fila['presentes'] }}</span></td>
                <td><span class="nota-baja">{{ $fila['ausentes'] }}</span></td>
                <td><span class="nota-media">{{ $fila['tardanzas'] }}</span></td>
                <td>{{ $fila['total'] }}</td>
                <td>
                    @if($pct !== null)
                        <span class="{{ $cls }}">{{ $pct }}%</span>
                    @else
                        <span style="color:#bbb">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="fila-total">
                <td></td>
                <td class="col-nombre" style="font-size:8.5pt;text-transform:uppercase;">Total general</td>
                <td><span class="nota-alta">{{ $presentesGeneral }}</span></td>
                <td><span class="nota-baja">{{ $ausentesGeneral }}</span></td>
                <td><span class="nota-media">{{ $tardanzasGeneral }}</span></td>
                <td>{{ $totalGeneral }}</td>
                <td>
                    @if($pctGeneral !== null)
                        @php $clsG = $pctGeneral >= 80 ? 'nota-alta' : ($pctGeneral >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                        <span class="{{ $clsG }}">{{ $pctGeneral }}%</span>
                    @else
                        <span style="color:#bbb">—</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="leyenda">
        % Asistencia = Presentes / Total de registros &nbsp;|&nbsp;
        <span class="nota-alta">Verde</span> &ge; 80% &nbsp;
        <span class="nota-media">Amarillo</span> &ge; 60% &nbsp;
        <span class="nota-baja">Rojo</span> &lt; 60%
    </div>

    <table class="firmas">
        <tr>
            <td>
                <div style="height:28px"></div>
                <div class="linea"></div>
                <div class="cargo">Director(a)</div>
                <div class="sub">Firma y Sello</div>
            </td>
            <td>
                <div style="height:28px"></div>
                <div class="linea"></div>
                <div class="cargo">Maestro(a) de Grado</div>
                <div class="sub">Firma</div>
            </td>
        </tr>
    </table>

    <div class="pie">
        Generado el {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }} &mdash; Sistema de Gestión Escolar EORM
    </div>

</div>
</body>
</html>
