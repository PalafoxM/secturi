<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: middle; }
        .header { background: #000; color: #fff; text-align: center; font-weight: bold; font-size: 12pt; }
        .label { background: #d9d9d9; width: 31%; font-weight: bold; font-size: 9pt; }
        .value { width: 69%; font-size: 9pt; }
    </style>
</head>
<body>
    <?php
        $importe = (float) str_replace(',', '', (string) ($pago_fic->importe ?? 0));
        $proveedor = $establecimiento->razon_social ?? $establecimiento->dsc_establecimiento ?? '';
        $responsable = implode(' / ', array_filter([
            $usuario_registro->nombre_completo ?? '',
            $usuario_registro->dsc_puesto ?? '',
            $usuario_registro->dsc_area ?? '',
        ]));
    ?>
    <table>
        <thead><tr><th colspan="2" class="header">ENCABEZADO DE COMPROBANTE FIC</th></tr></thead>
        <tbody>
            <tr><td class="label">RESPONSABLE / CARGO / ÁREA:</td><td class="value"><?= esc(mb_strtoupper($responsable, 'UTF-8')) ?></td></tr>
            <tr><td class="label">PROVEEDOR:</td><td class="value"><?= esc(mb_strtoupper($proveedor, 'UTF-8')) ?></td></tr>
            <tr><td class="label">RFC:</td><td class="value"><?= esc($establecimiento->rfc ?? '') ?></td></tr>
            <tr><td class="label">CONTRATO / CONVENIO:</td><td class="value"><?= esc($establecimiento->no_contrato ?? 'NO APLICA') ?></td></tr>
            <tr><td class="label">PROYECTO:</td><td class="value"><?= esc($proyecto->proyecto ?? 'E027QC04182601') ?></td></tr>
            <tr><td class="label">PARTIDA:</td><td class="value"><?= esc($pago_fic->partida ?? '') ?><?= !empty($partida->nombre_fondo) ? ' - ' . esc($partida->nombre_fondo) : '' ?></td></tr>
            <tr><td class="label">FACTURA / RECIBO No.:</td><td class="value"><?= esc($pago_fic->comprobante ?? '') ?></td></tr>
            <tr><td class="label">FECHA DEL TRÁMITE:</td><td class="value"><?= !empty($pago_fic->fec_tramite) ? date('d/m/Y', strtotime($pago_fic->fec_tramite)) : '' ?></td></tr>
            <tr><td class="label">RESERVA:</td><td class="value"><?= esc($pago_fic->reserva ?? '') ?></td></tr>
            <tr>
                <td class="label">IMPORTE EN PESOS (MXN):</td>
                <td class="value">$<?= number_format($importe, 2) ?> (<?= esc($pago_fic->importe_letra ?? '') ?>)</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
