<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .bg-black {
            background-color: #000;
            color: #fff;
        }
        .bg-grey {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .no-border { border: none; }
        .header-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
    <table width="100%" style="border: none; margin-bottom: 20px;">
        <tr>
            <td width="30%" style="border: none; vertical-align: middle; text-align: left;">
                <div style="width: 150px; height: 80px; background-image: url('<?= str_replace('\\', '/', $logo) ?>'); background-size: contain; background-repeat: no-repeat; background-position: left center;"></div>
            </td>
            <td width="70%" style="border: none; vertical-align: top;">
                <div style="text-align: right; font-size: 9px; color: #999; margin-bottom: 5px;">FORMATO PT - 25</div>
                <div style="text-align: center;">
                    <div style="font-weight: bold; font-size: 12pt; color: #000;">GOBIERNO DEL ESTADO DE GUANAJUATO</div>
                    <div style="font-size: 10pt; color: #000; margin-top: 5px;">FORMATO DE PAGO A TERCEROS</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- TABLA 1: RAMO -->
    <table>
        <thead>
            <tr class="bg-black">
                <th colspan="3" style="color:white;">RAMO O ENTIDAD REMITENTE</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-grey">
                <td colspan="3">21 SECRETARIA DE TURISMO E IDENTIDAD</td>
            </tr>
            <tr class="bg-grey">
                <td width="33%">DIVISIÓN</td>
                <td width="33%">FECHA TRÁMITE</td>
                <td width="33%">FOLIO</td>
            </tr>
            <tr>
                <td>21</td>
                <td><?= isset($registro_pt->fecha_tramite) ? date('d/m/Y', strtotime($registro_pt->fecha_tramite)) : '' ?></td>
                <td><?= isset($registro_pt->no_consecutivo) ? $registro_pt->no_consecutivo : '' ?></td>
            </tr>
        </tbody>
    </table>

    <!-- TABLA 2: ITEMS -->
    <table>
        <thead>
            <tr class="bg-black">
                <th colspan="5" style="color:white;">DATOS PROPORCIONADOS POR LA DEPENDENCIA</th>
            </tr>
            <tr class="bg-grey">
                <td colspan="5">REFERENCIA AL DOCUMENTO</td>
            </tr>
            <tr>
                <th width="15%">No. COMPROBANTE</th>
                <th width="15%">PROYECTO META</th>
                <th width="15%">No. PARTIDA</th>
                <th width="15%">IMPORTE</th>
                <th width="40%">OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $rows = isset($periodo_factura_rows) && !empty($periodo_factura_rows) ? $periodo_factura_rows : [];
            $total_rows = count($rows) > 0 ? count($rows) : 1;
            
            // Dummy row if empty
            if(empty($rows)) $rows = [(object)['encabezado' => '', 'proyecto_clave' => '', 'partida_clave' => '', 'importe' => '']];

            foreach($rows as $index => $row): 
            ?>
            <tr>
                <td><?= isset($row->no_comprobante) ? $row->no_comprobante : '' ?></td>
                <td><?= isset($row->proyecto) ? $row->proyecto : '' ?></td>
                <td><?= isset($row->partida) ? $row->partida : '' ?></td>
                <td>$<?= isset($row->importe) ? ($row->importe) : '0.00' ?></td>
                
                <?php if($index === 0): ?>
                <td rowspan="<?= count($rows) ?>" class="text-left" style="vertical-align: top;">
                    <div class="font-bold">DATOS DEL PROVEEDOR NACIONAL</div>
                    <?php if(!empty($row->nombre_proveedor_1)): ?>
                        <div><?= $row->nombre_proveedor_1 ?></div>
                    <?php endif; ?>
                    
                    <?php // Try to use row specific fields if they were saved in the item (which they weren't in the schema showed) 
                          // Or use variables passed to view if available
                    ?>
                   <!-- Hardcoded example structure as per requirements, could be dynamic -->
                   <div><span class="font-bold">No. PROVEEDOR:</span> <?= isset($registro_pt->no_proveedor) ? $registro_pt->no_proveedor : '' ?></div>
                   <div><span class="font-bold">RFC:</span> <?= isset($registro_pt->rfc_proveedor) ? $registro_pt->rfc_proveedor : '' ?></div>
                   <div><span class="font-bold">NOMBRE:</span> <?= isset($registro_pt->nombre_proveedor_1) ? $registro_pt->nombre_proveedor_1 : '' ?></div>
                   <div><span class="font-bold">NO. CUENTA:</span> <?= isset($registro_pt->no_cuenta) ? $registro_pt->no_cuenta : '' ?></div>
                   <div><span class="font-bold">BANCO:</span> <?= isset($registro_pt->banco) ? $registro_pt->banco : '' ?></div>
                   <div><span class="font-bold">CLABE:</span> <?= isset($registro_pt->clabe) ? $registro_pt->clabe : '' ?></div>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- TABLE 3: TOTALS -->
    <table>
        <tr>
            <td colspan="3" class="text-left no-border">
                <span class="font-bold">No. CONTRATO y/o CONVENIO:</span> <?= isset($registro_pt->contrato_convenio) ? $registro_pt->contrato_convenio : '' ?>
                <br>
                <span class="font-bold">No. RESERVA:</span> <?= isset($registro_pt->no_reserva) ? $registro_pt->no_reserva : '' ?>
            </td>
            <td width="30%" class="font-bold">
                 $<?= isset($registro_pt->importe_total_num) ? $registro_pt->importe_total_num : '' ?>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="text-center font-bold">
                <?= isset($registro_pt->importe_letra) ? $registro_pt->importe_letra : '' ?>
            </td>
        </tr>
    </table>

    <!-- TABLE 4: SIGNATURES -->
    <table style="margin-top: 20px;">
        <thead>
            <tr class="bg-black">
                <th colspan="3" style="color:white;">AUTORIZACIONES</th>
            </tr>
            <tr class="bg-grey">
                <th width="33%">DIRECTOR/A GENERAL ADMINISTRATIVO/A</th>
                <th width="33%">AUTORIZA</th>
                <th width="33%">RESPONSABLE DEL PROYECTO</th>
            </tr>
        </thead>
        <tbody>
            <tr style="height: 100px;">
                <td style="height: 80px; vertical-align: bottom;">
                    <br><br><br>
                    <strong>RODRIGO GONZALEZ GUERRERO</strong><br>
                    <span style="font-size: 8pt;">DIRECTOR/A GENERAL ADMINISTRATIVO/A</span>
                </td>
                <td style="height: 80px; vertical-align: bottom;">
                    <br><br><br>
                     <strong>JAVIER PACHECO CANO</strong><br>
                    <span style="font-size: 8pt;">DIRECTOR/A GENERAL JURÍDICO</span>
                </td>
                <td style="height: 80px; vertical-align: bottom;">
                    <br><br><br>
                     <strong>MARCO ANTONIO MORALES GARCÍA</strong><br>
                    <span style="font-size: 8pt;">DIRECTOR/A GENERAL DE INNOVACIÓN E INTELIGENCIA TURÍSTICA</span>
                </td>
            </tr>
            <tr>
                 <td colspan="2" class="no-border"></td>
                 <td class="bg-grey font-bold">RESPONSABLE DEL PROYECTO</td>
            </tr>
             <tr>
                 <td colspan="2" class="no-border"></td>
                 <td style="height: 80px; vertical-align: bottom;">
                      <br><br><br>
                     <strong>MARCO ANTONIO MORALES GARCÍA</strong><br>
                    <span style="font-size: 8pt;">DIRECTOR/A GENERAL DE INNOVACIÓN E INTELIGENCIA TURÍSTICA</span>
                 </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
