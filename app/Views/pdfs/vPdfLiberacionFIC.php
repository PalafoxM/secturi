<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.5; color: #000; }
        .logo { width: 250px; height: 75px; background: url('<?= $logo ?>') no-repeat left center; background-size: contain; }
        .date-folio { text-align: right; font-weight: bold; margin: 25px 0 30px; }
        .recipient { font-weight: bold; margin-bottom: 25px; }
        .content { text-align: justify; margin-bottom: 18px; }
        .signature { margin-top: 60px; text-align: center; font-weight: bold; }
        .signature-line { border-top: 1px solid #000; width: 62%; margin: 10px auto; }
    </style>
</head>
<body>
    <div class="logo"></div>
    <?php
        $meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        $timestamp = strtotime((string) ($pago_fic->fec_tramite ?? date('Y-m-d')));
        $fechaTexto = 'SILAO, GTO. ' . date('d', $timestamp) . ' DE OCTUBRE DEL ' . date('Y', $timestamp);
        $importe = (float) str_replace(',', '', (string) ($pago_fic->importe ?? 0));
        $proveedor = $establecimiento->razon_social ?? $establecimiento->dsc_establecimiento ?? 'PROVEEDOR';
        $descripcionPartida = $partida->nombre_fondo ?? '';
    ?>

    <div class="date-folio">
        <div><?= esc($fechaTexto) ?></div>
        <div style="margin-top: 8px;">FOLIO <em><?= esc($pago_fic->folio ?? '') ?></em></div>
    </div>

    <div class="recipient">
        L.R.I. Rodrigo González Guerrero<br>
        Director General Administrativo<br>
        PRESENTE
    </div>

    <div class="content">
        Por medio del presente, solicito su apoyo para realizar el trámite del Pago FIC con folio
        <strong><?= esc($pago_fic->folio ?? '') ?></strong>, por la cantidad de
        <strong>$<?= number_format($importe, 2) ?> (<?= esc($pago_fic->importe_letra ?? '') ?>)</strong>,
        correspondiente al comprobante fiscal No. <strong><?= esc($pago_fic->comprobante ?? '') ?></strong>,
        por concepto de Consumo de alimentos durante la edición 54 del FIC del 17 de septiembre al 09 de octubre de 2026 al proveedor <strong><?= esc($proveedor) ?></strong>.
    </div>

    <div class="content">
        El pago se realizará con cargo al proyecto <strong><?= esc($proyecto->proyecto ?? 'E027QC04182601') ?></strong>
        y a la partida presupuestal <strong><?= esc($pago_fic->partida ?? '') ?><?= $descripcionPartida ? ' (' . esc($descripcionPartida) . ')' : '' ?></strong>,
        con reserva No. <strong><?= esc($pago_fic->reserva ?? '') ?></strong>.
    </div>

    <div class="content">
        El servicio o producto relacionado con el instrumento jurídico
        <strong><?= esc($establecimiento->no_contrato ?? 'NO APLICA') ?></strong> fue recibido de conformidad,
        quedando bajo responsabilidad del área solicitante el resguardo de la documentación comprobatoria y
        el seguimiento correspondiente.
    </div>

    <div class="content">Sin otro particular, aprovecho la ocasión para enviarle un cordial saludo.</div>

    <div class="signature">
        ATENTAMENTE<br><br><br>
        <div class="signature-line"></div>
       MTRO. DAVID AYALA SAUCEDO<br>
        <span style="font-size: 9pt; font-weight: normal;">SUBSECRETARIO DE IDENTIDAD Y DESARROLLO TURÍSTICO</span><br>
    </div>
</body>
</html>
