<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11pt; color: #000; }
        .header { width: 100%; border-bottom: 0px solid #ccc; padding-bottom: 20px; }
        .logo { width: 220px; } 
        .title { text-align: right; font-weight: bold; margin-top: 20px; margin-bottom: 10px; font-size: 12pt; }
        .date, .folio { text-align: right; font-weight: bold; font-size: 10pt; margin-bottom: 5px; }
        .addressee { font-weight: bold; margin-top: 30px; margin-bottom: 20px; line-height: 1.5; font-size: 11pt; text-transform: uppercase;}
        .body-text { margin-top: 10px; text-align: justify; line-height: 1.6; margin-bottom: 15px; }
        .footer { width: 100%; margin-top: 80px; text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 60%; margin: 0 auto; margin-top: 60px; }
        .signature-name { font-weight: bold; margin-top: 5px; text-transform: uppercase; }
        .signature-title { font-weight: bold; font-size: 10pt; text-transform: uppercase; }
        .atentamente { font-weight: bold; margin-bottom: 60px; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="header">
        <img src="<?= $logo ?>" class="logo">
    </div>

    <div class="title">Oficio de Liberación de Pago</div>

     <div class="date">
        Silao de la Victoria, Gto, <?= date('d', strtotime($registro_pt->fecha_tramite)) ?> de <?= $meses[date('n', strtotime($registro_pt->fecha_tramite))-1] ?> de <?= date('Y', strtotime($registro_pt->fecha_tramite)) ?>
    </div>
    
    <div class="folio">
        FOLIO: <?= isset($registro_pt->no_consecutivo) ? $registro_pt->no_consecutivo : '000' ?>
    </div>

    <div class="addressee">
        RODRIGO GONZÁLEZ GUERRERO<br>
        DIRECTOR GENERAL ADMINISTRATIVO<br>
        PRESENTE
    </div>

    <div class="body-text">
        Por medio de la presente, me permito solicitar su apoyo para que se realice el tramite de Gasto de Operación 
        con folio <strong><?= isset($registro_pt->no_consecutivo) ? $registro_pt->no_consecutivo : '000' ?></strong> 
        por la cantidad de <strong><?= $registro_pt->importe_total_num ?> (<?= $registro_pt->importe_letra ?? 'CERO PESOS 00/100 M.N.' ?>)</strong>,
        por concepto de <?= isset($registro_pt->concepto) ? $registro_pt->concepto : 'Concepto no especificado' ?>.
    </div>

    <div class="body-text">
        Manifiesto que el gasto se realizó para el cumplimiento de la comisión asignada, quedando bajo mi 
        responsabilidad la veracidad de la misma en atención a lo establecido en las Disposiciones Administrativas 
        Vigentes, así mismo queda bajo mi resguardo y custodia los expedientes originales con los entregables 
        correspondientes en caso de cualquier proceso o revisión de auditoría.
    </div>

    <div class="body-text">
        Sin otro particular por el momento, aprovecho la ocasión para enviarle un cordial saludo.
    </div>

    <div class="footer">
        <div class="atentamente">ATENTAMENTE</div>
        
        <div class="signature-line"></div>
        <div class="signature-name">
            <?= (isset($registro_pt->nombre_responsable_2) && $registro_pt->nombre_responsable_2 != 'NO APLICA') ? $registro_pt->nombre_responsable_2 : 'NOMBRE DEL RESPONSABLE' ?>
        </div>
        <div class="signature-title">
            <?= (isset($registro_pt->cargo_responsable_2) && $registro_pt->cargo_responsable_2 != 'NO APLICA') ? $registro_pt->cargo_responsable_2 : 'CARGO DEL RESPONSABLE' ?>
        </div>
    </div>

</body>
</html>
