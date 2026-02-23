<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 2px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 15px; }
        .w-100 { width: 100%; }
        
        .title {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        .number-box {
            display: inline-block;
            width: 20px;
            font-weight: bold;
            text-align: center;
        }

        .line-input {
            border-bottom: 1px solid #000;
            display: inline-block;
        }

        table.presupuesto-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 10pt;
        }
        table.presupuesto-table th, table.presupuesto-table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: left;
        }
        table.presupuesto-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        
        .firma-container {
            width: 100%;
            text-align: center;
            margin-top: 30px;
        }
        .firma-line {
            width: 400px;
            border-top: 1px solid #000;
            margin: 0 auto;
            padding-top: 2px;
            font-size: 10pt;
        }

        /* Watermark Background Optional */
        body {
            background-image: url('public/assets/images/membrete.png');
            background-position: center 100px;
            background-repeat: no-repeat;
            background-image-resize: 4;
            background-image-opacity: 0.15;
        }
    </style>
</head>
<body>

    <div class="title">SOLICITUD DE GASTOS A RESERVA DE COMPROBAR GRC-1</div>

    <div class="mb-3">
        <span class="number-box">1</span>
        <span class="font-weight-bold" style="margin-left: 150px;">DATOS GENERALES DE SOLICITUD</span>
    </div>

    <div class="mb-3">
        <p class="mb-1">L.R.I Rodrigo González Guerrero</p>
        <p class="mb-1">Director General administrativo</p>
        <p class="mb-1">Solicito a usted, la autorización para que se expida cheque a favor de:</p>
    </div>

    <div class="text-center mb-3">
        <strong class="line-input" style="font-size: 10pt; width: 80%;">
            <?= isset($solicitud->cheque_favor_nombre) ? $solicitud->cheque_favor_nombre.' / '.$solicitud->dsc_area : '' ?>
        </strong>
    </div>

    <div class="mb-2">
        <span style="display:inline-block; width: 120px;">Por la cantidad de:</span>
        <span class="line-input" style="width: 550px;">
            $<?= isset($solicitud->cantidad) ? number_format($solicitud->cantidad, 2) : '0.00' ?> 
            (<?= isset($cantidad_letra) ? $cantidad_letra : '' ?>)
        </span>
    </div>

    <div class="mb-2">
        <span style="display:inline-block; width: 120px; vertical-align: top;">Nombre del evento:</span>
        <span class="line-input" style="width: 550px; text-align: justify;">
            <?= isset($solicitud->nombre_evento) ? $solicitud->nombre_evento : '' ?>
        </span>
    </div>

    <div class="mb-2">
        <span style="display:inline-block; width: 120px;">Lugar:</span>
        <span class="line-input" style="width: 550px;">
            <?= isset($solicitud->lugar) ? $solicitud->lugar : '' ?>
        </span>
    </div>

    <div class="mb-4">
        <span style="display:inline-block; width: 120px;">Duración:</span>
        <span class="line-input" style="width: 550px;">
            Del <?= isset($solicitud->fecha_inicio) ? date('d/m/Y', strtotime($solicitud->fecha_inicio)) : '' ?>
            al <?= isset($solicitud->fecha_fin) ? date('d/m/Y', strtotime($solicitud->fecha_fin)) : '' ?>
        </span>
    </div>

    <div class="mb-2">
        <span style="display:inline-block;">Clave Presupuestaria donde se efectúa el pago de nómina:</span>
        <span class="line-input font-weight-bold" style="width: 270px; text-align: center;">
            <?= isset($solicitud->clave) ? $solicitud->clave : '' ?>
        </span>
    </div>

    <div class="mb-4">
        <span style="display:inline-block;">Nombre del Responsable de la Comprobación:</span>
        <span class="line-input font-weight-bold" style="width: 350px; text-align: center;">
            <?= isset($solicitud->nombre_completo) ? mb_strtoupper($solicitud->nombre_completo, 'UTF-8') : '' ?>
        </span>
    </div>

    <div class="firma-container">
        <div class="firma-line">
            Firma de la persona Responsable de la<br>
            Comprobación
        </div>
    </div>

    <div class="mb-1 text-center" style="margin-top: 10px;">
        <span class="number-box" style="position: absolute; left: 15px;">2</span>
        <span class="font-weight-bold" style="margin-left: 20px;">DATOS PRESUPUESTARIOS</span><br>
        <span class="font-weight-bold">PRESUPUESTO</span>
    </div>

    <?php 
        $num_detalles = isset($detalles) ? count($detalles) : 0;
        $table_fs = '10pt';
        $td_pad = '5px';
        
        if ($num_detalles > 8 && $num_detalles <= 12) {
            $table_fs = '8pt';
            $td_pad = '3px';
        } elseif ($num_detalles > 12 && $num_detalles <= 18) {
            $table_fs = '7pt';
            $td_pad = '2px';
        } elseif ($num_detalles > 18) {
            $table_fs = '6pt';
            $td_pad = '1px';
        }
    ?>

    <table class="presupuesto-table" style="font-size: <?= $table_fs ?>;">
        <thead>
            <tr>
                <th style="padding: <?= $td_pad ?>;">Concepto</th>
                <th style="width: 100px; padding: <?= $td_pad ?>;">Importe</th>
                <th style="width: 150px; padding: <?= $td_pad ?>;">Clave presupuestaria</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalGeneral = 0;
            if (isset($detalles) && !empty($detalles)): ?>
                <?php foreach ($detalles as $det): 
                    $totalGeneral += $det->importe;
                ?>
                <tr>
                    <td style="padding: <?= $td_pad ?>;"><?= $det->cuenta_cable .' '. $det->nombre_fondo?></td>
                    <td class="text-right" style="padding: <?= $td_pad ?>;">$<?= number_format($det->importe, 2) ?></td>
                    <td class="text-center" style="padding: <?= $td_pad ?>;"><?= $det->proyecto ?></td>
                </tr>
                <?php endforeach; ?>
                 <tr>
                    <td class="text-right font-weight-bold" style="padding: <?= $td_pad ?>;">TOTAL:</td>
                    <td class="text-right font-weight-bold" style="padding: <?= $td_pad ?>;">$<?= number_format($totalGeneral, 2) ?></td>
                    <td style="padding: <?= $td_pad ?>;"></td>
                </tr>
            <?php else: ?>
                <tr><td colspan="3" class="text-center">No hay detalles registrados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table style="width: 100%; border: none; margin-top: 15px; margin-bottom: 5px;">
        <tr>
            <td style="width: 50%; vertical-align: bottom; border: none;">
                <div style="font-size: 14pt; font-weight: bold; border-bottom: 2px solid black; display: inline-block; padding-bottom: 2px;">
                    <?= isset($fecha_texto) ? $fecha_texto : '' ?>
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: bottom; border: none; padding-bottom: 0;">
                <div style="margin-bottom: 5px;">L.R.I. Rodrigo González Guerrero</div>
                <div style="border-top: 1px solid black; padding-top: 2px;">
                    Nombre y Firma de la persona Titular del Área<br>Administrativa
                </div>
            </td>
        </tr>
    </table>

    <div style="font-weight: bold; text-align: justify; font-size: 9pt; line-height: 1.1;">
        NOTA: Tratándose de eventos o comisiones en el extranjero, a ésta solicitud deberá anexarse además el
        formato "Solicitud de Viaje al Extranjero" debidamente autorizada por la persona titular del Poder Ejecutivo o la
        persona que ésta designe.
    </div>

</body>
</html>
