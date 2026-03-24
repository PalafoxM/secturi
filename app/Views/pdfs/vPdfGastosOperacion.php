<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 0.5cm 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 7.5pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 2px;
            vertical-align: middle;
        }
        .no-border { border: none !important; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .bg-black { background-color: #000; color: #fff; }
        .header-bg { background-color: #000; color: #fff; text-align: center; font-weight: bold; }
        .section-header { text-align: center; border: 1px solid #000; background-color: #fff; font-weight: bold; padding: 2px; }
        
        .signature-cell {
            vertical-align: top;
            padding: 0;
        }
        .signature-box {
            height: 80px;
            position: relative;
            border: 1px solid #000;
            border-bottom: none; 
        }
        .signature-title-box {
            border: 1px solid #000; 
            text-align: center; 
            padding: 3px; 
            font-weight: bold;
        }
    </style>
</head>
<body>
<br>
<br>
    <!-- HEADER SECTION -->
    <table class="no-border" style="margin-bottom: 2px;">
        <tr>
            <!-- LOGO -->
            <td width="10%" class="no-border" style="vertical-align: top;">
                <img src="<?= $logo ?>" style="width: 150px; height: auto;">
            </td>
            <!-- SECRETARIA + DOC REL -->
            <td width="35%" class="no-border" style="vertical-align: top; color: #888; font-size: 7pt;">
                <div style="color: #666; font-weight: bold; margin-bottom: 5px;">SECRETARÍA DE TURISMO E IDENTIDAD</div>
            </td>
             <td width="55%" class="no-border" style="vertical-align: top; text-align: right; font-size: 8pt;">
                Relación de <span style="border-bottom: 1px solid #000; padding: 0 5px;">&nbsp;<?= $registro_pt->relacion ?>&nbsp;</span> documentos que amparan un importe de <span style="border-bottom: 1px solid #000; padding: 0 5px;">&nbsp;<?= isset($registro_pt->importe_total_num) ? $registro_pt->importe_total_num : '0.00' ?>&nbsp;</span><br>
                que se envían para su revisión y trámite de pago
            </td>
        </tr>
    </table>

    <!-- BANK INFO & RAMO -->
    <table class="no-border" style="margin-bottom: 5px;">
        <tr>
            <!-- Left: Bank Info -->
            <td width="50%" class="no-border" style="vertical-align: bottom; font-size: 7pt;">
                Favor de depositar a nombre de:<br>
                <div style="margin-left: 20px; font-weight: bold; margin-top: 2px; margin-bottom: 2px;">GOBIERNO DEL ESTADO DE GUANAJUATO</div>
                
                <table class="no-border" style="width: 100%;">
                    <tr>
                        <td class="no-border" width="15%"><strong>CUENTA:</strong></td>
                        <td class="no-border" width="20%">5185913</td>
                        <td class="no-border" width="15%" class="text-right"><strong>SUCURSAL:</strong></td>
                        <td class="no-border" width="50%">MARFIL</td>
                    </tr>
                    <tr>
                        <td class="no-border"><strong>CLABE:</strong></td>
                        <td class="no-border" colspan="3">030 21051859130201 9</td>
                    </tr>
                    <tr>
                        <td class="no-border"><strong>BANCO:</strong></td>
                        <td class="no-border" colspan="3">BANCO DEL BAJÍO</td>
                    </tr>
                </table>
            </td>

            <!-- Right: Ramo Table -->
            <td width="50%" class="no-border" style="vertical-align: bottom; padding-left: 5px;">
                <div style="text-align: right; font-size: 7pt; margin-bottom: 1px;">RAMO O ENTIDAD REMITENTE</div>
                <table style="border: 1px solid #000;">
                    <tr>
                        <td class="bg-black text-center" style="padding: 3px;">21 SECRETARIA DE TURISMO E IDENTIDAD</td>
                    </tr>
                </table>
                <table style="border: 1px solid #000; border-top: none;">
                    <tr>
                        <td class="text-center font-bold" width="20%">DIVISIÓN</td>
                        <td class="text-center font-bold" width="30%">FECHA</td>
                        <td class="text-center font-bold" width="50%">FOLIO</td>
                    </tr>
                    <tr>
                        <td class="text-center">21</td>
                        <td class="text-center"><?= isset($registro_pt->fecha_tramite) ? date('d/m/Y', strtotime($registro_pt->fecha_tramite)) : '' ?></td>
                        <td class="text-center"><?= isset($registro_pt->no_consecutivo) ? $registro_pt->no_consecutivo : '' ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- SECTIONS -->
    <div class="section-header" style="border-bottom: none;">DATOS PROPORCIONADOS POR LA DEPENDENCIA</div>
    <div style="background-color: #f0f0f0; text-align: center; border: 1px solid #000; font-size: 7pt; font-weight: bold; border-bottom: none;">REFERENCIA AL DOCUMENTO</div>

    <!-- MAIN TABLE -->
    <table style="border-top: 1px solid #000;">
        <thead>
            <tr>
                <th width="13%">COMPROBANTE</th>
                <th width="15%">PROYECTO META</th>
                <th width="10%">PARTIDA No.</th>
                <th width="12%">IMPORTE</th>
                <th width="50%" colspan="2">OBSERVACIONES</th>
            </tr>
            <tr>
                <td colspan="4" class="no-border"></td> <!-- Spacer for proper rendering of rowspan/colspan mix in some pdf engines, but standard is different -->
                <td width="35%" class="text-center font-bold">DATOS DEL CONTRIBUYENTE</td>
                <td width="18%" class="text-center font-bold">RFC</td>
            </tr>
        </thead>
        <tbody>
             <?php 
                $rows = isset($periodo_factura_rows) ? $periodo_factura_rows : [];
                $detalleRows = [];

                if (!empty($rows)) {
                    foreach ($rows as $r) {
                        $detalleRows[] = [
                            'comprobante' => $r->no_comprobante ?? '',
                            'proyecto' => $r->proyecto ?? '',
                            'partida' => $r->partida ?? '',
                            'importe' => '$' . number_format((float)str_replace(',', '', $r->importe ?? 0) + (float)str_replace(',', '', $r->propinas ?? 0), 2),
                            'proveedor' => $r->proveedor ?? '',
                            'rfc' => $r->rfc ?? '',
                            'tipo' => 'principal'
                        ];

                        if (isset($r->isr) && (float)$r->isr > 0) {
                            $detalleRows[] = [
                                'comprobante' => $r->no_comprobante ?? '',
                                'proyecto' => 'ISR',
                                'partida' => '',
                                'importe' => '$' . number_format((float)$r->isr, 2),
                                'proveedor' =>   $r->proveedor ?? '',
                                'rfc' => $r->rfc ?? '',
                                'tipo' => 'retencion'
                            ];
                        }

                        if (isset($r->impuesto_local) && (float)$r->impuesto_local > 0) {
                            $detalleRows[] = [
                                'comprobante' => $r->no_comprobante ?? '',
                                'proyecto' => ' ISR CEDULAR',
                                'partida' => '',
                                'importe' => '$' . number_format((float)$r->impuesto_local, 2),
                                'proveedor' => $r->proveedor ?? '',
                                'rfc' => $r->rfc ?? '',
                                'tipo' => 'retencion'
                            ];
                        }
                    }
                }

                if (empty($detalleRows)) {
                    $detalleRows[] = [
                        'comprobante' => '',
                        'proyecto' => '',
                        'partida' => '',
                        'importe' => '',
                        'proveedor' => '',
                        'rfc' => '',
                        'tipo' => 'vacio'
                    ];
                }

                $minRows = 10;
                while (count($detalleRows) < $minRows) {
                    $detalleRows[] = [
                        'comprobante' => '',
                        'proyecto' => '',
                        'partida' => '',
                        'importe' => '',
                        'proveedor' => '',
                        'rfc' => '',
                        'tipo' => 'vacio'
                    ];
                }
            ?>
            <?php foreach($detalleRows as $detalle): ?>
            <tr style="height: 26px;">
                <td style="text-align: center; font-weight: bold;"><?= $detalle['comprobante'] ?></td>
                <td style="text-align: center;"><?= $detalle['proyecto'] ?></td>
                <td style="text-align: center;"><?= $detalle['partida'] ?></td>
                <td style="text-align: center; font-weight: bold;"><?= $detalle['importe'] ?></td>
                <td style="text-align: center;"><?= $detalle['proveedor'] ?></td>
                <td style="text-align: center;"><?= $detalle['rfc'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <!-- TOTALS FOOTER -->
        <tfoot>
            <tr>
                <td colspan="3" class="text-center font-bold" style="border-right: 1px solid #000; border-top: 1px solid #000;">
                    IMPORTE TOTAL
                </td>
                <td class="text-center font-bold" style="border-top: 1px solid #000;">
                    <?= isset($registro_pt->importe_total_num) ? $registro_pt->importe_total_num : '0.00' ?>
                </td>
                <td colspan="2" class="text-center font-bold" style="background-color: #f9f9f9; border-top: 1px solid #000;">
                    <?= isset($registro_pt->importe_letra) ? $registro_pt->importe_letra : '' ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- SIGNATURES SECTION -->
    <table class="no-border" style="margin-top: 10px;">
        <tr>
            <!-- COL 1 -->
            <td width="33%" class="signature-cell">
                <div style="border: 1px solid #000; text-align: center; font-size: 7pt; padding: 1px;">DIRECTOR GENERAL ADMINISTRATIVO</div>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; border-top: none;">
                    <tr>
                         <td style="height: 100px; vertical-align: bottom; text-align: center; font-weight: bold; font-size: 8pt; border: none; padding-bottom: 2px;">
                             L.R.I. RODRIGO GONZALEZ GUERRERO
                        </td>
                    </tr>
                </table>
                <div class="signature-title-box" style="border-top: none;">
                    DIRECTOR GENERAL ADMINISTRATIVO
                </div>
            </td>

            <!-- COL 2 -->
             <td width="33%" class="signature-cell">
                <div style="border: 1px solid #000; border-left: none; text-align: center; font-size: 7pt; padding: 1px;">AUTORIZA</div>
                 <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; border-left: none; border-top: none;">
                    <tr>
                         <td style="height:100px; vertical-align: bottom; text-align: center; font-weight: bold; font-size: 8pt; border: none; padding-bottom: 2px;">
                            <?= (isset($registro_pt->nombre_autoriza) && $registro_pt->nombre_autoriza != 'NO APLICA') ? $registro_pt->nombre_autoriza : 'JAVIER PACHECO CANO' ?>
                        </td>
                    </tr>
                </table>
                <div class="signature-title-box" style="border-left: none; border-top: none; text-align: center;">
                      <?= (isset($registro_pt->cargo_autoriza) && $registro_pt->cargo_autoriza != 'NO APLICA') ? $registro_pt->cargo_autoriza : 'JAVIER PACHECO CANO' ?>
                </div>
            </td>

            <!-- COL 3 -->
             <td width="34%" height="150px" class="signature-cell">
                 <!-- Top Box (Responsable 1) -->
                <div style="border: 1px solid #000; border-left: none; text-align: center; font-size: 7pt; padding: 1px;">RESPONSABLE DEL PROYECTO</div>
                
                 <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; border-left: none; border-top: none;">
                    <tr>
                         <td style="height: 100px; vertical-align: bottom; text-align: center; font-weight: bold; font-size: 8pt; border: none; padding-bottom: 2px;">
                            <?= (isset($registro_pt->nombre_responsable) && $registro_pt->nombre_responsable != 'NO APLICA') ? $registro_pt->nombre_responsable : '' ?>
                        </td>
                    </tr>
                </table>
                
                <div class="signature-title-box" style="border-left: none; border-top: none; text-align: center;">
                     <?= (isset($registro_pt->cargo_responsable) && $registro_pt->cargo_responsable != 'NO APLICA') ? $registro_pt->cargo_responsable : 'RESPONSABLE DEL PROYECTO' ?>
                </div>

               
                  <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; border-left: none; border-top: none;">
                    <tr>
                         <td style="height: 100px; vertical-align: bottom; text-align: center; font-weight: bold; font-size: 7pt; border: none; padding-bottom: 2px;">
                            <?= (isset($registro_pt->nombre_responsable_2) && $registro_pt->nombre_responsable_2 != 'NO APLICA') ? $registro_pt->nombre_responsable_2 : '&nbsp;' ?>
                        </td>
                    </tr>
                </table>
                
                 <div class="signature-title-box" style="border-left: none; border-top: none; font-size: 6pt;">
                     <?= (isset($registro_pt->cargo_responsable_2) && $registro_pt->cargo_responsable_2 != 'NO APLICA') ? $registro_pt->cargo_responsable_2 : 'COORDINADOR/A DE RECURSOS MATERIALES Y SERVICIOS GENERALES' ?>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
