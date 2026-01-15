<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Elaboración de Contrato</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .section-title { background-color: #213E66; color: white; padding: 5px; font-weight: bold; margin-top: 15px; }
        .row { display: flex; flex-wrap: wrap; margin-bottom: 5px; }
        .label { font-weight: bold; width: 30%; display: inline-block; }
        .value { border-bottom: 1px solid #ccc; width: 68%; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h3>SOLICITUD DE ELABORACIÓN DE CONTRATO</h3>
        <p>Fecha de registro: <?= date('d/m/Y H:i', strtotime($solicitud->fec_reg)) ?></p>
    </div>

    <div class="section-title">INFORMACIÓN DEL ÁREA SOLICITANTE</div>
    <div>
        <div class="row"><span class="label">Responsable del Proyecto:</span> <span class="value"><?= $solicitud->responsable_proyecto ?></span></div>
        <div class="row"><span class="label">Responsable de Seguimiento:</span> <span class="value"><?= $solicitud->responsable_seguimiento ?></span></div>
        <div class="row"><span class="label">Enlace de Comunicaciones:</span> <span class="value"><?= $solicitud->enlace_comunicaciones ?></span></div>
    </div>

    <div class="section-title">INFORMACIÓN PRESUPUESTAL</div>
    <table>
        <thead>
            <tr>
                <th>Proyecto</th>
                <th>Partida</th>
                <th>Clave Estándar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $solicitud->proyecto ?></td>
                <td><?= $solicitud->partida ?></td>
                <td><?= $solicitud->clave_estandarizada ?></td>
            </tr>
        </tbody>
    </table>
    <div style="margin-top: 10px;">
        <span class="label">Monto Total:</span> <span class="value"><?= $solicitud->monto_total ?></span>
    </div>
    <div>
        <span class="label">Garantía:</span> <span class="value"><?= $solicitud->garantia ?></span>
    </div>

    <div class="section-title">DESCRIPCIÓN DEL SERVICIO</div>
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
            <?php if(!empty($pagos)): ?>
                <?php foreach($pagos as $pago): ?>
                <tr>
                    <td><?= $pago->numero_pago ?></td>
                    <td><?= $pago->monto ?></td>
                    <td><?= date('d/m/Y', strtotime($pago->fecha)) ?></td>
                    <td><?= $pago->entregable ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">No hay pagos registrados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">INFORMACIÓN DEL PROVEEDOR</div>
    <div>
        <div class="row"><span class="label">Razón Social:</span> <span class="value"><?= $solicitud->proveedor_nombre ?></span></div>
        <div class="row"><span class="label">Domicilio:</span> <span class="value"><?= $solicitud->proveedor_domicilio ?></span></div>
        <div class="row"><span class="label">RFC:</span> <span class="value"><?= $solicitud->proveedor_rfc ?></span></div>
        <div class="row"><span class="label">Cédula:</span> <span class="value"><?= $solicitud->proveedor_cedula ?></span></div>
        <div class="row"><span class="label">Representante:</span> <span class="value"><?= $solicitud->proveedor_representante ?></span></div>
        <div class="row"><span class="label">Correo:</span> <span class="value"><?= $solicitud->proveedor_correo ?></span></div>
    </div>

</body>
</html>
