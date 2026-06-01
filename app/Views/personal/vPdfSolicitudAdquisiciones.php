<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Adquisicion</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #222; padding: 3px 4px; vertical-align: middle; line-height: 1.16; }
        .header-title { background: #214f7a; color: #fff; text-align: center; font-weight: bold; }
        .section-title { background: #214f7a; color: #fff; text-align: center; font-weight: bold; }
        .label { width: 35%; font-weight: bold; text-align: right; background: #f8f8f8; }
        .value { width: 65%; }
        .center { text-align: center; }
        .right { text-align: right; }
        .no-border { border: none; }
        .mt { margin-top: 5px; }
        .firma-table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .firma-table td { border: none; text-align: center; vertical-align: top; padding: 16px 7px 0 7px; }
        .firma-linea { display: block; width: 72%; margin: 0 auto 3px auto; border-top: 1px solid #000; height: 1px; }
        .firma-nombre { font-size: 9.8px; font-weight: bold; line-height: 1.16; text-transform: uppercase; }
        .firma-cargo { font-size: 9.2px; line-height: 1.16; text-transform: uppercase; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="no-border" style="width: 14%;">
                <img src="<?= base_url('assets/logo3.png') ?>" width="76" alt="Logo">
            </td>
            <td class="header-title" style="width: 86%;">
                <div style="font-size: 17px; margin-bottom: 4px;">SOLICITUD DE ELABORACION DE CONTRATO DE ADQUISICION</div>
                <div style="font-size: 12px;">DIRECCION GENERAL JURIDICA</div>
                <div style="font-size: 11px; margin-top: 2px;">DGJ-3</div>
            </td>
        </tr>
    </table>

    <table class="mt">
        <tr><td colspan="2" class="section-title">INFORMACION DEL AREA SOLICITANTE</td></tr>
        <tr>
            <td class="label">Area Solicitante:</td>
            <td class="value"><?= esc($solicitud->responsable_proyecto_nombre ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Fecha de solicitud:</td>
            <td class="value"><?= !empty($solicitud->fecha_solicitud) ? date('d/m/Y', strtotime($solicitud->fecha_solicitud)) : '' ?></td>
        </tr>
    </table>

    <table class="mt">
        <tr><td colspan="2" class="section-title">INFORMACION DEL CONTRATO</td></tr>
        <tr>
            <td class="label">Responsable del seguimiento (SECTURI):</td>
            <td class="value"><?= esc($solicitud->responsable_seguimiento_nombre ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Vigencia:</td>
            <td class="value"><?= esc($solicitud->vigencia ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Objeto de Adquisicion:</td>
            <td class="value"><?= nl2br(esc($solicitud->objeto_adquisicion ?? '')) ?></td>
        </tr>
    </table>

    <table class="mt">
        <tr><td colspan="2" class="section-title">PROCESO DE CONTRATACION</td></tr>
        <tr>
            <td class="label">Tipo de proceso:</td>
            <td class="value"><?= esc($solicitud->tipo_proceso ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">No. de invitacion:</td>
            <td class="value"><?= esc($solicitud->no_invitacion ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Fecha de invitacion:</td>
            <td class="value"><?= !empty($solicitud->fecha_invitacion) ? date('d/m/Y', strtotime($solicitud->fecha_invitacion)) : '' ?></td>
        </tr>
    </table>

    <table class="mt">
        <tr><td colspan="4" class="section-title">INFORMACION PRESUPUESTAL</td></tr>
        <tr>
            <th>Codigo Programatico</th>
            <th>Fondo</th>
            <th>Numero de Partida</th>
            <th>Nombre de la Partida</th>
        </tr>
        <tr>
            <td><?= esc($solicitud->codigo_programatico ?? '') ?></td>
            <td><?= esc($solicitud->fondo ?? '') ?></td>
            <td><?= esc($solicitud->numero_partida ?? '') ?></td>
            <td><?= esc($solicitud->nombre_partida ?? '') ?></td>
        </tr>
    </table>

    <table class="mt">
        <tr><td colspan="3" class="section-title">PAGOS</td></tr>
        <tr>
            <th style="width: 10%;">No.</th>
            <th style="width: 20%;">Monto</th>
            <th>Letra</th>
        </tr>
        <?php if (!empty($pagos)): ?>
            <?php foreach ($pagos as $pago): ?>
                <tr>
                    <td class="center"><?= esc($pago->numero_pago ?? '') ?></td>
                    <td class="right">$<?= number_format((float) str_replace([',', '$', ' '], '', (string) ($pago->monto ?? 0)), 2) ?></td>
                    <td><?= esc($pago->monto_letra ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="center">No hay pagos registrados</td>
            </tr>
        <?php endif; ?>
    </table>

    <table class="mt">
        <tr><td colspan="3" class="section-title">GARANTIA</td></tr>
        <tr>
            <th>Tipo de Garantia</th>
            <th>Monto de Garantia</th>
            <th>Monto en letra</th>
        </tr>
        <tr>
            <td><?= esc($solicitud->garantia ?? '') ?></td>
            <td class="right"><?= !empty($solicitud->monto_garantia) ? '$' . number_format((float) str_replace([',', '$', ' '], '', (string) $solicitud->monto_garantia), 2) : '' ?></td>
            <td><?= esc($solicitud->texto_monto_garantia ?? '') ?></td>
        </tr>
    </table>

    <table class="mt">
        <tr><td colspan="2" class="section-title">INFORMACION DEL PROVEEDOR</td></tr>
        <tr>
            <td class="label">Nombre/Razon Social:</td>
            <td class="value"><?= esc($solicitud->proveedor_nombre ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Nombre Comercial:</td>
            <td class="value"><?= esc($solicitud->proveedor_comercial ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Num. de Registro de Padron de Proveedores:</td>
            <td class="value"><?= esc($solicitud->proveedor_cedula ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Domicilio fiscal:</td>
            <td class="value"><?= esc($solicitud->proveedor_domicilio ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">RFC:</td>
            <td class="value"><?= esc($solicitud->proveedor_rfc ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Nombre del Representante Legal:</td>
            <td class="value"><?= esc($solicitud->proveedor_representante ?? '') ?></td>
        </tr>
        <tr>
            <td class="label">Responsable de Seguimiento:</td>
            <td class="value"><?= esc($solicitud->proveedor_seguimiento ?? '') ?></td>
        </tr>
    </table>

    <table class="mt">
        <tr><td class="section-title">DOCUMENTOS Y ANEXOS</td></tr>
        <tr><td class="center">SOPORTE DOCUMENTAL SE RELACIONA EN DOCUMENTO ANEXO</td></tr>
    </table>

    <table class="mt">
        <tr><td class="section-title">VALIDACION DE SOLICITUD</td></tr>
        <tr>
            <td style="height: 92px;">
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
