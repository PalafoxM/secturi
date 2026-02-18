<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10 pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        .header-bg {
            background-color: #000;
            color: #fff;
            text-align: center;
            font-weight: bold;
            padding: 8px;
        }
        .label-cell {
            background-color: #ccc;
            font-weight: bold;
            width: 30%;
        }
        .value-cell {
            width: 70%;
        }
    </style>
</head>
<body>
    <table style="margin-top: 10px;">
        <thead>
            <tr>
                <th colspan="2" class="header-bg">ENCABEZADO DE FACTURA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label-cell">RESPONSABLE / CARGO / AREA:</td>
                <td class="value-cell">
                    <?= !empty($registro_pt->nombre_responsable) ? strtoupper($registro_pt->nombre_responsable) : '' ?>
                    <?= !empty($registro_pt->cargo_responsable) ? ' - ' . strtoupper($registro_pt->cargo_responsable) : '' ?>
                </td>
            </tr>
            <tr>
                <td class="label-cell">COMISION / REUNION / EVENTO:</td>
                <td class="value-cell"><?= isset($registro_pt->concepto) ? strtoupper($registro_pt->concepto) : '' ?></td>
            </tr>
            <tr>
                <td class="label-cell">CONCEPTO DEL PAGO:</td>
                <td class="value-cell"><?= isset($row->comision) ? strtoupper($row->comision) : '' ?></td>
            </tr>
            <tr>
                <td class="label-cell">PARTIDA:</td>
                <td class="value-cell"><?= isset($row->partida) ? $row->partida : '' ?></td>
            </tr>
            <tr>
                <td class="label-cell">FACTURA / RECIBO No:</td>
                <td class="value-cell"><?= isset($row->no_comprobante) ? $row->no_comprobante : '' ?></td>
            </tr>
            <tr>
                <td class="label-cell">FECHA DEL GASTO:</td>
                <td class="value-cell">
                    DEL <?= date('d-m-Y', strtotime($registro_pt->fecha_tramite)) ?> AL <?= date('d-m-Y', strtotime($registro_pt->fecha_tramite)) ?>
                </td>
            </tr>
            <tr>
                <td class="label-cell">IMPORTE EN PESOS (MXN):</td>
                <td class="value-cell">
                    $<?= number_format((float)$row->importe, 2) ?> 
                    (<?= $row->importe_letra ?>)
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
