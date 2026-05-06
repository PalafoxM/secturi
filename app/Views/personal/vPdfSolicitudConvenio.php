<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Elaboración de Convenio</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10.7px; color: #000; line-height: 1.18; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 7px; page-break-inside: avoid; }
        th, td { border: 1px solid #000; padding: 4px 5px; text-align: left; vertical-align: top; line-height: 1.18; }
        .text-center { text-align: center; }
        .header-table td { border: 1px solid #000; }
        .header-logo { width: 86px; text-align: center; background: #fff; }
        .header-title { background-color: #1f4e79; color: #fff; text-align: center; }
        .header-title h2 { margin: 4px 0; font-size: 14.8px; }
        .header-title h3 { margin: 2px 0; font-size: 11.5px; }
        .header-title h4 { margin: 1px 0; font-size: 9.8px; font-weight: normal; }
        .bg-primary { background-color: #1f4e79; color: white; font-weight: bold; }
        .monto-letra { display: block; font-size: 9.2px; margin-top: 1px; line-height: 1.1; }
        .firma-table { margin-top: 22px; page-break-inside: avoid; }
        .firma-table td { border: none; text-align: center; vertical-align: bottom; padding-top: 38px; }
        .firma-table tr:first-child td { border: 1px solid #000; padding-top: 3px; padding-bottom: 3px; vertical-align: middle; }
        .firma-linea { display: block; width: 72%; margin: 0 auto 3px auto; border-top: 1px solid #000; height: 1px; }
        .firma-nombre { font-size: 10.1px; font-weight: bold; line-height: 1.14; text-transform: uppercase; }
        .firma-cargo { font-size: 9.3px; line-height: 1.14; text-transform: uppercase; }
        .firma-delegatorio { font-size: 8.6px; line-height: 1.14; margin-top: 2px; }
        h5 { margin: 4px 0 6px 0; font-size: 10.7px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="<?= base_url('assets/logo3.png') ?>" width="68" alt="Logo">
            </td>
            <td class="header-title">
                <h2>SOLICITUD DE ELABORACIÓN DE CONVENIO</h2>
                <h3>DIRECCIÓN GENERAL JURÍDICA</h3>
                <h4>DGJ-1</h4>
            </td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="bg-primary text-center">INFORMACIÓN DEL ÁREA SOLICITANTE</td></tr>
        <tr>
            <td width="30%"><strong>Responsable del Proyecto:</strong></td>
            <td><?= isset($solicitud->nombre_proyecto_puesto) ? $solicitud->nombre_proyecto_puesto : (isset($solicitud->nombre_proyecto) ? $solicitud->nombre_proyecto : '') ?></td>
        </tr>
        <tr>
            <td><strong>Responsable de Seguimiento:</strong></td>
            <td><?= isset($solicitud->nombre_seguimiento_puesto) ? $solicitud->nombre_seguimiento_puesto : (isset($solicitud->nombre_seguimiento) ? $solicitud->nombre_seguimiento : '') ?></td>
        </tr>
    </table>

    <table>
        <tr><td colspan="4" class="bg-primary text-center">INFORMACIÓN PRESUPUESTAL</td></tr>
        <tr>
            <th>Proyecto</th>
            <th>Partida</th>
            <th>Suficiencia Presupuestal</th>
        </tr>
        <tr>
            <td><?= isset($solicitud->dsc_proyecto) ? $solicitud->dsc_proyecto : '' ?></td>
            <td><?= isset($solicitud->cuenta_cable) ? $solicitud->cuenta_cable : '' ?></td>
            <td>Anexa: Sí</td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="bg-primary text-center">APORTACIONES AL CONVENIO</td></tr>
        <tr>
            <td width="50%"><strong>Monto SECTURI:</strong></td>
            <td>
                <?= isset($solicitud->monto_secturi) ? $solicitud->monto_secturi : '' ?>
                <span class="monto-letra"><?= isset($solicitud->monto_secturi_letra) ? $solicitud->monto_secturi_letra : '' ?></span>
            </td>
        </tr>
        <tr>
            <td><strong>Monto Federal:</strong></td>
            <td>
                <?= isset($solicitud->monto_federal) ? $solicitud->monto_federal : '' ?>
                <span class="monto-letra"><?= isset($solicitud->monto_federal_letra) ? $solicitud->monto_federal_letra : '' ?></span>
            </td>
        </tr>
        <tr>
            <td><strong>Monto Otra Dependencia:</strong></td>
            <td>
                <?= isset($solicitud->monto_otra) ? $solicitud->monto_otra : '' ?>
                <span class="monto-letra"><?= isset($solicitud->monto_otra_letra) ? $solicitud->monto_otra_letra : '' ?></span>
            </td>
        </tr>
        <tr>
            <td><strong>Monto Total:</strong></td>
            <td>
                <?= isset($solicitud->monto_total) ? $solicitud->monto_total : '' ?>
                <span class="monto-letra"><?= isset($solicitud->monto_total_letra) ? $solicitud->monto_total_letra : '' ?></span>
            </td>
        </tr>
    </table>

    <table>
        <tr><td class="bg-primary text-center">DESCRIPCIÓN DE ACCIONES A CONVENIR</td></tr>
        <tr><td><?= isset($solicitud->objeto_convenio) ? $solicitud->objeto_convenio : '' ?></td></tr>
    </table>

    <table>
        <tr><td colspan="4" class="bg-primary text-center">VIGENCIA Y MINISTRACIONES</td></tr>
        <tr>
            <td><strong>Fecha Inicio:</strong> <?= !empty($solicitud->fecha_inicio) ? date('d/m/Y', strtotime($solicitud->fecha_inicio)) : '' ?></td>
            <td colspan="3"><strong>Fecha Término:</strong> <?= !empty($solicitud->fecha_termino) ? date('d/m/Y', strtotime($solicitud->fecha_termino)) : '' ?></td>
        </tr>
        <tr>
            <th>Ministración</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Entregable</th>
        </tr>
        <?php if (!empty($pagos)): ?>
            <?php foreach ($pagos as $p): ?>
            <tr>
                <td><?= $p->numero_pago ?></td>
                <td>$<?= number_format((float) $p->monto, 2, '.', ',') ?></td>
                <td><?= $p->fecha ?></td>
                <td><?= $p->entregable ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center">Sin ministraciones</td></tr>
        <?php endif; ?>
    </table>

    <table>
        <tr><td colspan="2" class="bg-primary text-center">INFORMACIÓN</td></tr>
        <tr><td width="30%"><strong>Nombre / Razón Social:</strong></td><td><?= isset($solicitud->proveedor_nombre) ? $solicitud->proveedor_nombre : '' ?></td></tr>
        <tr><td><strong>Domicilio fiscal:</strong></td><td><?= isset($solicitud->proveedor_domicilio) ? $solicitud->proveedor_domicilio : '' ?></td></tr>
        <tr><td><strong>Cédula de Reg. Proveedores:</strong></td><td><?= isset($solicitud->proveedor_cedula) ? $solicitud->proveedor_cedula : '' ?></td></tr>
        <tr><td><strong>RFC:</strong></td><td><?= isset($solicitud->proveedor_rfc) ? $solicitud->proveedor_rfc : '' ?></td></tr>
        <tr><td><strong>Representante Legal:</strong></td><td><?= isset($solicitud->proveedor_representante) ? $solicitud->proveedor_representante : '' ?></td></tr>
        <tr><td><strong>Nombre quien asiste Rep.:</strong></td><td><?= isset($solicitud->proveedor_asistente) ? $solicitud->proveedor_asistente : '' ?></td></tr>
        <tr><td><strong>Resp. Seguimiento:</strong></td><td><?= isset($solicitud->proveedor_seguimiento) ? $solicitud->proveedor_seguimiento : '' ?></td></tr>
        <tr><td><strong>Correo Electrónico:</strong></td><td><?= isset($solicitud->proveedor_correo) ? $solicitud->proveedor_correo : '' ?></td></tr>
    </table>
 
    <?php
        $firmasPdf = !empty($firmas_pdf) ? $firmas_pdf : [
            (object) ['nombre' => 'Nombre Dir. Gral/Subsecretario', 'cargo' => 'Cargo'],
            (object) ['nombre' => 'Nombre Responsable del Proyecto', 'cargo' => 'Cargo'],
        ];
    ?>
  
    <table class="firma-table">
        <tr><td colspan="2" class="bg-primary text-center">FIRMAS</td></tr>
        <tr>
            <?php foreach ($firmasPdf as $firma): ?>
                <td width="<?= 100 / max(count($firmasPdf), 1) ?>%">
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
