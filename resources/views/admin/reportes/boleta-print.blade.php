<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Boleta — {{ $seleccionado->nombre_completo }}</title>
<style>
/* ── Reset ── */
* { margin: 0; padding: 0; box-sizing: border-box; }

/* ── Barra de herramientas (solo pantalla) ── */
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

/* ── Área de página (pantalla) ── */
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

/* ── Encabezado ── */
.encabezado {
    text-align: center;
    border-bottom: 2.5px solid #000;
    padding-bottom: 8px;
    margin-bottom: 10px;
}
.encabezado .mineduc { font-size: 8pt; letter-spacing: .4px; text-transform: uppercase; color: #555; }
.encabezado .titulo  { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 4px 0 2px; }
.encabezado .escuela { font-size: 10.5pt; font-weight: bold; }
.encabezado .anio    { font-size: 9pt; color: #555; margin-top: 3px; }

/* ── Datos del alumno ── */
.datos { margin: 10px 0; }
.datos-fila { display: flex; gap: 16px; margin-bottom: 5px; }
.datos-campo { display: flex; align-items: baseline; gap: 6px; }
.datos-campo.col-8 { flex: 8; }
.datos-campo.col-4 { flex: 4; }
.datos-campo .lbl { font-weight: bold; white-space: nowrap; font-size: 10pt; }
.datos-campo .val { flex: 1; border-bottom: 1px solid #777; font-size: 10pt; padding-bottom: 1px; }

/* ── Tabla de calificaciones ── */
.tabla-notas {
    width: 100%; border-collapse: collapse; margin-top: 12px;
    table-layout: fixed;
}
.tabla-notas th {
    background: #1a1a2e; color: #fff;
    padding: 5px 3px; text-align: center;
    font-size: 8.5pt; text-transform: uppercase;
    border: 1px solid #333; word-wrap: break-word;
}
.tabla-notas th.col-materia { text-align: left; padding-left: 6px; }
.tabla-notas td {
    border: 1px solid #ccc; padding: 4px 3px;
    font-size: 10pt; vertical-align: middle; word-wrap: break-word;
}
.tabla-notas td.col-materia { text-align: left; padding-left: 6px; }
.tabla-notas td.col-nota    { text-align: center; }
.tabla-notas tr:nth-child(even) td { background: #f8f8f8; }

.nota-alta  { color: #1a6b1a; font-weight: bold; }
.nota-media { color: #8a6000; font-weight: bold; }
.nota-baja  { color: #8b0000; font-weight: bold; }

/* ── Leyenda ── */
.leyenda { margin-top: 7px; font-size: 8pt; color: #666; }

/* ── Firmas ── */
.firmas { margin-top: 28px; width: 100%; border-collapse: collapse; }
.firmas td { text-align: center; padding: 0 16px; vertical-align: bottom; width: 50%; }
.firmas .fila-encargado td { padding-top: 24px; }
.firmas .fila-encargado td > div { display: inline-block; width: 60%; }
.linea { border-top: 1px solid #000; padding-top: 4px; font-size: 8.5pt; }
.cargo { font-weight: bold; font-size: 8.5pt; }
.sub   { font-size: 8pt; color: #666; }

/* ── Pie ── */
.pie {
    margin-top: 12px; border-top: 1px solid #ddd;
    padding-top: 5px; font-size: 8pt; color: #999; text-align: center;
}

/* ── IMPRESIÓN ── */
@media print {
    .toolbar { display: none !important; }
    body { background: white; padding-top: 0; }
    .pagina {
        width: 100%; min-height: 100%;
        margin: 0; padding: 2cm 2.5cm;
        box-shadow: none;
    }
    @page { size: letter portrait; margin: 0; }
}
</style>
</head>
<body>

{{-- Barra de herramientas --}}
<div class="toolbar">
    <span>Boleta de Calificaciones &mdash; {{ $seleccionado->nombre_completo }}</span>
    <div class="acciones">
        <button class="btn-print" onclick="window.print()">&#128438; Imprimir / Guardar PDF</button>
        <a class="btn-close" href="javascript:history.back()">&#10005; Cerrar</a>
    </div>
</div>

{{-- Página --}}
<div class="pagina">

    {{-- Encabezado --}}
    <div class="encabezado">
        <div class="mineduc">República de Guatemala &mdash; Ministerio de Educación</div>
        <div class="titulo">Informe de Calificaciones</div>
        <div class="escuela">Escuela Oficial Rural Mixta</div>
        <div class="anio">Año Lectivo {{ $anio }}</div>
    </div>

    {{-- Datos del alumno --}}
    <div class="datos">
        <div class="datos-fila">
            <div class="datos-campo col-8">
                <span class="lbl">Nombre del alumno:</span>
                <span class="val">{{ strtoupper($seleccionado->nombre_completo) }}</span>
            </div>
            <div class="datos-campo col-4">
                <span class="lbl">Grado / Sección:</span>
                <span class="val">{{ $inscripcion->gradoSeccion->grado->nombre ?? '' }} {{ $inscripcion->gradoSeccion->seccion->nombre ?? '' }}</span>
            </div>
        </div>
        <div class="datos-fila">
            <div class="datos-campo col-8">
                <span class="lbl">CUI:</span>
                <span class="val">{{ $seleccionado->cui ?? '—' }}</span>
            </div>
            <div class="datos-campo col-4">
                <span class="lbl">Ciclo Escolar:</span>
                <span class="val">{{ $anio }}</span>
            </div>
        </div>
    </div>

    {{-- Tabla de calificaciones --}}
    @php
        $numPeriodos = $periodos->count();
        $pctMateria  = max(30, 42 - max(0, ($numPeriodos - 4) * 2));
        $pctNfinal   = 12;
        $pctPeriodo  = round((100 - $pctMateria - $pctNfinal) / max(1, $numPeriodos), 1);
    @endphp

    @if($materias->isNotEmpty())
    <table class="tabla-notas">
        <colgroup>
            <col style="width:{{ $pctMateria }}%">
            @foreach($periodos as $p)
            <col style="width:{{ $pctPeriodo }}%">
            @endforeach
            <col style="width:{{ $pctNfinal }}%">
        </colgroup>
        <thead>
            <tr>
                <th class="col-materia">Área / Materia</th>
                @foreach($periodos as $i => $periodo)
                    <th>U{{ $i + 1 }}<br><span style="font-size:7pt;font-weight:normal;opacity:.85;">{{ $periodo->nombre }}</span></th>
                @endforeach
                <th>Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materias as $item)
            @php $nf = $item['notaFinal']; @endphp
            <tr>
                <td class="col-materia">{{ $item['materia']->nombre }}</td>
                @foreach($periodos as $periodo)
                @php $nota = $item['notasPorPeriodo'][$periodo->id] ?? null; @endphp
                <td class="col-nota">
                    @if($nota !== null)
                        @php $cls = $nota >= 70 ? 'nota-alta' : ($nota >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                        <span class="{{ $cls }}">{{ number_format($nota, 1) }}</span>
                    @else
                        <span style="color:#ccc">—</span>
                    @endif
                </td>
                @endforeach
                <td class="col-nota">
                    @if($nf !== null)
                        @php $cls = $nf >= 70 ? 'nota-alta' : ($nf >= 60 ? 'nota-media' : 'nota-baja'); @endphp
                        <strong class="{{ $cls }}">{{ number_format($nf, 1) }}</strong>
                    @else
                        <span style="color:#ccc">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="leyenda">
        Escala: 0&ndash;100 pts &nbsp;|&nbsp; Mínimo de promoción: <strong>60</strong> &nbsp;|&nbsp;
        Nota final = promedio de las {{ $numPeriodos }} unidad{{ $numPeriodos === 1 ? '' : 'es' }}
    </div>
    @endif

    {{-- Firmas --}}
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
        <tr class="fila-encargado">
            <td colspan="2">
                <div>
                    <div style="height:28px"></div>
                    <div class="linea"></div>
                    <div class="cargo">Padre / Madre / Encargado</div>
                    <div class="sub">Firma y DPI</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Pie --}}
    <div class="pie">
        Generado el {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }} &mdash; Sistema de Gestión Escolar EORM
    </div>

</div>
</body>
</html>
