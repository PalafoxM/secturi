<?php
// Layout similar to format but showcasing the expenses
?>
<style>
    body { font-family: Arial, sans-serif; font-size: 10pt; }
    h3 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid black; padding: 5px; text-align: left; }
    th { background-color: #f2f2f2; }
    .text-right { text-align: right; }
    .header-info { margin-bottom: 20px; }
    .text-align-center { text-align: center; }
    .color-red { color: red; }
</style>


 <img src="<?= base_url() ?>assets/logo-guanajuato.png" alt="Logo" style="width: 300px;">
<div class="header-info">
    <p><strong>FOLIO: </strong> $<?= number_format($solicitud->cantidad, 2) ?></p>
</div>

<h4 class="text-align-center">DESGLOSE DE GASTOS (VIATICOS POR PERSONA)</h4>
<table>
    <thead>
        <tr>
            <th style="width: 25%; text-align: center;">NOMBRE</th>
            <th style="width: 25%; text-align: center;">RFC</th>
            <th style="width: 25%; text-align: center;" class="text-right">TOTAL GASTO EN VIATICOS</th>
            <th style="width: 25%; text-align: center;">FIRMA</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $totalComprobado = 0;
        if (isset($comprobaciones) && !empty($comprobaciones)): ?>
            <?php foreach ($comprobaciones as $comp): 
                $totalComprobado += $comp->importe;
            ?>
            <tr>
                <td class="color-red text-align-center"><?= $comp->nombre_emisor ?></td>
                <td class="text-align-center"><?= $comp->rfc ?></td>
                <td class="text-right">$<?= number_format($comp->importe, 2) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" class="text-right"><strong>TOTAL COMPROBADO:</strong></td>
                <td colspan="2" class="text-right"><strong>$<?= number_format($totalComprobado, 2) ?></strong></td>
            </tr>
        <?php else: ?>
            <tr><td colspan="3" style="text-align:center">No hay comprobantes registrados</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="footer" style="margin-top: 50px;">
    <p>Fecha de Impresión: <?= date('d/m/Y H:i:s') ?></p>
</div>
