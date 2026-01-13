
    <style>
        .cheque {position: absolute; top: 280px; left: 170px; }
        .cantidad {position: absolute; top: 310px; left: 210px; }
        .evento {position: absolute; top: 350px; left: 230px; }
        .lugar {position: absolute; top: 370px; left: 180px; }
        .duracion { position: absolute; top: 400px; left: 180px; }
        .clave { position: absolute; top: 595px; left: 480px; }
        .responsable { position: absolute; top: 615px; left: 430px; }
        .label { font-weight: bold; width: 150px; display: inline-block; }
        .value { border-bottom: 1px solid #ccc; display: inline-block; width: 500px; }
        table { width: 95%; border-collapse: collapse; margin-top: 20px; }
        .detalle { position: absolute; top: 735px; left: 85px; width: 730px; height: 250px; overflow: hidden; } 
        th, td { border: 1px solid black; padding: 2px; text-align: left; height: 16px; font-size: 9px; line-height: 10px; overflow: hidden; }
        th { background-color: #f2f2f2; height: 18px; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; text-align: center; }
        .fecha-pie { position: absolute; top: 950px; left: 120px; font-weight: bold; width: 300px; background-color: white; }

        .firma-box { width: 45%; display: inline-block; text-align: center; margin-top: 50px; }
        .linea-firma { border-top: 1px solid black; width: 80%; margin: 10px auto; }
    </style>



    <div class="cheque">
        <span class="value"><strong><?= isset($solicitud->cheque_favor_nombre) ? $solicitud->cheque_favor_nombre.' / '.$solicitud->dsc_area : '' ?></strong></span>
    </div>
    <div class="cantidad">
        <span class="value">$<?= isset($solicitud->cantidad) ? number_format($solicitud->cantidad, 2) : '0.00' ?> (<?= isset($cantidad_letra) ? $cantidad_letra : '' ?>)</span>
    </div>
    <div class="evento">
        <span class="value"><?= isset($solicitud->nombre_evento) ? $solicitud->nombre_evento : '' ?></span>
    </div>
    <div class="lugar">
        <span class="value"><?= isset($solicitud->lugar) ? $solicitud->lugar : '' ?></span>
    </div>
    <div class="duracion">
        <span class="value">Del <?= isset($solicitud->fecha_inicio) ? date('d/m/Y', strtotime($solicitud->fecha_inicio)) : '' ?> al <?= isset($solicitud->fecha_fin) ? date('d/m/Y', strtotime($solicitud->fecha_fin)) : '' ?></span>
    </div>
    <div class="clave">
        <span class="value"><?= isset($solicitud->clave) ? $solicitud->clave : '' ?></span>
    </div>
    <div class="responsable">
        <span class="value"><?= isset($solicitud->nombre_completo) ? $solicitud->nombre_completo : '' ?></span>
    </div>
    <div class="detalle">
    <table>
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>importe</th>
                    <th class="text-right">Clave presupuestaria</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalGeneral = 0;
                if (isset($detalles) && !empty($detalles)): ?>
                    <?php foreach ($detalles as $det): 
                        $totalGeneral += $det->importe;
                    ?>
                    <tr>
                        <td><?= $det->cuenta_cable .' '. $det->nombre_fondo?></td>
                        <td class="text-right">$<?= number_format($det->importe, 2) ?></td>
                        <td><?= $det->proyecto ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- Fila de Total -->
                     <tr>
                        <td style="text-align: right; font-weight: bold;">TOTAL:</td>
                        <td class="text-right" style="font-weight: bold;">$<?= number_format($totalGeneral, 2) ?></td>
                        <td></td>
                    </tr>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align:center">No hay detalles registrados</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="fecha-pie">
        <?= isset($fecha_texto) ? $fecha_texto : '' ?>
    </div>
