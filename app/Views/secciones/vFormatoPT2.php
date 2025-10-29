<?php
// Obtener la fecha
$fecha = strtotime($registro->fecha_tramite);
$dia = date('j', $fecha);      // día sin ceros iniciales (1-31)
$anio = date('Y', $fecha);     // año

// Obtener el número del mes (1-12)
$mes_numero = (int) date('n', $fecha);

// Traducir mes a español con switch
switch ($mes_numero) {
    case 1:  $mes = 'enero'; break;
    case 2:  $mes = 'febrero'; break;
    case 3:  $mes = 'marzo'; break;
    case 4:  $mes = 'abril'; break;
    case 5:  $mes = 'mayo'; break;
    case 6:  $mes = 'junio'; break;
    case 7:  $mes = 'julio'; break;
    case 8:  $mes = 'agosto'; break;
    case 9:  $mes = 'septiembre'; break;
    case 10: $mes = 'octubre'; break;
    case 11: $mes = 'noviembre'; break;
    case 12: $mes = 'diciembre'; break;
    default: $mes = 'mes desconocido';
}

$fechaFormateada = $dia . ' de ' . $mes . ' del ' . $anio;
?>
<div style="position:absolute; text-align:right; top:21.2%; left:45.5%; width:45%; height:18px; background-color:white; font-size: 16px;">
    <span class="proxima"><strong>Silao de la Victoria, Gto, <?= ucfirst($fechaFormateada); ?></strong></span>
</div>
        <div  style="position:absolute; top:24.5%; left:64.5%; width:28%; height:18px; background-color:white; font-size: 14px; ">
            <?php if(!$fic): ?>
            <span class="proxima"><?= (isset($GO) && !empty($GO))?'GO':'PT';?> <?= strtoupper($registro->folio);?></span>
            <?php endif; ?>
            <?php if($fic): ?>
            <span class="proxima">PT <?= $folio;?></span>
            <?php endif; ?>
        </div>
        <div style="position:absolute; top:33.2%; left:9.5%; width:81%; height:72px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Por medio de la presente, me permito solicitar su apoyo para que se realice el tramite de <?= (isset($GO) && !empty($GO))?'Gasto de Operación':'Pago a Tercero'?>
                con folio <strong><?= (isset($GO) && !empty($GO))?'GO':'PT'?> <?= ($fic)?$folio:strtoupper($registro->folio);?></strong> por la cantidad de 
                <strong>$<?= ($fic)?$reserva[0]->total_importe: $registro->total_importe; ?> (<?= mb_strtoupper($numero_texto, 'UTF-8'); ?>)</strong>,
                de comprobante(s) fiscale(s) No. 
                <?php if($fic): ?>
                <strong>
                        <?= $uuid; ?>
                </strong> 
                <?php endif; ?>
                <?php if(!$fic): ?>
                <strong>
                    <?php 
                    $total = count($uuid);
                    $current = 0;
                    foreach($uuid as $u):
                        $current++;
                        echo $u->uuid;
                        if ($current < $total) {
                            echo ', ';
                        }
                    endforeach; 
                    ?>
                </strong> 
                <?php endif; ?>
                por concepto de <?= $registro->concepto_pago ?> 
                al proveedor <?= $registro->dsc_proveedor ?>.
            </span>
        </div>
        <div style="position:absolute; top:42.5%; left:9.5%; width:81%; height:42px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Lo anterior con cargo al proyecto(s) 
                <strong>
                    <?php if($fic): ?>
                    <?= strtoupper($reserva[0]->proyecto);?>
                   <?php endif; ?>
                     <?php if(!$fic): ?>
                        <?php 
                        $total = count($presupuesto);
                        $current = 0;
                        foreach($presupuesto as $r):
                            $current++;
                            echo $r->proyecto;
                            if ($current < $total) {
                                echo ', ';
                            }
                        endforeach; 
                        ?>
                   <?php endif; ?>
                </strong> a las partida(s) presupuestal(es) 
                <strong>
                 <?php if($fic): ?>
                   "<?= ($reserva[0]->partida);?> <?= ($reserva[0]->dsc_partida);?>".
                   <?php endif; ?>  
                     <?php if(!$fic): ?>
                        <?php 
                        $total = count($presupuesto);
                        $current = 0;
                        foreach($presupuesto as $r):
                            $current++;
                            echo $r->partida;
                            if ($current < $total) {
                                echo ', ';
                            }
                        endforeach; 
                        ?>
                   <?php endif; ?>  
               </strong>
            </span>
        </div>
         <div style="position:absolute; top:48%; left:9.5%; width:81%; height:80px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Hago de su conocimiento que de acuerdo a lo que establece la cláusula <strong><?= isset($registro->clausula_contrato) && !empty($registro->clausula_contrato)?$registro->clausula_contrato:'' ?></strong> de instrumento jurídico <strong><?=  isset($no_convenio) && !empty($no_convenio) ?$no_convenio:'' ?></strong>
                recibí el producto, atendido lo que establece el marco normativo aplicable. El producto recibido se nos ha
                entregado a entera satisfacción en tiempo y forma, quedando bajo responsabilidad el uso y/o distribución,
                así como el resguardo y custodia de los expedientes originales y entregables correspondientes.
            </span>
        </div>
        <div style="position:absolute; top:58%; left:9.5%; width:81%; height:40px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Daremos seguimiento al intrumento jurídico, con la finalidad de asegurar y garantizar que los recursos erogados cumplan con lo establecido,
                así como dar continuidad a las acciones del mencionado instrumento.
            </span>
        </div>
         <div style="position:absolute; top:64%; left:9.5%; width:81%; height:40px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
               La adquisicion del producto se realizó garantizado las mejores condiciones en cuanto a precio, calidad, financiamiento, oportunidad y demás elementos,
               en términos de la normatividad del gasto público.
            </span>
        </div>
        <div style="position:absolute; top:71%; left:9.5%; width:81%; height:20px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
              Sin otro particular por el momento, aprovecho la ocasión para enviarle un coridal saludo.
            </span>
        </div>
        <div style="position:absolute; top:75%; left:9.5%; width:81%; height:20px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> ATENTAMENTE </strong>
            </span>
        </div>
        <div style="position:absolute; top:82%; left:9.5%; width:81%; height:20px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> _________________________________________________ </strong>
            </span>
        </div>
         <div style="position:absolute; top:85%; left:9.5%; width:81%; height:20px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> <?= (isset($responsableGasto->nombre_completo) && !empty($responsableGasto->nombre_completo))?$responsableGasto->nombre_completo:''  ?> </strong>
            </span>
        </div>
           <div style="position:absolute; top:87%; left:9.5%; width:81%; height:20px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
              <strong> <?= (isset($responsableGasto->dsc_puesto) && !empty($responsableGasto->dsc_puesto))?$responsableGasto->dsc_puesto:'' ?></strong>
            </span>
        </div>
       



        
        
       
   
    
