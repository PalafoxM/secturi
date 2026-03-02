<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 7pt;
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
            font-size: 7pt;
        }

    </style>
</head>
<body>
    <!-- Layout based on reference image -->
    <table width="100%" style="border-bottom: 2px; margin-bottom: 5px; border-collapse: collapse;">
        <tr>
            <td width="30%" style="text-align: center; vertical-align: top;">
               
            </td>
            <td width="70%" style="text-align: right; font-size: 8pt; vertical-align: top; padding-top: -27px;">
                Lineamientos Generales de Racionalidad, Austeridad y Disciplina Presupuestal de la Administración Pública Estatal<br>
                para el Ejercicio Fiscal de 2026<br>
                «Anexo 3»<br>
                Secretaría de Finanzas
            </td>
        </tr>
    </table>

    <div class="text-center" style="margin-bottom: 15px;">
        <span style="font-size: 9pt; font-weight: bold;">SECRETARÍA DE TURISMO E IDENTIDAD</span><br>
        <span style="font-size: 7pt;">UNIDAD RESPONSABLE</span><br>
        <span style="font-size: 10pt; font-weight: bold;">SOLICITUD DE GASTOS A RESERVA DE COMPROBAR</span><br>
        <span style="font-size: 9pt; font-weight: bold;">GRC SECTURI/SSPT/DRP/010/2026</span><br>
        <span style="font-size: 8pt; font-weight: bold;">DATOS GENERALES DE SOLICITUD</span>
    </div>
