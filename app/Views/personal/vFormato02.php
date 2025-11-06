 <div  style="position:absolute; text-align:center;  top:14%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute; text-align:center; top:16.2%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?'GO':'PT' ?> <?= $folio;?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:18.5%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima">

            <?php 
            $keys = array_keys($presupuesto);
            $lastKey = end($keys);
             ?>
                     <?php foreach($presupuesto as $key => $p): ?>
                    <?= $p->partida ?><?= $key !== $lastKey ? ',' : '' ?>
                    <?php endforeach; ?>
               
            </span>
        </div>
         <div  style="position:absolute; text-align:center; top:15.5%; left:22%; width:36%; height:30px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($reserva->razon_social) && !empty($reserva->razon_social))?$reserva->razon_social:'';?></span>
        </div>
      <div  style="position:absolute; text-align:center; top:20.7%; left:22%; width:66%; height:30px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($registro->concepto_pago) && !empty($registro->concepto_pago))?$registro->concepto_pago:''; ?></span>
        </div>
       <div  style="position:absolute; top:27.6%; left:22%; width:35%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima">RODRIGO GONZÁLEZ GUERRERO</span>
        </div>
       <div  style="position:absolute; top:29.8%; left:22%; width:35%; height:10px; background-color:white; font-size: 7px; ">
           <!--  <span class="proxima"><?= (isset($direccion->dsc_area ) && !empty($direccion->dsc_area ))?$direccion->dsc_area :'';  ?></span> -->
             <span> DIRECTOR GENERAL ADMINISTRATIVO </span>
        </div>
        <div  style="position:absolute; text-align:justify;top:31.5%; left:7.5%; width:85%; height:35px; background-color:white; font-size:13px; ">
            <span class="proxima">Solicito que se realice trámite de pago del comprobante(s) fiscal con
            <strong>

            <?php 
            $keys = array_keys($uuid);
            $lastKey = end($keys);
            foreach($uuid as $key => $u): ?>
                <?= $u->uuid ?><?= $key !== $lastKey ? ',' : '' ?>
            <?php endforeach; ?>

           
            </strong>
                 derivado del contrato ó convenio número <strong> <?= (isset($reserva->no_convenio) && !empty($reserva->no_convenio))?$reserva->no_convenio:'S/N' ;?></strong> por la cantidad de <strong>$<?= ($fic)?$reserva->total_importe:( isset($registro->total_importe) && !empty($registro->total_importe)?$registro->total_importe:'' ) ;?></strong>
                 <strong>(<?= $numero_texto?>)</strong>, por el servicio de
                 <strong>
                      <?php if(!$fic): ?>
            <?php foreach($presupuesto as $key => $p): ?>
                <?= $p->dsc_partida ?><?= $key !== $lastKey ? ',' : '' ?>
            <?php endforeach; ?>
                <?php endif; ?>
                <?php if($fic): ?>
                <?= $registro->concepto_pago ?>
                <?php endif; ?>
                  
                </strong> prestado por el proveedor <strong><?=(isset($reserva->razon_social) && !empty($reserva->razon_social))?$reserva->razon_social:'' ?></strong>. Se cuenta con suficiencia presupuestal en la(s) partida(s) 
                <strong>
                 
                     <?php foreach($presupuesto as $key => $p): ?>
                    <?= $p->partida ?><?= $key !== $lastKey ? ',' : '' ?>
                    <?php endforeach; ?>
                   
                     
                   
                </strong> correspondiente.
           </span>
        </div>
        
        <div  style="position:absolute; top:43.2%; left:49.5%; width:2%; height:20px; background-color:white; font-size: 10px; ">
            <span class="proxima">SI</span>
        </div>
        <div  style="position:absolute; top:46.8%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?$responsableGasto->nombre_completo:$registro->responsable ?></span>
        </div>
        <div  style="position:absolute; top:49.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"> <?= (isset($GO) && !empty($GO))?$responsableGasto->dsc_puesto: $registro->dsc_puesto?></span>
        </div>
         <div  style="position:absolute; top:52.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?=  (isset($GO) && !empty($GO))?$responsableGasto->dsc_area: $registro->dsc_area ?></span>
        </div>
         <div  style="position:absolute; top:56.2%; left:49.5%; width:2%; height:20px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= ($es4000)?'NO':'SI' ?></span>
        </div>
         <div  style="position:absolute; top:56.2%; left:55.5%; width:30%; height:20px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= ($es4000)?'Ayuda/Aportación económica':'' ?></span>
        </div>
        <div  style="position:absolute; top:60%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?$responsableGasto->nombre_completo:$registro->responsable ?></span>
        </div>
         <div  style="position:absolute; top:62.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"> <?= (isset($GO) && !empty($GO))?$responsableGasto->dsc_puesto: $registro->dsc_puesto?></span>
        </div>
         <div  style="position:absolute; top:65.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
             <span class="proxima"> <?= (isset($GO) && !empty($GO))?$responsableGasto->dsc_area: $registro->dsc_area?></span>
        </div>
         <div  style="position:absolute; top:73%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?$responsableGasto->nombre_completo:$registro->responsable ?></span>
        </div>
         <div  style="position:absolute; top:75.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"> <?= (isset($GO) && !empty($GO))?$responsableGasto->dsc_puesto: $registro->dsc_puesto?></span>
        </div>
         <div  style="position:absolute; top:78%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
             <span class="proxima"> <?= (isset($GO) && !empty($GO))?$responsableGasto->dsc_area: $registro->dsc_area?></span>
        </div>

        <div  style="position:absolute; top:94.7%; left:21.2%; width:30%; height:10px; background-color:white; font-size: 10px; ">
            <?php if(!$fic): ?>
           <span class="proxima"><?= (isset($responsable) && !empty($responsable))?$responsable:'' ?></span>
           <?php endif; ?>
            <?php if($fic): ?>
           <span class="proxima">LIC. HUGO RAMÍREZ DUARTE</span>
           <?php endif; ?>
        </div>
         <div  style="position:absolute; top:96.8%; left:21.2%; width:35%; height:10px; background-color:white; font-size: 10px; ">
              <?php if(!$fic): ?>
           <span class="proxima"><?= (isset($dsc_puesto) && !empty($dsc_puesto))?$dsc_puesto:'' ?></span>
           <?php endif; ?>
            <?php if($fic): ?>
           <span class="proxima">DIRECTOR DE COMPETITIVIDAD TURÍSTICA</span>
           <?php endif; ?>
        </div>


        