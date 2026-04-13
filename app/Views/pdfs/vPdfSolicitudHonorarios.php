<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 10pt;
        color: #1f2937;
    }

    .header-title {
        text-align: center;
        font-weight: bold;
        font-size: 14pt;
        margin-bottom: 4px;
    }

    .header-subtitle {
        text-align: center;
        font-size: 10pt;
        margin-bottom: 18px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    td, th {
        border: 1px solid #1f4e79;
        padding: 6px 8px;
        vertical-align: top;
    }

    .section-title {
        background: #1f4e79;
        color: #fff;
        font-weight: bold;
        text-align: center;
    }

    .label {
        width: 30%;
        font-weight: bold;
        background: #eef4fb;
    }

    .check {
        text-align: center;
        font-weight: bold;
    }
</style>

<div class="header-title">SOLICITUD DE ELABORACION DE CONTRATO DE PRESTACION DE SERVICIOS</div>
<div class="header-title">PERSONALES POR HONORARIOS ASIMILADOS A SALARIOS</div>
<div class="header-subtitle">DIRECCION GENERAL JURIDICA</div>

<table>
    <tr><td colspan="2" class="section-title">INFORMACION DEL CONTRATO</td></tr>
    <tr>
        <td class="label">Responsable del Proyecto</td>
        <td><?= esc($solicitud->responsable_proyecto_nombre ?? '') ?></td>
    </tr>
    <tr>
        <td class="label">Area</td>
        <td><?= esc($solicitud->area ?? ($solicitud->area_responsable ?? '')) ?></td>
    </tr>
    <tr>
        <td class="label">Informes a rendir</td>
        <td><?= esc($solicitud->informes_rendir ?? '') ?></td>
    </tr>
    <tr>
        <td class="label">Vigencia</td>
        <td>
            <?= !empty($solicitud->vigencia_inicio) ? date('d/m/Y', strtotime($solicitud->vigencia_inicio)) : '' ?>
            al
            <?= !empty($solicitud->vigencia_fin) ? date('d/m/Y', strtotime($solicitud->vigencia_fin)) : '' ?>
        </td>
    </tr>
    <tr>
        <td class="label">Clave presupuestal</td>
        <td><?= esc($solicitud->clave_presupuestal_nombre ?? $solicitud->clave_presupuestal ?? '') ?></td>
    </tr>
    <tr>
        <td class="label">Partida</td>
        <td><?= esc($solicitud->partida_nombre ?? $solicitud->partida ?? '') ?></td>
    </tr>
    <tr>
        <td class="label">Monto total del contrato</td>
        <td>$<?= number_format((float) ($solicitud->monto_total_contrato ?? 0), 2) ?></td>
    </tr>
</table>

<table>
    <tr><td colspan="2" class="section-title">INFORMACION DEL PRESTADOR DE SERVICIOS</td></tr>
    <tr>
        <td class="label">Nombre completo</td>
        <td><?= esc($solicitud->nombre_prestador ?? '') ?></td>
    </tr>
    <tr>
        <td class="label">RFC</td>
        <td><?= esc($solicitud->rfc_prestador ?? '') ?></td>
    </tr>
    <tr>
        <td class="label">Domicilio</td>
        <td><?= esc($solicitud->domicilio_prestador ?? '') ?></td>
    </tr>
</table>

<table>
    <tr><td colspan="2" class="section-title">ACTIVIDADES</td></tr>
    <?php if (!empty($actividades)): ?>
        <?php foreach ($actividades as $index => $actividad): ?>
            <tr>
                <td class="label"><?= $index + 1 ?></td>
                <td><?= esc($actividad->actividad ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="2">No hay actividades registradas.</td>
        </tr>
    <?php endif; ?>
</table>

<table>
    <tr><td colspan="3" class="section-title">SOPORTE DOCUMENTAL</td></tr>
    <tr>
        <td>Autorizacion SFIA</td>
        <td>Justificacion oficial</td>
        <td>Cedula RFC</td>
    </tr>
    <tr>
        <td class="check"><?= !empty($solicitud->autorizacion_sfia) ? 'SI' : 'NO' ?></td>
        <td class="check"><?= !empty($solicitud->justificacion_oficial) ? 'SI' : 'NO' ?></td>
        <td class="check"><?= !empty($solicitud->cedula_rfc) ? 'SI' : 'NO' ?></td>
    </tr>
    <tr>
        <td>Comprobante domicilio</td>
        <td>Autorizacion datos</td>
        <td></td>
    </tr>
    <tr>
        <td class="check"><?= !empty($solicitud->comprobante_domicilio) ? 'SI' : 'NO' ?></td>
        <td class="check"><?= !empty($solicitud->autorizacion_datos) ? 'SI' : 'NO' ?></td>
        <td></td>
    </tr>
</table>

<div style="margin-top: 24px; text-align: right;">
    Fecha de impresion: <?= date('d/m/Y H:i:s') ?>
</div>