<BR>
    <div style="font-size: 9pt; font-weight: bold; margin-bottom: 10px;">
        <span class="number-box" style="text-align: left;">1</span><br>
        L.R.I. Rodrigo González Guerrero
    </div>

    <div style="font-size: 9pt; margin-bottom: 15px;">
        Director General administrativo<br>
        Solicito a usted, la autorización para que se expida cheque a favor de:<br>
        <div class="text-center" style="margin-top: 10px; border-bottom: 0.5px solid #000; width: 100%; padding-bottom: 2px;">
            <strong style="font-size: 10pt;">
                <?= isset($solicitud->cheque_favor_nombre) ? $solicitud->cheque_favor_nombre.' / '.$solicitud->dsc_area : '' ?>
            </strong>
        </div>
    </div>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px; font-size: 8pt;">
        <tr>
            <td style="width: 14%; white-space: nowrap; padding-right: 5px; padding-bottom: 2px;">Por la cantidad de:</td>
            <td style="border-bottom: 0.5px solid #000; padding-bottom: 2px;">
                <strong>
                    $<?= isset($solicitud->cantidad) ? number_format($solicitud->cantidad, 2) : '0.00' ?> (<?= isset($solicitud->cantidad_letra) ? mb_strtoupper($solicitud->cantidad_letra, 'UTF-8') : '' ?>)
                </strong>
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px; font-size: 8pt;">
        <tr>
            <td style="width: 17%; white-space: nowrap; padding-right: 5px; padding-bottom: 2px;">Nombre del evento:</td>
            <td style="border-bottom: 0.5px solid #000; padding-bottom: 2px;">
                <strong>
                    <?= isset($solicitud->nombre_evento) ? $solicitud->nombre_evento : '' ?>
                </strong>
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px; font-size: 8pt;">
        <tr>
            <td style="width: 6%; white-space: nowrap; padding-right: 5px; padding-bottom: 2px;">Lugar:</td>
            <td style="border-bottom: 0.5px solid #000; padding-bottom: 2px;">
                <strong>
                    <?= isset($solicitud->lugar) ? $solicitud->lugar : '' ?>
                </strong>
            </td>
        </tr>
    </table>

    <?php 
    $meses_es = ['January' => 'enero', 'February' => 'febrero', 'March' => 'marzo', 'April' => 'abril', 'May' => 'mayo', 'June' => 'junio', 'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre', 'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'];
    $fecha_inicio_str = '';
    if (isset($solicitud->fecha_inicio) && $solicitud->fecha_inicio) {
        $m_inicio = date('F', strtotime($solicitud->fecha_inicio));
        $fecha_inicio_str = date('d', strtotime($solicitud->fecha_inicio)) . ' de ' . ($meses_es[$m_inicio] ?? $m_inicio);
    }
    $fecha_fin_str = '';
    if (isset($solicitud->fecha_fin) && $solicitud->fecha_fin) {
        $m_fin = date('F', strtotime($solicitud->fecha_fin));
        $y_fin = date('Y', strtotime($solicitud->fecha_fin));
        $fecha_fin_str = date('d', strtotime($solicitud->fecha_fin)) . ' de ' . ($meses_es[$m_fin] ?? $m_fin) . ' de ' . $y_fin;
    }
    ?>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px; font-size: 8pt;">
        <tr>
            <td style="width: 8%; white-space: nowrap; padding-right: 5px; padding-bottom: 2px;">Duración:</td>
            <td style="border-bottom: 0.5px solid #000; padding-bottom: 2px;">
                <strong>
                    <?php if($fecha_inicio_str && $fecha_fin_str): ?>
                        Del <?= $fecha_inicio_str ?> al <?= $fecha_fin_str ?>
                    <?php endif; ?>
                </strong>
            </td>
        </tr>
    </table>

    <div style="font-size: 7pt; margin-bottom: 5px;">Cláusulas:</div>
    <div style="font-size: 6pt; text-align: justify; margin-bottom: 15px; padding-left: 15px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 5px;">
            <tr>
                <td width="20px" valign="top"><strong>1.1</strong></td>
                <td>
                    Cualquier persona que preste un servicio al Estado, independientemente de la relación jurídica que le vincule a 
                    éste y se entreguen recursos para la realización de gastos pendientes de comprobar, invariablemente deberá 
                    entregar los documentos comprobatorios de dicho gasto al área de administration de la Dependencia o Entidad 
                    con Registro del Gasto Descentralizado de que se trate, en el siguiente plazo máximo:<br>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 5px;">
                        <tr>
                            <td width="20px" valign="top">a.</td>
                            <td><strong>5 días hábiles</strong> contados a partir de que terminen la comisión o evento, tanto en el interior como en el extranjero.</td>
                        </tr>
                        <tr>
                            <td width="20px" valign="top">b.</td>
                            <td><strong>3 días hábiles</strong> contados a partir de la cancelación de la comisión o evento.</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="20px" valign="top"><strong>1.2</strong></td>
                <td>
                    Si no presento la documentación comprobatoria o en su caso, el reintegro de los recursos dentro de los plazos 
                    arriba mencionados, autorizo para que, a través de la Dirección General de Recursos Humanos, me sea 
                    descontado el recurso vía nómina, para lo cual proporciono los siguientes datos:
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 5px;">
                        <tr>
                            <td width="20px" valign="top">a)</td>
                            <td>Clave Presupuestaria donde se efectúa el pago de nómina: <strong class="line-input" style="width: 300px;"><?= isset($solicitud->clave) ? $solicitud->clave : '' ?></strong></td>
                        </tr>
                        <tr>
                            <td width="20px" valign="top">b)</td>
                            <td>Nombre del Responsable de la Comprobación: <strong class="line-input" style="width: 300px;"><?= isset($solicitud->nombre_completo) ? mb_strtoupper($solicitud->nombre_completo, 'UTF-8') : '' ?></strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="firma-container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="firma-line" style="width: 300px;">
            Firma de la persona Responsable de la<br>
            Comprobación
        </div>
    </div>

    <div class="mb-1 text-center" style="margin-top: 10px;">
        <span class="font-weight-bold">DATOS PRESUPUESTARIOS</span><br>
        <span class="font-weight-bold" style="font-size: 8pt;">PRESUPUESTO</span>
    </div>

    <?php 
        $num_detalles = isset($detalles) ? count($detalles) : 0;
        $table_fs = '9pt';
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

    <table class="presupuesto-table" style="font-size: <?= $table_fs ?>; border: none; width: 100%;">
        <thead>
            <tr>
                <th style="padding: <?= $td_pad ?>; text-align: left; background-color: white; border: none; font-size: 8pt; font-weight: normal;">Concepto</th>
                <th style="width: 100px; padding: <?= $td_pad ?>; text-align: right; background-color: white; border: none; font-size: 8pt; font-weight: normal;">Importe</th>
                <th style="width: 150px; padding: <?= $td_pad ?>; text-align: center; background-color: white; border: none; font-size: 8pt; font-weight: normal;">Clave presupuestaria</th>
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
                    <td style="padding: <?= $td_pad ?>; border: none; font-size: 8pt;"><?= $det->cuenta_cable .' '. $det->nombre_fondo?></td>
                    <td class="text-right" style="padding: <?= $td_pad ?>; border: none; font-size: 8pt;">$<?= number_format($det->importe, 2) ?></td>
                    <td class="text-center" style="padding: <?= $td_pad ?>; border: none; font-size: 8pt;"><?= $det->proyecto ?></td>
                </tr>
                <?php endforeach; ?>
                 <tr>
                    <td class="font-weight-bold" style="padding: <?= $td_pad ?>; border: none; font-size: 8pt;">Total</td>
                    <td class="text-right font-weight-bold" style="padding: <?= $td_pad ?>; border: none; font-size: 8pt;">$<?= number_format($totalGeneral, 2) ?></td>
                    <td style="padding: <?= $td_pad ?>; border: none;"></td>
                </tr>
            <?php else: ?>
                <tr><td colspan="3" class="text-center" style="border: none;">No hay detalles registrados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table style="width: 100%; border: none; margin-top: 30px; margin-bottom: 5px;">
        <tr>
            <td style="width: 50%; vertical-align: bottom; border: none;">
                <div style="font-size: 9pt; font-weight: bold; border-bottom: 2px solid black; display: inline-block; padding-bottom: 2px;">
                    <?= isset($fecha_texto) ? $fecha_texto : '' ?>
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: bottom; border: none; padding-bottom: 0;">
                <div style="margin-bottom: 5px; font-weight: bold; font-size: 10pt;">L.R.I. Rodrigo González Guerrero</div>
                <div style="border-top: 1px solid black; padding-top: 2px; font-size: 9pt;">
                    Nombre y Firma de la persona Titular del Área<br>Administrativa
                </div>
            </td>
        </tr>
    </table>

    <div style="font-weight: bold; text-align: justify; font-size: 8pt; line-height: 1.1; margin-top: 15px; border-top: 1px solid #ccc; padding-top: 10px; color: #555;">
        NOTA: Tratándose de eventos o comisiones en el extranjero, a ésta solicitud deberá anexarse además el
        formato "Solicitud de Viaje al Extranjero" debidamente autorizada por la persona titular del Poder Ejecutivo o la
        persona que ésta designe.
    </div>

</body>
</html>
