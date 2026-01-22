
     
       
        <div  style="position:absolute; text-align:center; top:10.65%; left:75%; width:15%; height:10px; background-color:white; font-size: 8px; ">
           $<?= number_format($registro->total_importe, 2); ?>
        </div>
        <div  style="position:absolute; text-align:center; top:10.8%; left:49.5%; width:4%; height:10px; background-color:white; font-size: 8px; ">
           <?= isset( $documentos) && !empty( $documentos)? $documentos:'' ?>
        </div>
        <div  style="position:absolute;text-align:center; top:14.75%; left:53.5%; width:40.5%; height:15px; background-color:black; color:white; font-size: 12px; ">
           21 SECRETARIA DE TURISMO E IDENTIDAD
        </div>
       <div  style="position:absolute; text-align:center; top:19.5%; left:61.4%; width:7%; height:10px; background-color:white; font-size: 8px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>
        <div  style="position:absolute;text-align:center; top:19.5%; left:70%; width:23%; height:10px; background-color:white; font-size: 12px; ">
            <?php if(!$fic): ?>
            <span class="proxima"><?=  strtoupper($registro->folio); ?></span>
            <?php endif; ?>
        </div>
  
     
        
        <?php $i = 27.5;  ?>
        <?php 
           $total_p = count($presupuestoGO);
           $total_i = count($importe);
           $iteraciones = ($total_p == 1 && $total_i > 1) ? $importe : $presupuestoGO;
        ?>
        <?php foreach($iteraciones as $k => $v): ?>
            <?php 
               if ($total_p == 1 && $total_i > 1) {
                   $r = $presupuestoGO[0];
               } else {
                   $r = $v;
               }
            ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:22%; width:10%; background-color:white; font-size: 10px;  height:12px;">
            <span class="proxima "><?= $r->dsc_proyecto ?></span>
        </div>

         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:33%; width:16%; background-color:white; font-size: 10px;  height:12px;">
            <span class="proxima "><?= $r->dsc_partida ?></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>

        <?php $i = 27.5;  ?>
        <?php foreach($importe as $im): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:50%; width:11%; background-color:white; font-size: 10px;  height:12px;">
            <?php $final = (int)$im->total + (int)$im->propina ?>
            <span class="proxima ">$<?= number_format($final, 2) ?></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>

        
        <?php $i = 27.5;  ?>
            <?php foreach( $uuid as $u ): ?>
                <div style="position:absolute; text-align:center; top:<?=$i?>%; left:11.8%; width:11%; background-color:white; font-size: 10px;  height:12px; line-height:12px;">
                   <span class="proxima"> <strong> <?= ($u->folio)?$u->folio:$u->uuid; ?></strong> </span>
                 </div>
      

                <div style="position:absolute; text-align:center; top:<?=$i?>%; left:61.35%; width:18%; background-color:white; font-size: 10px;  height:11px;">
                  <span class="proxima"> <strong> <?= $u->emisor_nombre; ?> </strong></span>
                 </div>

                <div style="position:absolute; text-align:center; top:<?=$i?>%; left:81%; width:11%; background-color:white; font-size: 10px;  height:12px;">
                  <span class="proxima"><?= $u->emisor_rfc; ?></span>
                 </div>
            <?php $i += 1.5; ?>
        <?php endforeach; ?>
   
        <div  style="position:absolute; text-align:right; top:69.5%; left:50.5%; width:10%; background-color:white; font-size: 12px;  height:12px;">
            <span><?= number_format($registro->total_importe, 2); ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:69.5%; left:63%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><strong><?= ($numero_texto); ?></strong></span>
        </div>
         <div  style="position:absolute; top:48.1%; left:22%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= (isset($reserva[0]->no_convenio) && !empty($reserva[0]->no_convenio))?strtoupper($reserva[0]->no_convenio):'' ?></span>
        </div>
   
         <div  style="position:absolute;  text-align:center; top:75.7%; left:16%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= 'L.R.I. '.strtoupper($registro->director) ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:78.7%; left:16%; width:30%; background-color:white; font-size: 12px;  height:13px;">
            <span class="proxima ">DIRECTOR GENERAL ADMINISTRATIVO</span>
        </div>
   
         <div  style="position:absolute;  text-align:center; top:75.7%; left:55.5%; width:19%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->secretario); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:78.7%; left:55.5%; width:19%; background-color:white; font-size: 12px;  height:13px;">
            <span class="proxima "><?= strtoupper($registro->dsc_puesto_secretario); ?></span>
        </div>
        
         <div  style="position:absolute;  text-align:center; top:75.7%; left:75.3%; width:18.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= ($usu_sub->dsc_subsecretario == 'NO APLICA')?'':$usu_sub->dsc_subsecretario?></span>
        </div>
        
         <div  style="position:absolute;  text-align:center; top:78.7%; left:75.3%; width:18.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= (isset($usu_sub->puesto) && !empty($usu_sub->puesto))?$usu_sub->puesto:''?></span>
        </div>
                
      
      
         <div  style="position:absolute;  text-align:center; top:86%; left:75.3%; width:18.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= (isset($responsableGasto->nombre_completo) && !empty($responsableGasto->nombre_completo))?$responsableGasto->nombre_completo:'' ?> </span>
        </div>
         <div  style="position:absolute;  text-align:center; top:88%; left:75.3%; width:18.5%; background-color:white; font-size: 9px;  height:18px;">
            <span class="proxima "> <?= (isset($responsableGasto->dsc_puesto) && !empty($responsableGasto->dsc_puesto))?$responsableGasto->dsc_puesto:'' ?> </span>
        </div>

        
        
       
   
    
