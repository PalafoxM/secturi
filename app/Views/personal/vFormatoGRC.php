<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 2px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 15px; }
        .w-100 { width: 100%; }
        
        .title {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        .number-box {
            display: inline-block;
            width: 20px;
            font-weight: bold;
            text-align: center;
        }

        .line-input {
            border-bottom: 1px solid #000;
            display: inline-block;
        }

        table.presupuesto-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 10pt;
        }
        table.presupuesto-table th, table.presupuesto-table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: left;
        }
        table.presupuesto-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        
        .firma-container {
            width: 100%;
            text-align: center;
            margin-top: 30px;
        }
        .firma-line {
            width: 400px;
            border-top: 1px solid #000;
            margin: 0 auto;
            padding-top: 2px;
            font-size: 10pt;
        }

    </style>
</head>
<body>

    <div class="text-center" style="position: absolute; top: 235px; left:0px; background-color:white; width: 100%;">
        <strong class="line-input" style="font-size: 10pt; width: 100%;">
            <?= isset($solicitud->cheque_favor_nombre) ? $solicitud->cheque_favor_nombre.' / '.$solicitud->dsc_area : '' ?>
        </strong>
    </div>
    <div style="position: absolute; top: 271px; left:203px; background-color:white; width: 65%;">
        <strong class="" style="font-size: 8pt; width: 65%;">
            $<?= isset($solicitud->cantidad) ? number_format($solicitud->cantidad, 2) : '0.00' ?> (<?= isset($solicitud->cantidad_letra) ? mb_strtoupper($solicitud->cantidad_letra, 'UTF-8') : '' ?>)
        </strong>
    </div>
    <?php 
        $nombre_evento = isset($solicitud->nombre_evento) ? $solicitud->nombre_evento : '';
        $top_val = strlen($nombre_evento) >= 200 ? '295px' : '304px';
        $height_val = strlen($nombre_evento) >= 200 ? '35px' : '20px';
    ?>
    <div style="position: absolute; top: <?= $top_val ?>; left:205px; background-color:white; width: 70%; height: <?= $height_val ?>;" >
        <strong class="" style="font-size: 8pt; width: 70%;">
            <?= $nombre_evento ?>
        </strong>
    </div>
   
   

 

</body>
</html>
