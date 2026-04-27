<?php
// Obtener la fecha
$fecha = strtotime($registro->fecha_tramite);
$dia = date('j', $fecha);      // día sin ceros iniciales (1-31)
$anio = date('Y', $fecha);     // año

// Obtener el número del mes (1-12)
$mes_numero = (int) date('n', $fecha);

// Traducir mes a español con switch
switch ($mes_numero) {
    case 1:  $mes = 'ENERO'; break;
    case 2:  $mes = 'FEBRERO'; break;
    case 3:  $mes = 'MARZO'; break;
    case 4:  $mes = 'ABRIL'; break;
    case 5:  $mes = 'MAYO'; break;
    case 6:  $mes = 'JUNIO'; break;
    case 7:  $mes = 'JULIO'; break;
    case 8:  $mes = 'AGOSTO'; break;
    case 9:  $mes = 'SEPTIEMBRE'; break;
    case 10: $mes = 'OCTUBRE'; break;
    case 11: $mes = 'NOVIEMBRE'; break;
    case 12: $mes = 'DICIEMBRE'; break;
    default: $mes = 'mes desconocido';
}

$fechaFormateada = $dia . ' DE ' . $mes . ' DEL ' . $anio;
?>
<div style="position:absolute; text-align:left; top:17%; left:65%; width:20%; height:22px; background-color:WHITE; font-size: 16px;">
    <span class="proxima"><strong> <?= ucfirst($fechaFormateada); ?></strong></span>
</div>
        <div  style="position:absolute; top:20%; left:62%; width:28%; height:18px; background-color:white; font-size: 12px; ">
            <?php if(!$fic): ?>
            <span class="proxima"><i><?= (isset($GO) && !empty($GO))?'GO':'PT';?> <?= strtoupper($registro->folio);?></i></span>
            <?php endif; ?>
        
        </div>
        <div style="position:absolute; top:30.2%; left:14%; width:75%; height:72px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Por medio del presente, me permito solicitar su apoyo para que se realice el trámite de <?= (isset($GO) && !empty($GO))?'Gasto de Operación':'Pago a Tercero'?>
                pago a terceros de folio <strong><?= (isset($GO) && !empty($GO))?'GO':'PT'?> <?= ($fic)?$folio:strtoupper($registro->folio);?></strong> por la 
                cantidad de <strong>$<?= (isset($suma) && !empty($suma))? number_format($suma,2):''; ?> (<?= mb_strtoupper($suma_texto, 'UTF-8'); ?>)</strong>,
                de comprobante(s) fiscal(es) No. 
             
           
                <strong>
                    <?php 
                    $total = count($uuid);
                    $current = 0;
                    foreach($uuid as $u):
                        $current++;
                        echo (isset($u->folio) && !empty($u->folio))?$u->folio:$u->uuid;
                        if ($current < $total) {
                            echo ', ';
                        }
                    endforeach; 
                    ?>
                </strong> 
    
                por concepto de <?= (isset($concepto) && !empty($concepto))?$concepto:$registro->concepto_pago ?> 
                al proveedor <?= $registro->dsc_proveedor ?>.
            </span>
        </div>
        <div style="position:absolute; top:39.5%; left:14%; width:75%; height:42px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Lo anterior con cargo al proyecto(s) 
                <strong>
              
                      <?php 
                        $proyectosMostrados = [];
                        $proyectosArray = [];

                        // Primero recolectar proyectos únicos
                        foreach($presupuesto as $r) {
                            if (!in_array($r->proyecto, $proyectosMostrados)) {
                                $proyectosMostrados[] = $r->proyecto;
                            }
                        }

                        // Mostrar resultados
                        echo implode(', ', $proyectosMostrados);
                        ?>
                 
              
                </strong> a las partida(s) presupuestal(es) 
                <strong>
                    
                      <?php 
                        $vistas = [];
                        $resultado = [];

                        foreach($presupuesto as $r) {
                            if (!in_array($r->partida, $vistas)) {
                                $vistas[] = $r->partida;
                                $resultado[] = $r->partida;
                            }
                        }

                        echo implode(', ', $resultado);
                        ?>
                  
               </strong>
            </span>
        </div>
         <div style="position:absolute; top:45%; left:14%; width:75%; height:80px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Hago de su conocimiento que de acuerdo a lo que establece la cláusula <strong><?= isset($registro->clausula_contrato) && !empty($registro->clausula_contrato)?$registro->clausula_contrato:'' ?></strong> de instrumento jurídico <strong><?=  isset($no_convenio) && !empty($no_convenio) ?$no_convenio:'' ?></strong>
                recibí el producto, atendiendo lo que establece el marco
                normativo aplicable. El producto recibido se nos ha entregado a entera satisfacción en tiempo y
                forma, quedando bajo mi responsabilidad el uso y/o distribución, así como el resguardo y custodia
                de los expedientes originales y entregables correspondientes.
            </span>
        </div>
        <div style="position:absolute; top:55%; left:14%; width:75%; height:40px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Daremos seguimiento al instrumento jurídico, con la finalidad de asegurar y garantizar que los recursos erogados cumplan con lo establecido,
                así como dar continuidad a las acciones del mencionado instrumento.
            </span>
        </div>
         <div style="position:absolute; top:61%; left:14%; width:75%; height:40px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
               La adquisición del producto se realizó garantizando las mejores condiciones en cuanto a precio, calidad, financiamiento, oportunidad y demás elementos,
               en términos de la normatividad del gasto público.
            </span>
        </div>
        <div style="position:absolute; top:67%; left:14%; width:75%; height:20px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
              Sin otro particular por el momento, aprovecho la ocasión para enviarle un cordial saludo.
            </span>
        </div>
        <div style="position:absolute; top:75%; left:14%; width:75%; height:20px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> ATENTAMENTE </strong>
            </span>
        </div>
        <div style="position:absolute; top:82%; left:14%; width:75%; height:20px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> _________________________________________________ </strong>
            </span>
        </div>
         <div style="position:absolute; top:85%; left:14%; width:75%; height:20px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> <?= (isset($responsableGasto->nombre_completo) && !empty($responsableGasto->nombre_completo))?$responsableGasto->nombre_completo:''  ?> </strong>
            </span>
        </div>
           <div style="position:absolute; top:87.5%; left:14%; width:75%; height:30px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
              <strong> <?= (isset($responsableGasto->dsc_puesto) && !empty($responsableGasto->dsc_puesto))?$responsableGasto->dsc_puesto:'' ?></strong>
            </span>
        </div>
       



        
        
       
   
    
