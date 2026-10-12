<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 9pt; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
        .bg-black { background: #000; color: #fff; font-weight: bold; }
        .bg-grey { background: #e0e0e0; font-weight: bold; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .no-border { border: none; }
        .logo { width: 230px; height: 70px; background: url('<?= $logo ?>') no-repeat left center; background-size: contain; }
    </style>
</head>
<body>
    <table style="border: none; margin-bottom: 16px;">
        <tr>
            <td width="35%" class="no-border text-left"><div class="logo"></div></td>
            <td width="65%" class="no-border">
                <div class="font-bold" style="font-size: 12pt;">GOBIERNO DEL ESTADO DE GUANAJUATO</div>
                <div style="font-size: 10pt; margin-top: 5px;">FORMATO DE PAGO FIC</div>
            </td>
        </tr>
    </table>

    <table>
        <tr><th colspan="3" class="bg-black">RAMO O ENTIDAD REMITENTE</th></tr>
        <tr><td colspan="3" class="bg-grey">21 SECRETARÍA DE TURISMO E IDENTIDAD</td></tr>
        <tr class="bg-grey"><td>DIVISIÓN</td><td>FECHA TRÁMITE</td><td>FOLIO</td></tr>
        <tr>
            <td>21</td>
            <td><?= !empty($pago_fic->fec_tramite) ? date('d/m/Y', strtotime($pago_fic->fec_tramite)) : '' ?></td>
            <td><?= esc($pago_fic->folio ?? '') ?></td>
        </tr>
    </table>

    <table>
        <tr><th colspan="5" class="bg-black">DATOS PROPORCIONADOS POR LA DEPENDENCIA</th></tr>
        <tr><td colspan="5" class="bg-grey">REFERENCIA AL DOCUMENTO</td></tr>
        <tr class="bg-grey">
            <td width="18%">No. COMPROBANTE</td>
            <td width="18%">PROYECTO META</td>
            <td width="12%">PARTIDA</td>
            <td width="15%">IMPORTE</td>
            <td width="37%">OBSERVACIONES</td>
        </tr>
        <tr>
            <td><?= esc($pago_fic->comprobante ?? '') ?></td>
            <td><?= esc($proyecto->proyecto ?? 'E027QC04182601') ?></td>
            <td><?= esc($pago_fic->partida ?? '') ?></td>
            <td>$<?= number_format((float) str_replace(',', '', (string) ($pago_fic->importe ?? 0)), 2) ?></td>
            <td class="text-left" style="vertical-align: top;">
                <div class="font-bold">DATOS DEL PROVEEDOR NACIONAL</div>
                <div><span class="font-bold">No. PROVEEDOR:</span> <?= esc($establecimiento->no_proveedor ?? '') ?></div>
                <div><span class="font-bold">RFC:</span> <?= esc($establecimiento->rfc ?? '') ?></div>
                <div><span class="font-bold">NOMBRE:</span> <?= esc($establecimiento->razon_social ?? $establecimiento->dsc_establecimiento ?? '') ?></div>
                <div><span class="font-bold">NO. CUENTA:</span> <?= esc($establecimiento->no_cuenta ?? '') ?></div>
                <div><span class="font-bold">BANCO:</span> <?= esc($establecimiento->banco ?? '') ?></div>
                <div><span class="font-bold">CLABE:</span> <?= esc($establecimiento->clabe ?? '') ?></div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="text-left">
                <span class="font-bold">No. CONTRATO y/o CONVENIO:</span> <?= esc($establecimiento->no_contrato ?? 'NO APLICA') ?><br>
                <span class="font-bold">No. RESERVA:</span> <?= esc($pago_fic->reserva ?? '') ?>
            </td>
            <td width="30%" class="font-bold">$<?= number_format((float) str_replace(',', '', (string) ($pago_fic->importe ?? 0)), 2) ?></td>
        </tr>
        <tr><td colspan="4" class="font-bold"><?= esc($pago_fic->importe_letra ?? '') ?></td></tr>
    </table>

    <table style="margin-top: 20px;">
        <tr><th colspan="3" class="bg-black">AUTORIZACIONES</th></tr>
        <tr class="bg-grey">
            <td width="33%">RECURSOS FINANCIEROS</td>
            <td width="33%">AUTORIZA</td>
            <td width="34%">RESPONSABLE DEL PROYECTO</td>
        </tr>
        <tr style="height: 105px;">
            <td style="vertical-align: bottom;">
                <strong>ANGÉLICA LÓPEZ LÓPEZ</strong><br>
                <span style="font-size: 7pt;">COORDINADORA DE RECURSOS FINANCIEROS Y APLICACIÓN PRESUPUESTAL</span>
            </td>
            <td></td>
            <td style="vertical-align: bottom;">
                <strong><?= esc($usuario_registro->nombre_completo ?? '') ?></strong><br>
                <span style="font-size: 7pt;"><?= esc($usuario_registro->dsc_puesto ?? '') ?></span>
            </td>
        </tr>
    </table>
</body>
</html>
