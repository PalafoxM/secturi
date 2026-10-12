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
        <thead><tr><th colspan="2" class="header">ENCABEZADO DE FACTURA</th></tr></thead>
        <tbody>
            <tr><td class="label">RESPONSABLE / CARGO / ÁREA:</td><td class="value">MTRO. DAVID AYALA SAUCEDO - SUBSECRETARIO DE IDENTIDAD Y DESARROLLO TURÍSTICO</td></tr>
            <tr><td class="label">COMISION / REUNION / EVENTO:</td><td class="value">Consumo de alimentos durante la edición 54 del FIC del 17 de Sep al 19 Oct. 2026</td></tr>
            <tr><td class="label">CONCEPTO DEL PAGO:</td><td class="value">Consumo de alimentos durante la edición 54 del FIC del 17 de Sep al 19 Oct. 2026</td></tr>
            <tr><td class="label">PARTIDA:</td><td class="value"><?= esc($pago_fic->partida ?? '') ?><?= !empty($partida->nombre_fondo) ? ' - ' . esc($partida->nombre_fondo) : '' ?></td></tr>
            <tr><td class="label">FACTURA / RECIBO No.:</td><td class="value"><?= esc($pago_fic->comprobante ?? '') ?></td></tr>
            <tr><td class="label">FECHA DEL TRÁMITE:</td><td class="value">del 17 de septiembre al 19 de octubre de 2026</td></tr>
            <tr>
                <td class="label">IMPORTE EN PESOS (MXN):</td>
                <td class="value">$<?= number_format($importe, 2) ?> (<?= esc($pago_fic->importe_letra ?? '') ?>)</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
