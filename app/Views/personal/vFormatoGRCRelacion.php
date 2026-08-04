<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 4mm; }
        body { margin: 0; font-family: Arial, sans-serif; font-size: 6.4pt; color: #000; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 2px 3px; vertical-align: middle; }
        .page { border: 1px solid #000; min-height: 255mm; }
        .border { border: 1px solid #000; }
        .border-x { border-left: 1px solid #000; border-right: 1px solid #000; }
        .border-bottom { border-bottom: 1px solid #000; }
        .no-border { border: 0 !important; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .section-title { border: 1px solid #000; text-align: center; font-size: 6pt; padding: 2px; }
        .small { font-size: 5.5pt; }
        .tiny { font-size: 4.8pt; }
        .signature-name { font-size: 5.2pt; font-weight: bold; text-transform: uppercase; }
        .signature-role { font-size: 4.4pt; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
<?php
    $folio = trim((string) ($solicitud->no_consecutivo ?? ''));
    if ($folio !== '' && stripos($folio, 'GRC') !== 0) {
        $folio = 'GRC ' . $folio;
    }
    $fechaDocumento = !empty($solicitud->fec_reg) ? date('d/m/Y', strtotime($solicitud->fec_reg)) : date('d/m/Y');
    $fechaInicio = !empty($solicitud->fecha_inicio) ? date('d/m/Y', strtotime($solicitud->fecha_inicio)) : '';
    $fechaFin = !empty($solicitud->fecha_fin) ? date('d/m/Y', strtotime($solicitud->fecha_fin)) : '';
    $responsableNombre = $responsable->nombre_completo ?? ($solicitud->nombre_completo ?? '');
    $responsablePuesto = $responsable->dsc_puesto ?? '';
    $responsableArea = $responsable->dsc_area ?? ($solicitud->dsc_area ?? '');
    $secretarioNombre = $secretario->dsc_secretario ?? '';
    $secretarioPuesto = $secretario->dsc_puesto ?? 'SECRETARÍA DE TURISMO E IDENTIDAD';
    $firmanteUno = $jefe_responsable ?? $responsable;
    $firmanteDos = $superior_responsable ?? null;
?>
<div class="page">
    <table style="height: 27mm;">
        <tr>
            <td width="36%" class="center no-border" style="padding: 3mm 2mm 1mm;">
                <img src="<?= esc($logo) ?>" style="width: 47mm; height: auto;">
            </td>
            <td width="46%" class="center bold no-border" style="font-size: 7pt; padding-top: 2mm;">
                GOBIERNO DEL ESTADO DE GUANAJUATO<br>
                FORMATO DE GASTO DE RESERVA POR COMPROBAR
            </td>
            <td width="18%" class="right tiny no-border" style="vertical-align: top; padding: 1mm 2mm;">FORMATO GRC - 25</td>
        </tr>
    </table>

    <table style="height: 18mm;">
        <tr>
            <td width="15%" class="no-border"></td>
            <td width="49%" class="no-border" style="vertical-align: top; padding-top: 2mm;">
                Relación de <span class="bold center" style="display: inline-block; width: 13mm; border-bottom: 1px solid #000;"><?= (int) $numero_documentos ?></span>
                documentos que amparan un importe de<br>
                que se envían para su revisión y trámite de pago
            </td>
            <td width="36%" class="right bold no-border" style="vertical-align: top; padding: 2mm 4mm 0 0;">
                <span style="display: inline-block; min-width: 40mm; border-bottom: 1px solid #000; padding-bottom: 1mm;">$<?= number_format($cantidad, 2) ?></span>
            </td>
        </tr>
    </table>

    <table>
        <tr><td width="32%" rowspan="4" class="no-border"></td><td colspan="3" class="border center small">RAMO O ENTIDAD REMITENTE</td></tr>
        <tr><td colspan="3" class="border center bold">21 SECRETARÍA DE TURISMO E IDENTIDAD</td></tr>
        <tr>
            <td width="20%" class="border center bold">DIVISIÓN</td>
            <td width="20%" class="border center bold">FECHA</td>
            <td width="28%" class="border center bold">FOLIO</td>
        </tr>
        <tr>
            <td class="border center">21</td>
            <td class="border center"><?= esc($fechaDocumento) ?></td>
            <td class="border center bold"><?= esc($folio) ?></td>
        </tr>
    </table>

    <div class="section-title" style="margin-top: 1mm;">DATOS PROPORCIONADOS POR LA DEPENDENCIA</div>
    <div class="section-title" style="border-top: 0;">REFERENCIA AL DOCUMENTO</div>

    <table>
        <thead>
            <tr>
                <th width="15%" class="border center">COMPROBANTE No.</th>
                <th width="10%" class="border center">PARTIDA No.</th>
                <th width="19%" class="border center">IMPORTE</th>
                <th width="56%" class="border center">OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody>
            <tr style="height: 112mm;">
                <td class="border center bold" style="vertical-align: top; padding-top: 5mm;">RELACIÓN DE<br>COMPROBANTES</td>
                <td class="border center bold" style="vertical-align: top; padding-top: 5mm;"><?= esc($partida_texto) ?></td>
                <td class="border right bold" style="vertical-align: top; padding-top: 5mm;">$&nbsp;&nbsp;<?= number_format($cantidad, 2) ?></td>
                <td class="border" style="vertical-align: top; padding: 4mm 3mm; line-height: 1.35;">
                    <div class="bold">RESPONSABLE DEL GASTO A RESERVA DE COMPROBAR:</div>
                    <div style="margin: 1mm 0 2mm;"><?= esc($responsableNombre) ?></div>
                    <div class="bold">CARGO / ÁREA DE ASIGNACIÓN:</div>
                    <div style="margin: 1mm 0 3mm;">
                        <?= esc($responsablePuesto) ?><?= $responsablePuesto !== '' && $responsableArea !== '' ? '<br>' : '' ?><?= esc($responsableArea) ?>
                    </div>
                    <div class="bold">EVENTO / COMISIÓN / PROGRAMA:</div>
                    <div style="margin: 1mm 0 3mm; text-align: justify;"><?= esc($solicitud->nombre_evento ?? '') ?></div>
                    <div class="bold">CONCEPTO DEL GASTO:</div>
                    <div style="margin: 1mm 0 4mm; text-align: justify;">Gastos relacionados con <?= esc($solicitud->nombre_evento ?? 'la comisión registrada') ?>.</div>
                    <div class="bold">LUGAR Y PERIODO DE LA COMISIÓN:</div>
                    <div style="margin: 1mm 0 4mm; text-align: justify;">
                        <?= esc($solicitud->lugar ?? '') ?><br>
                        <?php if ($fechaInicio !== '' || $fechaFin !== ''): ?>Del <?= esc($fechaInicio) ?> al <?= esc($fechaFin) ?><?php endif; ?>
                    </div>
                    <div class="bold">SE REINTEGRA LA CANTIDAD DE:</div>
                    <table style="margin-top: 2mm;">
                        <tr>
                            <td width="38%" class="no-border bold">$&nbsp;&nbsp;<?= number_format($reintegro, 2) ?></td>
                            <td width="62%" class="no-border tiny uppercase"><?= esc($reintegro_letra) ?></td>
                        </tr>
                    </table>
                    <div style="line-height: 6mm;">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="border center bold">RESERVA No.</td>
                <td class="border right bold">$&nbsp;&nbsp;<?= number_format($cantidad, 2) ?></td>
                <td class="border center tiny uppercase"><?= esc($cantidad_letra) ?></td>
            </tr>
        </tfoot>
    </table>

    <table style="height: 61mm;">
        <tr style="height: 8mm;">
            <td width="32%" class="border center">ELABORA<br>DIRECTOR GENERAL ADMINISTRATIVO</td>
            <td width="41%" class="border center">AUTORIZA</td>
            <td width="27%" class="border center">RESPONSABLE DEL PROYECTO</td>
        </tr>
        <tr style="height: 25mm;">
            <td class="border center" style="vertical-align: bottom; padding-bottom: 3mm;">
                <div style="line-height: 5mm;">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</div>
                <div class="signature-name">L.R.I. Rodrigo González Guerrero</div>
            </td>
            <td class="border center" style="vertical-align: bottom; padding-bottom: 3mm;">
                <div style="line-height: 5mm;">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</div>
                <div class="signature-name"><?= esc($secretarioNombre) ?></div>
                <div class="signature-role"><?= esc($secretarioPuesto) ?></div>
            </td>
            <td class="border center" style="vertical-align: bottom; padding-bottom: 3mm;">
                <div style="line-height: 5mm;">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</div>
                <?php if ($firmanteUno): ?>
                    <div class="signature-name"><?= esc($firmanteUno->nombre_completo ?? '') ?></div>
                    <div class="signature-role"><?= esc($firmanteUno->dsc_puesto ?? '') ?></div>
                <?php endif; ?>
            </td>
        </tr>
        <tr style="height: 8mm;">
            <td class="border"></td>
            <td class="border"></td>
            <td class="border center">RESPONSABLE DEL PROYECTO</td>
        </tr>
        <tr style="height: 20mm;">
            <td class="border"><div style="line-height: 5mm;">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</div></td>
            <td class="border"><div style="line-height: 5mm;">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</div></td>
            <td class="border center" style="vertical-align: bottom; padding-bottom: 2mm;">
                <div style="line-height: 5mm;">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</div>
                <?php if ($firmanteDos): ?>
                    <div class="signature-name"><?= esc($firmanteDos->nombre_completo == 'MARÍA GUADALUPE ROBLES LEÓN' ? $firmanteUno->nombre_completo : '') ?></div>
                    <div class="signature-role"><?= esc($firmanteDos->dsc_puesto == 'SECRETARIO/A DE TURISMO E IDENTIDAD' ?  $firmanteUno->dsc_puesto : '') ?></div>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
