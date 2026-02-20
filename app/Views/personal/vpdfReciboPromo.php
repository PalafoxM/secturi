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
                page-break-after: always;
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
            <img src="<?= $membrete ?>" style="position:absolute; top:0; left:0; width:100%;">
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

                    $dia  = date('d');
                    $mes  = $meses[date('m')];
                    $anio = date('Y');
                ?>

                <div style="text-align:right;">
                    Recibo/<?= $folio ?><br>
                    Dirección de Promoción y Difusión<br>
                    Silao, Gto. <?= $dia ?> de <?= $mes ?> del <?= $anio ?>
                </div>

                <br>

                <table>
                    <tr>
                        <td class="bold">Acción</td>
                        <td><?= $concepto ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Contrato</td>
                        <td><?= $concepto ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Cantidad</td>
                        <td><?= $cantidad ?></td>
                    </tr>
                </table>

                <br>

                <div class="center bold">Recibe Material</div>
                <br>

                Nombre completo: <?= $nombre_solicitante ?><br>
                Fecha: <?= date('d/m/Y') ?><br><br>

                ___________________________<br>
                Firma

                <div class="footer-text">
                    Hago de su conocimiento que he recibido a entera satisfacción y todo se encuentra bajo mi resguardo, 
                    así mismo le comento que el uso y distribución que se dará de los artículos y materiales recibidos quedan
                    bajo mi responsabilidad; así como contar con toda la evidencia y soporte documental de la entrega de los mismos.
                </div>

            </div>
        </div>

        <!-- ================== PAGE 2 ================== -->
        <div class="page">
            <img src="<?= $membrete ?>" style="position:absolute; top:0; left:0; width:100%;">
            <div class="content">

                <table>
                    <tr>
                        <td class="bold">Acción</td>
                        <td><?= $nombre_solicitante ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Nombre</td>
                        <td><?= $nombre_solicitante ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Cargo</td>
                        <td><?= $puesto ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Teléfono</td>
                        <td><?= $telefono ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Correo</td>
                        <td><?= $correo ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Fecha del evento</td>
                        <td><?= $fec_eve ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Lugar de entrega</td>
                        <td><?= $lugar ?></td>
                    </tr>
                </table>

                <br>

                <div class="center bold">Envío de fotografías</div>

                <br>
                INE ☐<br>
                Credencial de Servidor Público ☐<br><br>

                <div class="center bold">
                    Nombre completo: <?= $nombre_solicitante ?><br>
                    Fecha: <?= date('d/m/Y') ?><br><br>
                
                    ___________________________<br>
                    Firma
                
                    Me comprometo a entregar evidencia fotográfica de la entrega <br> <!--bold-->
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
