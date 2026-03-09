<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
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
            vertical-align: middle;
        }
        .header-bg {
            background-color: #000;
            color: #fff;
            text-align: center;
            font-weight: bold;
            padding: 8px;
            font-size: 12pt;
        }
        .label-cell {
            background-color: #ccc;
            font-weight: bold;
            width: 30%;
            font-size: 9pt;
        }
        .value-cell {
            width: 70%;
            font-size: 9pt;
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
                    <?= !empty($registro_pt->nombre_responsable) ? mb_strtoupper($registro_pt->nombre_responsable, 'UTF-8') : '' ?>
                </td>
            </tr>
            <tr>
                <td class="label-cell">COMISION / REUNION / EVENTO:</td>
                <td class="value-cell"><?= isset($row->comision) ? mb_strtoupper($row->comision, 'UTF-8') : '' ?></td>
            </tr>
            <tr>
                <td class="label-cell">CONCEPTO DEL PAGO:</td>
                <td class="value-cell"><?= isset($row->concepto_gasto) ? mb_strtoupper($row->concepto_gasto, 'UTF-8') : 'PEAJES' ?></td>
            </tr>
            <tr>
                <td class="label-cell">PARTIDA:</td>
                <td class="value-cell"><?= isset($row->partida) ? $row->partida : '' ?> - <?= isset($row->dsc_partida) ? $row->dsc_partida : '' ?></td>
            </tr>
            <tr>
                <td class="label-cell">FACTURA / RECIBO No:</td>
                <td class="value-cell"><?= isset($row->no_comprobante) ? $row->no_comprobante : '' ?></td>
            </tr>
            <tr>
                <td class="label-cell">FECHA DEL GASTO:</td>
                <td class="value-cell">
                    <?php 
                        $meses = array("01","02","03","04","05","06","07","08","09","10","11","12");
                        
                        if(isset($registro_pt->tipo_formato) && $registro_pt->tipo_formato == 'REFRENDO' && isset($row->fechas) && strpos($row->fechas, ' / ') !== false){
                            list($inicio, $fin) = explode(' / ', $row->fechas);
                            echo mb_strtoupper(trim($inicio), 'UTF-8') . " A " . mb_strtoupper(trim($fin), 'UTF-8');
                        } else if(isset($row->fechas) && strpos($row->fechas, ' / ') !== false){
                            list($inicio, $fin) = explode(' / ', $row->fechas);
                            $f_inicio = strtotime($inicio);
                            $f_fin = strtotime($fin);
                            if ($f_inicio !== false && $f_fin !== false) {
                                echo "DEL " . date("d", $f_inicio) . "/" . $meses[date("n", $f_inicio)-1] . "/" . date("Y", $f_inicio);
                                echo " AL " . date("d", $f_fin) . "/" . $meses[date("n", $f_fin)-1] . "/" . date("Y", $f_fin);
                            } else {
                                echo mb_strtoupper($row->fechas, 'UTF-8');
                            }
                        } else {
                             $f_tramite = strtotime($registro_pt->fecha_tramite);
                             echo "DEL " . date("d", $f_tramite) . "/" . $meses[date("n", $f_tramite)-1] . "/" . date("Y", $f_tramite);
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="label-cell">IMPORTANTE EN PESOS (MXN):</td>
                <td class="value-cell">
                    $<?= $row->importe ?> 
                    (<?= isset($row->importe_letra) ? mb_strtoupper($row->importe_letra, 'UTF-8') : '' ?>)
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
