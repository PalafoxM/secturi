<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            body {
                background-position: top left;
                background-repeat: no-repeat;
            }

            @page {
                margin: 0;
            }

            body {
                margin: 0;
                font-family: Arial, sans-serif;
                font-size: 12px;
            }

            .page {
                position: relative;
                width: 100%;
                height: 100%;
                page-break-after: auto;
            }

            .background {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            .content {
                position: relative;
                padding: 120px 60px 100px 60px;
            }

            .content.page2 { 
                padding-top: 220px; 
            }

            h1 {
                text-align: center;
                font-size: 28px;
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            table, td, th {
                border: 1px solid #000;
            }

            td, th {
                padding: 6px;
            }

            .no-border {
                border: none !important;
            }

            .center {
                text-align: center;
            }

            .bold {
                font-weight: bold;
            }

            .footer-text {
                font-size: 10px;
                margin-top: 15px;
                text-align: justify;
            }
        </style>
    </head>
    <body>

        <!-- ================== PAGE 1 ================== -->
        <div class="page">
            <div class="content">

                <h1>RECIBO</h1>
                <?php
                    $meses = [
                        '01' => 'enero',
                        '02' => 'febrero',
                        '03' => 'marzo',
                        '04' => 'abril',
                        '05' => 'mayo',
                        '06' => 'junio',
                        '07' => 'julio',
                        '08' => 'agosto',
                        '09' => 'septiembre',
                        '10' => 'octubre',
                        '11' => 'noviembre',
                        '12' => 'diciembre'
                    ];

                    $fechaReciboTimestamp = !empty($fec_recibo) ? strtotime($fec_recibo) : false;
                    if (!$fechaReciboTimestamp) {
                        $fechaReciboTimestamp = time();
                    }

                    $dia  = date('d', $fechaReciboTimestamp);
                    $mes  = $meses[date('m', $fechaReciboTimestamp)];
                    $anio = date('Y', $fechaReciboTimestamp);
                ?>

                <div style="text-align:right;">
                    <h4 style="margin:0; font-weight:700;">
                        <?= $materiales ? esc(($materiales->convenio ?? '') . ('')) : 'Convenio no encontrado' ?>
                    </h4>
                    <div style="margin-top:4px;">
                        Recibo/<?= esc($folio ?? '') ?><br>
                        Dirección de Promoción y Difusión<br>
                        Silao, Gto. <?= esc($dia ?? '') ?> de <?= esc($mes ?? '') ?> del <?= esc($anio ?? '') ?>
                    </div>
                </div>

                <br>

                <table>
                    <tr>
                        <td class="bold" style="width:25%;">Concepto</td>
                        <td><?= esc($concepto ?? '') ?></td>
                    </tr>
                </table>

                <br>

                <table>
                    <tr>
                        <th class="bold" style="width:70%;">Artículo</th>
                        <th class="bold center" style="width:30%;">Cantidad</th>
                    </tr>

                    <?php if (!empty($productos) && is_array($productos)): ?>
                        <?php foreach ($productos as $p): ?>
                        <tr>
                            <td><?= esc($p->dsc_producto ?? '') ?></td>
                            <td class="center"><?= intval($p->cantidad_entregada ?? 0) ?></td>
                        </tr>
                        <?php endforeach; ?>

                        <tr>
                        <td class="bold" style="text-align:right;">TOTAL</td>
                        <td class="bold center">
                            <?= intval($total_entregado ?? $total_solicitado ?? 0) ?>
                        </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                        <td colspan="2" class="center">Sin artículos registrados</td>
                        </tr>
                    <?php endif; ?>
                </table>

                <br>

                <div class="center bold">Recibe Material</div>
                <br>

                Nombre completo: <br>
                Fecha: <br>

                Firma <br>
                ___________________________<br>
                

                <div class="footer-text">
                    Hago de su conocimiento que he recibido a entera satisfacción y todo se encuentra bajo mi resguardo, 
                    así mismo le comento que el uso y distribución que se dará de los artículos y materiales recibidos quedan
                    bajo mi responsabilidad; así como contar con toda la evidencia y soporte documental de la entrega de los mismos.
                </div>

            </div>
        </div>

        <!-- ================== PAGE 2 ================== -->
        <div class="page">
            <div class="content page2">

                <table>
                    <tr>
                        <td class="bold" style="width: 25%;">Concepto</td>
                        <td><?= $concepto ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Nombre</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="bold">Cargo</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="bold">Teléfono</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="bold">Correo</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="bold">Fecha del evento</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="bold">Lugar de distribución</td>
                        <td></td>
                    </tr>
                </table>

                <br>

                <div class="center bold">Envío de fotografías</div>

                <br>
                INE ☐<br>
                Credencial de Servidor Público ☐<br><br>

                <div class="center bold">
                    Nombre completo: <br><br><br> 
                    Fecha: <br><br><br>
                    Firma <br><br><br>
                
                    Me comprometo a entregar evidencia fotográfica de la distribución <br> <!--bold-->
                </div>

                <div class="footer-text">
                    El solicitante se compromete a realizar la entrega de evidencia fotográfica del amterial promocional
                    distribuido en el evento y actividad programada a más tardar 5 días hábiles al correo electrónico 
                    apenriquez@guanajuato.gob.mx, mamedinaher@guanajuato.gob.mx, mascencio@guanajuato.gob.mx y 
                    zulema.lira@guanajuato.gob.mx.
                </div>
            </div>
        </div>
    </body>
</html>
