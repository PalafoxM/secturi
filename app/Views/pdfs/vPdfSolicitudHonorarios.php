<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Honorarios</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #222; padding: 4px 6px; vertical-align: middle; }
        .header-title { background: #214f7a; color: #fff; text-align: center; font-weight: bold; }
        .section-title { background: #214f7a; color: #fff; text-align: center; font-weight: bold; }
        .label { width: 35%; font-weight: bold; text-align: right; background: #f8f8f8; }
        .value { width: 65%; }
        .center { text-align: center; }
        .no-border { border: none; }
        .firma-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .firma-table td { border: none; text-align: center; vertical-align: top; padding: 20px 8px 0 8px; }
        .firma-linea { display: block; width: 72%; margin: 0 auto 4px auto; border-top: 1px solid #000; height: 1px; }
        .firma-nombre { font-size: 12px; font-weight: bold; line-height: 1.2; text-transform: uppercase; }
        .firma-cargo { font-size: 11px; line-height: 1.2; text-transform: uppercase; }
        .grid-cell {
            position: relative;
            min-height: 110px;
            background-image:
                linear-gradient(to right, rgba(31, 78, 121, 0.12) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(31, 78, 121, 0.12) 1px, transparent 1px);
            background-size: 16px 16px;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="no-border" style="width: 14%;">
                <img src="<?= base_url('assets/logo3.png') ?>" width="90" alt="Logo">
            </td>
            <td class="header-title" style="width: 86%;">
                <div style="font-size: 18px; margin-bottom: 6px;">SOLICITUD DE ELABORACION DE CONTRATO DE PRESTACION DE SERVICIOS</div>
                <div style="font-size: 18px; margin-bottom: 6px;">PERSONALES POR HONORARIOS ASIMILADOS A SALARIOS</div>
                <div style="font-size: 16px;">DIRECCION GENERAL JURIDICA</div>
                <div style="font-size: 15px; margin-top: 4px;">DGJ-4</div>
            </td>
        </tr>
    </table>

    <table style="margin-top: 8px;">
        <tr><td colspan="2" class="section-title">INFORMACION DEL CONTRATO</td></tr>
        <tr>
            <td class="label">Responsable del Proyecto:</td>
            <td class="value"><?= esc($solicitud->responsable_proyecto_nombre ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Area:</td>
            <td class="value"><?= esc($solicitud->area_nombre ?? $solicitud->area ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Informes a rendir:</td>
            <td class="value"><?= esc($solicitud->informes_rendir ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Vigencia:</td>
            <td class="value">
                <?= !empty($solicitud->vigencia_inicio) ? date('d/m/Y', strtotime($solicitud->vigencia_inicio)) : '' ?>
                al
                <?= !empty($solicitud->vigencia_fin) ? date('d/m/Y', strtotime($solicitud->vigencia_fin)) : '' ?>
            </td>
        </tr>
    </table>

    <table style="margin-top: 8px;">
        <tr><td colspan="3" class="section-title">ACTIVIDADES A REALIZAR</td></tr>
        <tr>
            <th style="width: 10%;">No.</th>
            <th colspan="2">Actividad</th>
        </tr>
        <?php if (!empty($actividades)): ?>
            <?php foreach ($actividades as $index => $actividad): ?>
                <tr>
                    <td class="center"><?= $index + 1 ?></td>
                    <td colspan="2"><?= esc($actividad->actividad ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="center">No hay actividades registradas</td>
            </tr>
        <?php endif; ?>
    </table>

    <table style="margin-top: 8px;">
        <tr><td colspan="3" class="section-title">INFORMACION PRESUPUESTAL</td></tr>
        <tr>
            <th>Clave presupuestal</th>
            <th>Numero y nombre de la Partida</th>
            <th>Monto total del Contrato</th>
        </tr>
        <tr>
            <td><?= esc($solicitud->clave_presupuestal_nombre ?? $solicitud->clave_presupuestal ?? '') ?></td>
            <td><?= esc($solicitud->partida_nombre ?? $solicitud->partida ?? '') ?></td>
            <td class="center">$<?= number_format((float) ($solicitud->monto_total_contrato ?? 0), 2) ?></td>
        </tr>
    </table>

    <table style="margin-top: 8px;">
        <tr><td colspan="2" class="section-title">INFORMACION DEL PRESTADOR DE SERVICIOS PERSONALES POR HONORARIOS</td></tr>
        <tr>
            <td class="label">Nombre Completo Prestacion de Servicios:</td>
            <td class="value"><?= esc($solicitud->nombre_prestador ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Prestacion de Servicios:</td>
            <td class="value"><?= esc($solicitud->puesto_prestador_nombre ?? $solicitud->prestacion_servicios ?? $solicitud->puesto_prestador ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">RFC:</td>
            <td class="value"><?= esc($solicitud->rfc_prestador ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Domicilio:</td>
            <td class="value"><?= esc($solicitud->domicilio_prestador ?? '') ?></td>
        </tr>
    </table>

    <table style="margin-top: 8px;">
        <tr><td colspan="3" class="section-title">SOPORTE DOCUMENTAL</td></tr>
        <tr>
            <th>Autorizacion SFIA</th>
            <th>Identificacion Oficial</th>
            <th>Cedula RFC</th>
        </tr>
        <tr>
            <td class="center"><?= !empty($solicitud->autorizacion_sfia) ? 'SI' : 'NO' ?></td>
            <td class="center"><?= !empty($solicitud->justificacion_oficial) ? 'SI' : 'NO' ?></td>
            <td class="center"><?= !empty($solicitud->cedula_rfc) ? 'SI' : 'NO' ?></td>
        </tr>
        <tr>
            <th>Comprobante domicilio</th>
            <th>Autorizacion datos</th>
            <th></th>
        </tr>
        <tr>
            <td class="center"><?= !empty($solicitud->comprobante_domicilio) ? 'SI' : 'NO' ?></td>
            <td class="center"><?= !empty($solicitud->autorizacion_datos) ? 'SI' : 'NO' ?></td>
            <td></td>
        </tr>
    </table>

    <table style="margin-top: 8px;">
        <tr><td class="section-title">VALIDACION DE SOLICITUD</td></tr>
        <tr>
            <td class="grid-cell">
                <?php
                $firmasPdf = !empty($firmas_pdf) ? $firmas_pdf : [
                    (object) ['nombre' => 'Firma pendiente', 'cargo' => 'Cargo'],
                ];
                ?>
                <table class="firma-table">
                    <tr>
                        <?php foreach ($firmasPdf as $firma): ?>
                            <td style="width: <?= 100 / max(count($firmasPdf), 1) ?>%;">
                                <span class="firma-linea"></span>
                                <div class="firma-nombre"><?= esc($firma->nombre ?? '') ?></div>
                                <div class="firma-cargo"><?= esc($firma->cargo ?? '') ?></div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
