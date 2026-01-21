<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        th { background-color: #213E66; color: white; text-align: center;}
        .text-right { text-align: right; }
        .header-info { margin-bottom: 20px; }
        .text-align-center { text-align: center; }
        .color-red { color: red; }
    </style>
</head>
<body>

 <img src="<?= base_url() ?>assets/logo-guanajuato.png" alt="Logo" style="width: 300px;">
<div class="header-info">
    <p><strong>FOLIO: </strong> <?= isset($folio) ? $folio : 'GO SECTURI/SSITR/DGA/001/2026' ?> </p>
</div>
    <table>
        <thead>
            <tr>
                <td colspan="4" class="section-title" style="text-align: center; font-weight: bold; border: none; font-size: 12pt;">DESGLOSE DE GASTOS (VIÁTICOS POR PERSONA)</td>
            </tr>
            <tr class="header-row">
                <th style="width: 35%;">NOMBRE</th>
                <th style="width: 25%;">RFC</th>
                <th style="width: 25%;">TOTAL GASTO EN VIATICOS (3760-3750)</th>
                <th style="width: 15%;">FIRMA</th>
            </tr>
        </thead>
            <tbody>
        <?php 
        $totalComprobado = 0;
        if (isset($viaticos) && !empty($viaticos)): ?>
            <?php foreach ($viaticos as $v): 
                $totalComprobado += $v->importe;
            ?>
            <tr>
                <td class="text-align-center"><?= $v->nombre ?></td>
                <td class="text-align-center"><?= $v->rfc ?></td>
                <td class="text-right">$<?= number_format($v->importe, 2) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" class="text-right"><strong>TOTAL COMPROBADO:</strong></td>
                <td colspan="2" class="text-right"><strong>$<?= number_format($totalComprobado, 2) ?></strong></td>
            </tr>
            <tr>
                <?php $fn = new \App\Libraries\Funciones(); ?>
                <td colspan="4" style="background-color: #213E66; color:white; text-align: right;"><strong>(<?= $fn->numeroALetras($totalComprobado) ?>)</strong></td>
            </tr>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center">No hay comprobantes registrados</td></tr>
        <?php endif; ?>
    </tbody>
    </table>
</body>
</html>

