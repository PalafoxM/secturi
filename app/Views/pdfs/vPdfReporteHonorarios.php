<?php
    $reporte = $reporte ?? new stdClass();
    $actividades = $actividades ?? [];

    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];

    $formatFecha = static function ($fecha) use ($meses) {
        if (empty($fecha)) {
            return ['dia' => '__', 'mes' => '__________', 'anio' => '20__'];
        }

        $timestamp = strtotime((string) $fecha);
        if (!$timestamp) {
            return ['dia' => '__', 'mes' => '__________', 'anio' => '20__'];
        }

        return [
            'dia' => date('d', $timestamp),
            'mes' => $meses[(int) date('n', $timestamp)] ?? '',
            'anio' => date('Y', $timestamp),
        ];
    };

    $fechaInicio = $formatFecha($reporte->fecha_inicio ?? null);
    $fechaFin = $formatFecha($reporte->fecha_fin ?? null);
    $fechaFirma = $formatFecha($reporte->fecha_firma ?? null);
    $tipoReporte = strtolower((string) ($reporte->tipo_reporte ?? 'trimestral'));

    $splitBullets = static function ($texto) {
        $lineas = preg_split('/\r\n|\r|\n/', (string) $texto);
        $lineas = array_values(array_filter(array_map('trim', $lineas), static function ($linea) {
            return $linea !== '';
        }));

        return empty($lineas) ? ['Sin desglose'] : $lineas;
    };
?>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Cambria, "Times New Roman", serif;
            font-size: 11pt;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .sheet {
            padding: 0;
            line-height: 1.38;
        }
        .line {
            margin: 0 0 4px 0;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 10.5pt;
        }
        .spacer-sm {
            height: 6mm;
        }
        .spacer-md {
            height: 10mm;
        }
        .paragraph {
            margin: 0 0 4mm 0;
            text-align: justify;
            text-indent: 10mm;
            font-size: 10.5pt;
        }
        .title {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 11pt;
            margin: 0 0 2mm 0;
        }
        .activities {
            margin-top: 4mm;
            margin-bottom: 6mm;
        }
        .activity-block {
            margin-bottom: 4mm;
        }
        .activity-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 1mm;
            font-size: 10.5pt;
        }
        .bullet {
            margin-left: 8mm;
            margin-bottom: 1mm;
            font-size: 10.3pt;
        }
        .attention {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 3mm;
            margin-bottom: 2mm;
        }
        .center {
            text-align: center;
        }
        .signature-space {
            height: 8mm;
        }
        .signature-line {
            width: 70%;
            margin: 0 auto 2mm auto;
            border-top: 1px solid #000;
        }
        .signature-line-wide {
            width: 92%;
            margin: 0 auto 2mm auto;
            border-top: 1px solid #000;
        }
        .signature-caption {
            text-align: center;
            font-size: 10pt;
            line-height: 1.3;
        }
        .closing-block {
            margin-top: 2mm;
        }
        .closing-paragraph {
            margin-bottom: 2mm;
        }
        .conformidad {
            margin-top: 2mm;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <br>
    <div class="sheet">
        <p class="line">Nombre y puesto del responsable de administrativo: <?= esc($reporte->responsable_administrativo ?? '') ?></p>
        <p class="line">Área: <?= esc($reporte->area ?? '') ?></p>
        <p class="line">Secretaría de Turismo e Identidad</p>

        <div class="spacer-sm"></div>

        <p class="paragraph">
            Por medio del presente, me dirijo a usted de la manera mas atenta, con el fin de dar cumplimiento a la CLAÚSULA SEGUNDA del Contrato de Servicios Profesionales por Honorarios Asimilados a Salarios No. <?= esc($reporte->numero_contrato ?? '') ?>, que tuve a bien celebrar con la Secretaría de Turismo e Identidad del Estado de Guanajuato.
        </p>

        <p class="paragraph">
            Por lo anterior, me permito presentar el Informe <?= esc($tipoReporte) ?>, en el que se refleja la realizacion de las actividades señaladas en la CLAÚSULA PRIMERA del Contrato en mención.
        </p>

        <p class="title">Informe <?= esc(strtoupper($tipoReporte)) ?> de actividades</p>
        <p class="title">Del <?= esc($fechaInicio['dia']) ?> de <?= esc($fechaInicio['mes']) ?> al <?= esc($fechaFin['dia']) ?> de <?= esc($fechaFin['mes']) ?> de <?= esc($fechaFin['anio']) ?></p>

        <div class="activities">
            <?php foreach ($actividades as $index => $actividad): ?>
                <div class="activity-block">
                    <div class="activity-title"><?= ($index + 1) ?>. <?= esc($actividad->titulo_actividad ?? 'Actividad') ?></div>
                    <?php foreach ($splitBullets($actividad->desglose_actividad ?? '') as $bullet): ?>
                        <div class="bullet">- <?= esc($bullet) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="closing-block">
            <p class="paragraph closing-paragraph">
                Finalmente, declaro que he dado cumplimiento a las políticas y obligaciones en materia de transparencia, rendición de cuentas, cultura de la legalidad, integridad y participación ciudadana para el combate a la corrupción, enunciadas en la CLAÚSULA QUINTA del Instrumento Juridico mencionado en supra líneas.
            </p>

            <div class="attention">A T E N T A M E N T E</div>
            <div class="center">Silao de la Victoria, Gto., a <?= esc($fechaFirma['dia']) ?> de <?= esc($fechaFirma['mes']) ?> <?= esc($fechaFirma['anio']) ?></div>

            <div class="signature-space"></div>
            <div class="signature-line"></div>
            <div class="signature-caption">Nombre completo: <?= esc($reporte->nombre_prestador ?? '') ?></div>
            <div class="signature-caption">Puesto: <?= esc($reporte->puesto_prestador ?? '') ?></div>

            <div class="spacer-md"></div>
            <div class="conformidad">Recibí de Conformidad,</div>

            <div class="signature-space"></div>
            <div class="signature-line-wide"></div>
            <div class="signature-caption">Director de área: <?= esc($reporte->nombre_responsable_area ?? '') ?></div>
            <div class="signature-caption">Puesto: <?= esc($reporte->puesto_responsable_area ?? '') ?></div>
            <div class="signature-caption">Responsable de administrar y verificar: <?= esc($reporte->nombre_responsable ?? ($reporte->nombre_responsable_area ?? '')) ?></div>
        </div>
    </div>
</body>
</html>
