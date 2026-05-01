<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Elaboracion de Contrato</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; background-color: #213E66; color: white; }
        .section-title { background-color: #213E66; color: white; padding: 5px; font-weight: bold; margin-top: 15px; }
        .row { display: flex; flex-wrap: wrap; margin-bottom: 5px; }
        .label { font-weight: bold; width: 30%; display: inline-block; }
        .value { border-bottom: 1px solid #ccc; width: 68%; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; width: 25%; }
        th { background-color: #f2f2f2; }
        .firma-table { width: 100%; border: none; margin-top: 35px; table-layout: fixed; }
        .firma-table td { border: none; text-align: center; vertical-align: bottom; padding: 0 10px; }
        .firma-linea { display: block; width: 72%; margin: 0 auto 4px auto; border-top: 1px solid #000; height: 1px; }
        .firma-nombre { font-size: 12px; font-weight: bold; line-height: 1.2; text-transform: uppercase; }
        .firma-cargo { font-size: 11px; line-height: 1.2; text-transform: uppercase; }
        .firma-delegatorio { font-size: 10px; line-height: 1.2; margin-top: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 10%; border: none; background-color: #213E66; text-align: left; padding: 10px;">
                    <img src="<?= base_url('assets/logo3.png') ?>" width="100" alt="Logo">
                </td>
                <td style="width: 90%; border: none; background-color: #213E66; color: white; text-align: center;">
                    <h3 style="margin: 5px 0;">SOLICITUD DE ELABORACIÓN DE CONTRATO DE PRESTACIÓN DE SERVICIOS</h3>
                    <h4 style="margin: 5px 0;">DIRECCIÓN GENERAL JURÍDICA</h4>
                    <h4 style="margin: 5px 0;">DGJ-2</h4>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">INFORMACION DEL AREA SOLICITANTE</div>
    <div>
        <div class="row"><span class="label">Nombre y cargo del responsable del Proyecto:</span> <span class="value"><i><?= $solicitud->nombre_proyecto_puesto ?? $solicitud->nombre_proyecto ?></i></span></div>
        <div class="row"><span class="label">Nombre y cargo del responsable de Seguimiento:</span> <span class="value"><i><?= $solicitud->nombre_seguimiento_puesto ?? $solicitud->nombre_seguimiento ?></i></span></div>
        <div class="row"><span class="label">Nombre y cargo del enlace de Comunicaciones:</span> <span class="value"><i><?= $solicitud->nombre_enlace_puesto ?? $solicitud->nombre_enlace ?></i></span></div>
    </div>

    <div class="section-title">INFORMACION PRESUPUESTAL</div>
    <table>
        <thead>
            <tr>
                <th>Proyecto o Proceso</th>
                <th>Partida</th>
                <th>Clave Estándarizada</th>
                <th>Suficiencia Presupuestal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;"><?= $solicitud->dsc_proyecto ?></td>
                <td style="text-align: center;"><?= $solicitud->cuenta_cable ?></td>
                <td style="text-align: center;"><?= $solicitud->clave_estandarizada ?></td>
                <td style="text-align: center;">El proyecto cuenta con suficiencia presupuestal</td>
            </tr>
            <?php if (!empty($partidas_extra)): ?>
                <?php foreach ($partidas_extra as $partidaExtra): ?>
                    <tr>
                        <td style="text-align: center;"><?= $partidaExtra->dsc_proyecto ?? '' ?></td>
                        <td style="text-align: center;"><?= $partidaExtra->cuenta_cable ?? '' ?></td>
                        <td style="text-align: center;"><?= $partidaExtra->clave ?? '' ?></td>
                        <td style="text-align: center;">Partida adicional</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="margin-top: 10px;">
        <span class="label">Monto:</span> <span class="value"><?= $solicitud->monto_total_formateado ?? $solicitud->monto_total ?> (<?= strtoupper($solicitud->monto_total_texto ?? '') ?>)</span>
    </div>
    <?php if (!empty($solicitud->monto_sin_impuesto)): ?>
    <div>
        <span class="label">Monto del contrato SIN INCLUIR IMPUESTO:</span>
        <span class="value"><?= $solicitud->monto_sin_impuesto_formateado ?? $solicitud->monto_sin_impuesto ?> (<?= strtoupper($solicitud->monto_sin_impuesto_texto ?? '') ?>)</span>
    </div>
    <?php endif; ?>
    <div>
        <span class="label">Tipo / Monto de Garantía:</span>
        <span class="value">
            <?= $solicitud->garantia ?>
            <?php if (!empty($solicitud->monto_garantia_formateado) || !empty($solicitud->monto_garantia_texto)): ?>
                <?= !empty($solicitud->monto_garantia_formateado) ? ' - ' . $solicitud->monto_garantia_formateado : '' ?>
                <?= !empty($solicitud->monto_garantia_texto) ? ' (' . strtoupper($solicitud->monto_garantia_texto) . ')' : '' ?>
            <?php endif; ?>
        </span>
    </div>

    <div class="section-title">DESCRIPCION DEL SERVICIO A CONTRATAR O BIENES A ADQUIRIR</div>
    <div style="border: 1px solid #ccc; padding: 10px; min-height: 50px; margin-bottom: 10px;">
        <?= nl2br($solicitud->objeto_contrato) ?>
    </div>
    <div>
        <span class="label">Vigencia:</span>
        <span class="value">Del <?= date('d/m/Y', strtotime($solicitud->fecha_inicio)) ?> al <?= date('d/m/Y', strtotime($solicitud->fecha_termino)) ?></span>
    </div>

    <h5>Calendario de Pagos</h5>
    <table>
        <thead>
            <tr>
                <th>Pago</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Entregable</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $pagosValidos = [];
                if (!empty($pagos)) {
                    foreach ($pagos as $pagoItem) {
                        $numeroPago = trim((string) ($pagoItem->numero_pago ?? ''));
                        $montoPago = trim((string) ($pagoItem->monto ?? ''));
                        $fechaPago = trim((string) ($pagoItem->fecha ?? ''));
                        $entregablePago = trim((string) ($pagoItem->entregable ?? ''));
                        if ($numeroPago !== '' || $montoPago !== '' || $fechaPago !== '' || $entregablePago !== '') {
                            $pagosValidos[] = $pagoItem;
                        }
                    }
                }
            ?>
            <?php if (!empty($pagosValidos)): ?>
                <?php foreach ($pagosValidos as $pago): ?>
                <tr>
                    <td><?= $pago->numero_pago ?></td>
                    <td><?= $pago->monto_formateado ?? $pago->monto ?></td>
                    <td><?= $pago->fecha ?></td>
                    <td><?= $pago->entregable ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td>N/A</td>
                    <td><?= $solicitud->monto_total_formateado ?? $solicitud->monto_total ?></td>
                    <td>Contra Devengo</td>
                    <td>Entrega de material difundido, ordenes generales y estados de cuenta</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">INFORMACION DEL PROVEEDOR</div>
    <div>
        <div class="row"><span class="label">Nombre/Razón Social:</span> <span class="value"><?= $solicitud->proveedor_nombre ?></span></div>
        <div class="row"><span class="label">Domicilio fiscal:</span> <span class="value"><?= $solicitud->proveedor_domicilio ?></span></div>
        <div class="row"><span class="label">RFC:</span> <span class="value"><?= $solicitud->proveedor_rfc ?></span></div>
        <div class="row"><span class="label">Cedula de Registro en el Padron de Proveedores:</span> <span class="value"><?= $solicitud->proveedor_cedula ?></span></div>
        <div class="row"><span class="label">Nombre del Representante Legal (persona moral):</span> <span class="value"><?= $solicitud->proveedor_representante ?></span></div>
        <div class="row"><span class="label">Responsable de Seguimiento:</span> <span class="value"><?= $solicitud->proveedor_seguimiento ?></span></div>
        <div class="row"><span class="label">Correo electrónico:</span> <span class="value"><?= $solicitud->proveedor_correo ?></span></div>
    </div>

    <div class="section-title">FIRMAS</div>
    <?php
        $firmasPdf = !empty($firmas_pdf) ? $firmas_pdf : [
            (object) ['nombre' => 'Nombre Dir. Gral/Subsecretario', 'cargo' => 'Cargo'],
            (object) ['nombre' => 'Nombre Responsable del Proyecto', 'cargo' => 'Cargo'],
        ];
    ?>
    <table class="firma-table">
        <tr>
            <?php foreach ($firmasPdf as $firma): ?>
                <td style="width: <?= 100 / max(count($firmasPdf), 1) ?>%;">
                    <span class="firma-linea"></span>
                    <div class="firma-nombre"><?= esc($firma->nombre ?? '') ?></div>
                    <div class="firma-cargo"><?= esc($firma->cargo ?? '') ?></div>
                    <?php if (!empty($firma->no_delegatorio ?? '')): ?>
                        <div class="firma-delegatorio">No. delegatorio: <?= esc($firma->no_delegatorio) ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    </table>
</body>
</html>
