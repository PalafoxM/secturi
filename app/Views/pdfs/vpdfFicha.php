<?php
//die(var_dump($ficha));
// Helpers for arrays
$desglose_costo = isset($desglose_costo) && $desglose_costo != '' && $desglose_costo != 'null' ? json_decode($desglose_costo, true) : [];
$cantidades = isset($cantidades_desglose) && $cantidades_desglose != '' && $cantidades_desglose != 'null' ? json_decode($cantidades_desglose, true) : [];
$montos = isset($montos_desglose) && $montos_desglose != '' && $montos_desglose != 'null' ? json_decode($montos_desglose, true) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Técnica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .main-table {
            border: 2px solid #1a3c7b;
        }
        .main-table td, .main-table th {
            border: 1px solid #1a3c7b;
            padding: 4px;
            vertical-align: middle;
        }
        .ficha-header {
            background-color: #1a3c7b;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            padding: 8px;
            text-transform: uppercase;
        }
        .section-title {
            background-color: #1a3c7b;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            padding: 5px;
        }
        .cell-label {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 10px;
        }
        .cell-input {
            background-color: #ffffff;
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-success { color: #28a745; font-weight: bold; }
        .bg-gray { background-color: #d9d9d9; }
        .nested-table {
            width: 100%;
            border: none;
        }
        .nested-table td {
            border: none;
            padding: 2px;
        }
        .nested-table td.border-left {
            border-left: 1px solid #1a3c7b;
        }
    </style>
</head>
<body>

<table class="main-table">
    <tbody>
        <!-- Main Header -->
        <tr>
            <td colspan="4" class="ficha-header">
                DIRECCIÓN GENERAL DE COMPETITIVIDAD TURÍSTICA<br>
                FICHA TÉCNICA
            </td>
        </tr>
        
        <!-- Row 1 -->
        <tr>
            <td class="cell-label" style="width: 25%;">Nombre completo del evento</td>
            <td class="cell-input" style="width: 35%;"><?= $nombre_evento ?? '' ?></td>
            <td class="cell-label" style="width: 20%;">Persona que presenta la solicitud</td>
            <td class="cell-input" style="width: 20%;"><?= $persona_solicitud ?? '' ?></td>
        </tr>
        
        <!-- Row 2 -->
        <tr>
            <td class="cell-label">Fecha de realización</td>
            <td class="cell-input">
                <table class="nested-table">
                    <tr>
                        <td style="width: 50%;"><?= $fecha_realizacion ?? '' ?></td>
                        <td class="cell-label border-left" style="width: 20%;">Edición</td>
                        <td style="width: 30%;"><?= $edicion ?? '' ?></td>
                    </tr>
                </table>
            </td>
            <td class="cell-label">Municipio Sede</td>
            <td class="cell-input"><?= $municipio_sede ?? '' ?></td>
        </tr>
        
        <!-- Row 3 -->
        <tr>
            <td class="cell-label">Periodicidad</td>
            <td colspan="3" class="cell-input"><?= $periodicidad_radio ?? '' ?></td>
        </tr>
        
        <!-- Row 4 -->
        <tr>
            <td class="cell-label">Antecedentes</td>
            <td colspan="3" class="cell-input"><?= nl2br($antecedentes ?? '') ?></td>
        </tr>
        
        <!-- Row 5 -->
        <tr>
            <td class="cell-label">Objetivo general del evento:</td>
            <td colspan="3" class="cell-input"><?= nl2br($objetivo_general ?? '') ?></td>
        </tr>
        
        <!-- Row 6 -->
        <tr>
            <td class="cell-label">Justificación:</td>
            <td colspan="3" class="cell-input"><?= nl2br($justificacion ?? '') ?></td>
        </tr>
        
        <!-- Row 7 -->
        <tr>
            <td class="cell-label">Incorporación de la cadena de valor:</td>
            <td colspan="3" class="cell-input"><?= nl2br($cadena_valor ?? '') ?></td>
        </tr>
        
        <!-- Row 8 -->
        <tr>
            <td class="cell-label">Datos de población: Llena SECTURI:</td>
            <td colspan="3" class="cell-input" style="padding:0;">
                <table class="nested-table">
                    <tr>
                        <td class="cell-label" style="width: 25%;">Número de habitantes</td>
                        <td class="cell-input" style="width: 25%;"><?= $nivel_habilidades ?? '' ?></td>
                        <td class="cell-label border-left" style="width: 25%;">Estratificación Social:</td>
                        <td class="cell-input" style="width: 25%;"><?= $estrato ?? '' ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Complex Row 9 & 10 Group -->
        <tr>
            <td class="cell-label text-center">
                Periodicidad<br>
                <div style="background: white; border: 1px solid #ccc; padding: 5px; margin-top:5px; text-align:left; min-height: 30px; font-weight:normal;">
                    <?= nl2br($periodicidad_desc ?? '') ?>
                </div>
            </td>
            <td class="cell-label text-center bg-gray">Asistentes estimados</td>
            <td colspan="2" style="padding:0;">
                <table class="nested-table" style="border-collapse: collapse; width:100%;">
                    <tr>
                        <td class="cell-label text-center" style="width: 40%; border-bottom: 1px solid #1a3c7b;">Totales:<br>(acumulados)</td>
                        <td class="cell-input text-center" style="width: 30%; border-bottom: 1px solid #1a3c7b;"><?= $asistentes_totales ?? '' ?></td>
                        <td class="cell-label text-center border-left" style="width: 15%; border-bottom: 1px solid #1a3c7b;">Local</td>
                        <td class="cell-input text-center" style="width: 15%; border-bottom: 1px solid #1a3c7b;"><?= $asistentes_local ?? '' ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="cell-label text-right" style="font-weight:normal; border-bottom: 1px solid #1a3c7b;">Regional</td>
                        <td class="cell-input text-center" style="border-bottom: 1px solid #1a3c7b;"><?= $asistentes_regional ?? '' ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="cell-label text-right" style="font-weight:normal; border-bottom: 1px solid #1a3c7b;">Nacional</td>
                        <td class="cell-input text-center" style="border-bottom: 1px solid #1a3c7b;"><?= $asistentes_nacional ?? '' ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="cell-label text-right" style="font-weight:normal;">Internacional</td>
                        <td class="cell-input text-center"><?= $asistentes_internacional ?? '' ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td class="cell-label text-center" style="vertical-align: top;">
                Alcance<br>
                <small style="font-weight: normal; font-size: 8px;">[Local, regional, nacional, internacional.]</small>
                <br>
                <div style="background: white; border: 1px solid #ccc; padding: 5px; margin-top:5px; text-align:left; min-height: 40px;">
                    <?= nl2br($alcance ?? '') ?>
                </div>
            </td>
            <td class="cell-label text-center bg-gray">Derrama económica estimada</td>
            <td colspan="2" style="padding:0; vertical-align: top;">
                <table class="nested-table" style="width: 100%;">
                    <tr>
                        <td class="cell-label text-center" style="border-bottom: 1px solid #1a3c7b;">Total:</td>
                        <td class="cell-input text-center text-success" style="border-bottom: 1px solid #1a3c7b;"><?= $derrama_total ?? '' ?></td>
                    </tr>
                    <tr>
                        <td class="cell-label text-center" style="border-bottom: 1px solid #1a3c7b;">Habitante Local:</td>
                        <td class="cell-input text-center text-success" style="border-bottom: 1px solid #1a3c7b;"><?= $derrama_local ?? '' ?></td>
                    </tr>
                    <tr>
                        <td class="cell-label text-center">Visitante Foráneo:</td>
                        <td class="cell-input text-center text-success"><?= $derrama_foraneo ?? '' ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td class="cell-label text-center bg-gray" colspan="2">Empleos que se generarán</td>
            <td colspan="2" style="padding:0;">
                <table class="nested-table" style="width:100%;">
                    <tr>
                        <td class="cell-label text-center" style="border-bottom: 1px solid #1a3c7b;">Mujeres</td>
                        <td class="cell-input text-center" style="border-bottom: 1px solid #1a3c7b;"><?= $empleos_mujeres ?? '' ?></td>
                    </tr>
                    <tr>
                        <td class="cell-label text-center" style="border-bottom: 1px solid #1a3c7b;">Hombres</td>
                        <td class="cell-input text-center" style="border-bottom: 1px solid #1a3c7b;"><?= $empleos_hombres ?? '' ?></td>
                    </tr>
                    <tr>
                        <td class="cell-label text-center">Personas con discapacidad</td>
                        <td class="cell-input text-center"><?= $empleos_discapacidad ?? '' ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td class="cell-label text-center bg-gray" colspan="2">Cuota de acceso al evento</td>
            <td colspan="2" class="cell-input text-center text-success" style="font-weight: bold; font-size:12px;">
                <?= $cuota_acceso ?? '' ?>
            </td>
        </tr>
        
        <!-- Cuotas Row -->
        <tr>
            <td colspan="2" class="cell-label">
                Cuartos noche <small style="font-weight: normal;">[por acción generadas por el festival/evento]</small>: &nbsp;
                <span style="background: white; border: 1px solid #ccc; padding: 2px 10px;"><?= $cuantas_cuotas ?? '' ?></span>
            </td>
            <td colspan="2" class="cell-label">
                Costo total de la realización del festival/evento: &nbsp;
                <span class="text-success" style="background: white; border: 1px solid #ccc; padding: 2px 10px; font-weight: bold;"><?= $costo_total ?? '' ?></span>
            </td>
        </tr>
        
        <!-- Desglose -->
        <tr>
            <td colspan="4" class="cell-label" style="vertical-align: top;">
                Desglose del costo total del festival/evento por conceptos:
                <div style="background:white; border: 1px solid #ccc; margin-top:5px; padding: 5px; font-weight:normal; min-height:40px;">
                    <?php if(!empty($desglose_costo)): ?>
                        <table style="width:100%; border:none;">
                            <?php foreach($desglose_costo as $index => $concepto): ?>
                            <tr>
                                <td style="width:60%; border-bottom: 1px dashed #ccc;"><em><?= $concepto ?></em></td>

                                <td style="width:20%; border-bottom: 1px dashed #ccc;" class="text-right text-success">Monto: <b>$<?= $montos[$index] ?? '' ?></b></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        Ninguno.
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        
        <!-- Antecedentes Section -->
        <tr>
            <td colspan="4" class="section-title">
                ANTECEDENTES DEL FESTIVAL/EVENTO<br>
                <span style="font-size: 8px; font-weight: normal;">[EDICIONES REALIZADAS, RESULTADOS OBTENIDOS, ASISTENCIA DE PERSONAS, DERRAMA ECONÓMICA, ETC]</span>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="cell-input" style="height: 60px; vertical-align: top;">
                <?= nl2br($antecedentes_evento ?? '') ?>
            </td>
        </tr>
        
        <!-- Descripcion General Section -->
        <tr>
            <td colspan="4" class="section-title">
                DESCRIPCIÓN GENERAL DEL FESTIVAL/EVENTO
            </td>
        </tr>
        <tr>
            <td colspan="2" class="cell-label">
                Propuesta de valor del evento<br>
                <small style="font-weight: normal;">[Contenido diferenciado...]</small>
            </td>
            <td colspan="2" class="cell-input" style="height:40px; vertical-align:top;"><?= nl2br($propuesta_valor ?? '') ?></td>
        </tr>
        <tr>
            <td colspan="2" class="cell-label">
                Inclusión y empoderamiento de las mujeres<br>
                <small style="font-weight: normal;">[empleo, acción dirigida a mujeres]</small>
            </td>
            <td colspan="2" class="cell-input" style="height:40px; vertical-align:top;"><?= nl2br($inclusion_mujeres ?? '') ?></td>
        </tr>
        <tr>
            <td colspan="2" class="cell-label">Programa preliminar del evento</td>
            <td colspan="2" class="cell-input" style="height:40px; vertical-align:top;"><?= nl2br($programa_preliminar ?? '') ?></td>
        </tr>
        <tr>
            <td colspan="2" class="cell-label">Otras actividades (especificar)</td>
            <td colspan="2" class="cell-input" style="height:40px; vertical-align:top;"><?= nl2br($otras_actividades ?? '') ?></td>
        </tr>

        <!-- Datos de Sitio Web / Redes Sociales -->
        <tr>
            <td colspan="4" class="section-title" style="background-color: #1a6f8b;">
                DATOS DE SITIO WEB/ REDES SOCIALES
            </td>
        </tr>
        <tr>
            <td class="cell-label text-center">Link página web</td>
            <td colspan="3" class="cell-input"><?= $link_web ?? '' ?></td>
        </tr>
        <tr>
            <td class="cell-label text-center" rowspan="5" style="vertical-align: middle;">Nombre de sus cuentas en redes sociales</td>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">Facebook:</td>
            <td class="cell-input"><?= $facebook ?? '' ?></td>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white; border-right: none;">
                No. de seguidores <span style="background:#fff; color:#000; padding:2px 5px; margin-left:5px; border-radius:3px; display:inline-block;"><?= $fb_seguidores ?? '' ?></span>
            </td>
        </tr>
        <tr>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">Twitter:</td>
            <td class="cell-input"><?= $twitter ?? '' ?></td>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">
                No. de seguidores <span style="background:#fff; color:#000; padding:2px 5px; margin-left:5px; border-radius:3px; display:inline-block;"><?= $tw_seguidores ?? '' ?></span>
            </td>
        </tr>
        <tr>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">Instagram:</td>
            <td class="cell-input"><?= $instagram ?? '' ?></td>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">
                No. de seguidores <span style="background:#fff; color:#000; padding:2px 5px; margin-left:5px; border-radius:3px; display:inline-block;"><?= $ig_seguidores ?? '' ?></span>
            </td>
        </tr>
        <tr>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">YouTube:</td>
            <td class="cell-input"><?= $youtube ?? '' ?></td>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">
                No. de seguidores <span style="background:#fff; color:#000; padding:2px 5px; margin-left:5px; border-radius:3px; display:inline-block;"><?= $yt_seguidores ?? '' ?></span>
            </td>
        </tr>
        <tr>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">TikTok:</td>
            <td class="cell-input"><?= $tiktok ?? '' ?></td>
            <td class="cell-label text-center" style="background-color: #4a4a4a; color: white;">
                No. de seguidores <span style="background:#fff; color:#000; padding:2px 5px; margin-left:5px; border-radius:3px; display:inline-block;"><?= $tk_seguidores ?? '' ?></span>
            </td>
        </tr>

        <!-- Datos del Comité Organizador -->
        <tr>
            <td colspan="4" class="section-title">
                DATOS DEL COMITÉ ORGANIZADOR<br>
                <span style="font-size: 8px; font-weight: normal;">(PERSONA FÍSICA Y/O MORAL)</span>
            </td>
        </tr>
        <tr>
            <td class="cell-label text-center">Nombre Completo</td>
            <td class="cell-input"><?= $co_nombre ?? '' ?></td>
            <td class="cell-label text-center">Teléfono</td>
            <td class="cell-input"><?= $co_telefono ?? '' ?></td>
        </tr>
        <tr>
            <td class="cell-label text-center">Razón Social</td>
            <td class="cell-input"><?= $co_razon_social ?? '' ?></td>
            <td class="bg-gray"></td>
            <td class="bg-gray"></td>
        </tr>
        <tr>
            <td class="cell-label text-center">Cargo del comité organizador</td>
            <td class="cell-input"><?= $co_cargo ?? '' ?></td>
            <td class="cell-label text-center">Celular</td>
            <td class="cell-input"><?= $co_celular ?? '' ?></td>
        </tr>
        <tr>
            <td class="cell-label text-center">Domicilio</td>
            <td class="cell-input"><?= $co_domicilio ?? '' ?></td>
            <td class="bg-gray"></td>
            <td class="bg-gray"></td>
        </tr>
        <tr>
            <td class="cell-label text-center">Ciudad y estado</td>
            <td class="cell-input"><?= $co_ciudad_estado ?? '' ?></td>
            <td class="cell-label text-center">E-mail</td>
            <td class="cell-input"><?= $co_email ?? '' ?></td>
        </tr>

        <!-- Enlace Municipal Organizador -->
        <tr>
            <td colspan="4" class="section-title">
                ENLACE MUNICIPAL ORGANIZADOR
            </td>
        </tr>
        <tr>
            <td colspan="4" class="cell-label text-center" style="background-color: #1a3c7b; color: white; font-weight: normal; border-top: 1px dashed white;">
                ¿Es el mismo domicilio? Sólo agregar nombre, puesto, celular y E-mail.
            </td>
        </tr>
        <tr>
            <td class="cell-label text-center">Nombre Completo</td>
            <td class="cell-input"><?= $em_nombre ?? '' ?></td>
            <td class="cell-label text-center">Cargo/puesto en el evento</td>
            <td class="cell-input"><?= $em_cargo ?? '' ?></td>
        </tr>
        <tr>
            <td class="cell-label text-center" rowspan="2">Domicilio</td>
            <td class="cell-input" rowspan="2" style="vertical-align: top;"><?= nl2br($em_domicilio ?? '') ?></td>
            <td class="cell-label text-center">Teléfono (celular)</td>
            <td class="cell-input"><?= $em_celular ?? '' ?></td>
        </tr>
        <tr>
            <td class="cell-label text-center">Teléfono y extensión (fijo)</td>
            <td class="cell-input"><?= $em_telefono_fijo ?? '' ?></td>
        </tr>
        <tr>
            <td class="cell-label text-center">Ciudad y estado</td>
            <td class="cell-input"><?= $em_ciudad_estado ?? '' ?></td>
            <td class="cell-label text-center">E-mail</td>
            <td class="cell-input"><?= $em_email ?? '' ?></td>
        </tr>

        <!-- Apoyos Solicitados -->
        <tr>
            <td colspan="4" class="section-title">
                APOYOS SOLICITADOS<br>
                <span style="font-size: 8px; font-weight: normal;">(ESPECIFICAR SOLICITUDES DE APOYO ECONÓMICO O EN ESPECIE Y EL MONTO)</span>
            </td>
        </tr>
        <tr>
            <td class="cell-label text-center" style="width: 33.33%;">Federal</td>
            <td colspan="2" class="cell-label text-center" style="width: 33.33%;">Municipal</td>
            <td class="cell-label text-center" style="width: 33.33%;">Estatal</td>
        </tr>
        <tr>
            <td class="cell-input text-center text-success font-weight-bold"><?= $apoyo_federal ?? '' ?></td>
            <td colspan="2" class="cell-input text-center text-success font-weight-bold"><?= $apoyo_municipal ?? '' ?></td>
            <td class="cell-input text-center text-success font-weight-bold"><?= $apoyo_estatal ?? '' ?></td>
        </tr>
        <tr>
            <td colspan="4" class="cell-label text-center bg-gray">
                DESCRIBIR CONCEPTOS Y MONTOS EN LOS QUÉ SE APLICARÁ EL RECURSO SOLICITADO
            </td>
        </tr>
        <tr>
            <td colspan="4" class="cell-input" style="height: 50px; vertical-align:top;">
                <?= nl2br($descripcion_apoyos ?? '') ?>
            </td>
        </tr>

        <!-- Detallar Conceptos -->
        <tr>
            <td colspan="4" class="section-title">
                DETALLAR LOS CONCEPTOS A PAGAR CON LOS APOYOS QUE SOLICITA A LA SECRETARÍA DE TURISMO E IDENTIDAD DE GUANAJUATO
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
