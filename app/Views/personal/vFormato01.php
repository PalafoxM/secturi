
     <img src="<?= base_url() ?>assets/logo-guanajuato.png" alt="Logo" style="width: 180px;  background-color:WHITE; margin-left: 100px; margin-top: 20px;">

<div  style="position:absolute; text-align:center; top:12.2%; left:77.5%; width:6%; height:18px; background-color:WHITE; font-size: 8px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:15.5%; left:77.5%; width:6%; height:18px; background-color:white; font-size: 9px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?'GO':'PT' ?> <?= strtoupper($folio);?></span>
        </div>
        <div  style="position:absolute; text-align:left; top:25.3%; left:33.2%; width:5%; height:18px; background-color:WHITE; font-size: 8px; ">
            <span class="proxima"><?= ($registro->poliza == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:left; top:46%; left:33.2%; width:20%; height:18px; background-color:white; font-size: 8px; ">
            <span class="proxima"><?= ($registro->dsc_proveedor) ?></span>
        </div>
        <div  style="position:absolute; text-align:left; top:49%; left:33.2%; width:20%; height:18px; background-color:white; font-size: 8px; ">
            <span class="proxima"><?= ($registro->concepto_pago) ?></span>
        </div>
        <div  style="position:absolute; text-align:left; top:51.2%; left:33.2%; width:32%; height:18px; background-color:white; font-size: 8px; ">
            <?php 
            $arrUuid = [];
            foreach($uuid as $u){
                $arrUuid[] = ($u->folio) ? $u->folio : $u->uuid;
            }
            echo '<span class="proxima">' . implode(', ', $arrUuid) . '</span>';
            ?>
        </div>
        <div  style="position:absolute; text-align:left; top:54%; left:33.2%; width:48%; height:18px; background-color:white; font-size: 8px; ">
            <?php 
            $sumaTotal = 0;
            foreach($uuid as $u){
                $sumaTotal += (float)$u->total;
            }
            $fn = new \App\Libraries\Funciones();
            echo '<span class="proxima">$' . number_format($sumaTotal, 2) . ' ' . $fn->numeroALetras($sumaTotal) . '</span>';
            ?>
        </div>
         <div  style="position:absolute; text-align:left; top:46%; left:77.2%; width:7%; height:18px; background-color:white; font-size: 8px; ">
            <?php 
            $arrPartida = [];
            if(isset($presupuesto) && is_array($presupuesto)){
                foreach($presupuesto as $p){
                    $arrPartida[] = $p->partida;
                }
            } elseif(isset($presupuesto->partida)) {
                $arrPartida[] = $presupuesto->partida;
            }
            echo '<span class="proxima">' . implode(', ', $arrPartida) . '</span>';
            ?>
        </div>
       <div  style="position:absolute; text-align:center; top:51.2%; left:77.2%; width:7%; height:18px; background-color:white; font-size: 8px; ">
            <span class="proxima"><?= ($presupuesto[0]->no_convenio) ?></span>
        </div>
       <div  style="position:absolute; text-align:center; top:67%; left:45.5%; width:4%; height:18px; background-color:white; font-size: 8px; ">
            <span class="proxima">SI</span>
        </div>
       <div  style="position:absolute; text-align:center; top:72%; left:45.5%; width:4%; height:18px; background-color:white; font-size: 8px; ">
            <span class="proxima">SI</span>
        </div>
       <div  style="position:absolute; text-align:center; top:77%; left:45.5%; width:4%; height:18px; background-color:white; font-size: 8px; ">
            <span class="proxima">SI</span>
        </div>
       <!--  <div  style="position:absolute; text-align:center; top:40.5%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= ($registro->documentacion_comprobatoria == 1)?'SI':'NO'; ?></span>
        </div> -->
      <!--   <div  style="position:absolute; text-align:center; top:51.8%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <?php if(!$fic): ?>
            <span class="proxima"><?= (isset($registro->contrato_convenio) && !empty($registro->contrato_convenio) && $registro->contrato_convenio == 1)?'SI':'NO'; ?></span>
            <?php endif; ?>

            <?php if($fic): ?>
            <span class="proxima">N/A</span>
            <?php endif; ?>
        </div> -->
     <!--    <div  style="position:absolute; text-align:center; top:34.5%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px;">
            <span class="proxima"><?= ($registro->formato_establecido == 1)?'SI':'NO'; ?></span>
        </div> -->
      
    <!--      <div  style="position:absolute; text-align:center; top:48.6%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima">SI</span>
        </div>
         <div  style="position:absolute; text-align:center; top:55.2%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima">SI</span>
        </div> -->
      <!--   <div  style="position:absolute; text-align:center; top:62.5%; left:25%; width:65%; height:18px; background-color:red; font-size: 10px; ">
            <span class="proxima"><?= strtoupper($registro->otros); ?></span>
        </div> -->


        