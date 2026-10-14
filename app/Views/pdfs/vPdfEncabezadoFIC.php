<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 9pt; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: middle; }
        .header { background: #000; color: #fff; text-align: center; font-weight: bold; font-size: 12pt; padding: 8px; }
        .label { background: #d0d0d0; width: 30%; font-weight: bold; font-size: 9pt; white-space: nowrap; }
        .value { width: 70%; font-size: 9pt; }
    </style>
</head>
<body>
    <?php
        $conceptoActual = $concepto_fic ?? ($conceptos_fic[0] ?? (object) []);
        $importe = (float) ($conceptoActual->importe ?? 0);
        $partidaActual = trim((string) ($conceptoActual->partida ?? ''));
        $reservaActual = trim((string) ($pago_fic->reserva ?? ''));
        $esHospedaje = $partidaActual === '5413302' || $reservaActual === '5413302';
        $descripcionConcepto = $esHospedaje
            ? 'Servicio de hospedaje durante la edición 54 del FIC del 17 de septiembre al 09 de octubre de 2026'
            : 'Consumo de alimentos durante la edición 54 del FIC del 17 de septiembre al 09 de octubre de 2026';
    ?>
    <table>
        <thead><tr><th colspan="2" class="header">ENCABEZADO DE FACTURA</th></tr></thead>
        <tbody>
            <tr><td class="label">RESPONSABLE / CARGO / ÁREA:</td><td class="value">MTRO. DAVID AYALA SAUCEDO - SUBSECRETARIO DE IDENTIDAD Y DESARROLLO TURÍSTICO</td></tr>
            <tr><td class="label">COMISION / REUNION / EVENTO:</td><td class="value"><?= esc($descripcionConcepto) ?></td></tr>
            <tr><td class="label">CONCEPTO DEL PAGO:</td><td class="value"><?= esc($descripcionConcepto) ?></td></tr>
            <tr><td class="label">PARTIDA:</td><td class="value"><?= esc($conceptoActual->partida ?? '') ?><?= !empty($conceptoActual->descripcion_partida) ? ' - ' . esc($conceptoActual->descripcion_partida) : '' ?></td></tr>
            <tr><td class="label">FACTURA / RECIBO No.:</td><td class="value"><?= esc($conceptoActual->comprobante ?? '') ?></td></tr>
            <tr><td class="label">FECHA DEL GASTO:</td><td class="value">del 17 de septiembre al 09 de octubre de 2026</td></tr>
            <tr>
                <td class="label">IMPORTE EN PESOS (MXN):</td>
                <td class="value">$<?= number_format($importe, 2) ?> M.N.</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
