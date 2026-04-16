<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Elaboración de Convenio</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: top; }
        .text-center { text-align: center; }
        .header-table td { border: 1px solid #000; }
        .header-logo { width: 90px; text-align: center; background: #fff; }
        .header-title { background-color: #1f4e79; color: #fff; text-align: center; }
        .header-title h2 { margin: 6px 0; font-size: 16px; }
        .header-title h3 { margin: 4px 0; font-size: 12px; }
        .header-title h4 { margin: 2px 0; font-size: 10px; font-weight: normal; }
        .bg-primary { background-color: #1f4e79; color: white; font-weight: bold; }
        .monto-letra { display: block; font-size: 9px; margin-top: 2px; }
        .firma-table { margin-top: 35px; }
        .firma-table td { border: none; text-align: center; vertical-align: bottom; padding-top: 35px; }
        .firma-linea { display: inline-block; width: 80%; border-top: 1px solid #000; padding-top: 6px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="<?= base_url('assets/logo3.png') ?>" width="70" alt="Logo">
            </td>
            <td class="header-title">
                <h2>SOLICITUD DE ELABORACIÓN DE CONVENIO</h2>
                <h3>DIRECCIÓN DE ASUNTOS JURÍDICOS</h3>
                <h4>DAJ-2</h4>
            </td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="bg-primary text-center">INFORMACIÓN DEL ÁREA SOLICITANTE</td></tr>
        <tr>
            <td width="30%"><strong>Responsable del Proyecto:</strong></td>
            <td><?= isset($solicitud->nombre_proyecto) ? $solicitud->nombre_proyecto : '' ?></td>
        </tr>
        <tr>
            <td><strong>Responsable de Seguimiento:</strong></td>
            <td><?= isset($solicitud->nombre_seguimiento) ? $solicitud->nombre_seguimiento : '' ?></td>
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
            <td>Anexa: <?= !empty($solicitud->archivo_suficiencia) ? 'Sí' : 'No' ?></td>
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
        <tr><td colspan="2" class="bg-primary text-center">INFORMACIÓN DEL PROVEEDOR/CONVENIO</td></tr>
        <tr><td width="30%"><strong>Nombre / Razón Social:</strong></td><td><?= isset($solicitud->proveedor_nombre) ? $solicitud->proveedor_nombre : '' ?></td></tr>
        <tr><td><strong>Domicilio fiscal:</strong></td><td><?= isset($solicitud->proveedor_domicilio) ? $solicitud->proveedor_domicilio : '' ?></td></tr>
        <tr><td><strong>Cédula de Reg. Proveedores:</strong></td><td><?= isset($solicitud->proveedor_cedula) ? $solicitud->proveedor_cedula : '' ?></td></tr>
        <tr><td><strong>RFC:</strong></td><td><?= isset($solicitud->proveedor_rfc) ? $solicitud->proveedor_rfc : '' ?></td></tr>
        <tr><td><strong>Representante Legal:</strong></td><td><?= isset($solicitud->proveedor_representante) ? $solicitud->proveedor_representante : '' ?></td></tr>
        <tr><td><strong>Nombre quien asiste Rep.:</strong></td><td><?= isset($solicitud->proveedor_asistente) ? $solicitud->proveedor_asistente : '' ?></td></tr>
        <tr><td><strong>Resp. Seguimiento:</strong></td><td><?= isset($solicitud->proveedor_seguimiento) ? $solicitud->proveedor_seguimiento : '' ?></td></tr>
        <tr><td><strong>Correo Electrónico:</strong></td><td><?= isset($solicitud->proveedor_correo) ? $solicitud->proveedor_correo : '' ?></td></tr>
    </table>

    <table class="firma-table">
        <tr>
            <td width="50%">
                <span class="firma-linea">Nombre Dir. Gral/Subsecretario<br>Cargo</span>
            </td>
            <td width="50%">
                <span class="firma-linea">Nombre Responsable del Proyecto<br>Cargo</span>
            </td>
        </tr>
    </table>
</body>
</html>
