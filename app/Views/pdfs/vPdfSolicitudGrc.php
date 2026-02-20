<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
        }

        .container {
            padding: 80px 40px 40px 40px;
        }

        .line-text {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-height: 20px;
        }

        .text-center {
            text-align: center;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
        .mb-30 { margin-bottom: 30px; }

        table.form-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.form-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        /* Estilos de tabla para simular las líneas subrayadas (funciona mejor en mPDF) */
        .underline-cell {
            border-bottom: 1px solid #000;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="mb-20">
            Solicito a usted, la autorización para que se expida cheque a favor de:
        </div>

        <div class="text-center font-weight-bold mb-30" style="border-bottom: 2px solid #000; padding-bottom: 5px; width: 100%; display: block;">
            <?= isset($solicitante_nombre) ? $solicitante_nombre : 'José María Melgar Azanza' ?> / <span style="text-decoration: underline; color: #0000FF;"><?= isset($solicitante_puesto) ? $solicitante_puesto : 'Jefe de Atención a Grupos' ?></span>
        </div>

        <table class="form-table mb-20">
            <tr>
                <td style="width: 120px;">Por la cantidad de:</td>
                <td class="underline-cell">
                    <?= isset($cantidad_numero) ? $cantidad_numero : '$861,700.00' ?> (<?= isset($cantidad_letra) ? $cantidad_letra : 'Ochocientos sesenta y un mil setecientos pesos 00/100 M.N.' ?>)
                </td>
            </tr>
        </table>

        <table class="form-table mb-20">
            <tr>
                <td style="width: 125px;">Nombre del evento:</td>
                <td class="underline-cell" style="line-height: 1.5;">
                    <?= isset($nombre_evento) ? $nombre_evento : 'Realizar y atender varios viajes de familiarización con prensa estatal, nacional e internacional, así como agentes de viajes, bloggers, influencers, invitados especiales y/o tour operadores durante septiembre y octubre 2025.' ?>
                </td>
            </tr>
        </table>

        <table class="form-table mb-20">
            <tr>
                <td style="width: 50px;">Lugar:</td>
                <td class="underline-cell">
                    <?= isset($lugar) ? $lugar : 'Estado de Guanajuato y CDMX' ?>
                </td>
            </tr>
        </table>

        <table class="form-table mb-30">
            <tr>
                <td style="width: 70px;">Duración:</td>
                <td class="underline-cell">
                    <?= isset($duracion) ? $duracion : 'Del 25 de septiembre al 27 de octubre de 2025' ?>
                </td>
            </tr>
        </table>

        <!-- El usuario indicó que las cláusulas no son importantes 
        <div class="mb-10">
            Cláusulas:
        </div>
        -->

    </div>

</body>
</html>
