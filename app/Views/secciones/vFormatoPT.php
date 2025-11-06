
        <div  style="position:absolute; text-align:center; top:18.2%; left:13%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">21</span>
        </div>
        <div  style="position:absolute; text-align:center; top:18.2%; left:45.5%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute;text-align:center; top:18.2%; left:70.5%; width:25%; height:18px; background-color:white; font-size: 12px; ">
           
            <?php if(!$fic): ?>
            <span class="proxima"><?= (isset($GO) && !empty($GO))?'GO':'PT';?> <?= strtoupper($registro->folio);?> </span>
            <?php endif; ?>
            <?php if($fic): ?>
            <span class="proxima">PT <?= $folio;?></span>
            <?php endif; ?>
        </div>
        <div  style="position:absolute; top:36.2%; left:60.7%; width:35%; background-color:white; font-size: 12px;  height:12px;">
            <?php if($GO): ?>
            <span class="proxima"> <strong> GOBIERNO DEL ESTADO DE GUANAJUATO SFIYA SECRETARIA DE TURISMO</strong></span>
             <?php endif; ?>
           <?php if(!$GO): ?>
            <span class="proxima "><?= (isset($registro->dsc_proveedor) && !empty($registro->dsc_proveedor))?strtoupper($registro->dsc_proveedor):'' ?></span>
             <?php endif; ?>
        </div>
         <div  style="position:absolute; top:37.4%; left:73.5%; width:20%; background-color:white; font-size: 9px;  height:12px;">
            <span ><?= (isset($registro->no_proveedor) && !empty($registro->no_proveedor))?strtoupper($registro->no_proveedor):'' ?></span>
        </div>
        <div  style="position:absolute; top:38.5%; left:64%; width:33%; background-color:white; font-size: 9px;  height:12px;">
            <span class="proxima "><?= (isset($registro->rfc) && !empty($registro->rfc))?strtoupper($registro->rfc):'' ?></span>
        </div>
        <div  style="position:absolute; top:40.8%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
           <?php if($GO): ?>
            <span class="proxima"> <strong> GOBIERNO DEL ESTADO DE GUANAJUATO SFIYA SECRETARIA DE TURISMO</strong></span>
             <?php endif; ?>
           <?php if(!$GO): ?>
            <span class="proxima "><STRONG>NOMBRE DEL PROVEEDOR :</STRONG><?= (isset($registro->dsc_proveedor) && !empty($registro->dsc_proveedor))?strtoupper($registro->dsc_proveedor):'' ?></span>
             <?php endif; ?>
        </div>
          <div  style="position:absolute; top:42%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><strong>NO. CUENTA :</strong><?= ($fic)?$registro->no_cuenta: (isset($no_cuenta) && !empty($no_cuenta)?$no_cuenta:'') ?></span>
        </div>
        <div  style="position:absolute; top:43%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>BANCO: </STRONG><?= ($fic)?$registro->banco:$banco ?></span>
        </div>
         <div  style="position:absolute; top:44.3%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>CLABE:</STRONG> <?= ($fic)?$registro->clabe:$clabe ?></span>
        </div>
         <div  style="position:absolute; top:45.3%; left:60.8%; width:35%; background-color:white; font-size: 12px;  height:15px;">
            <span class="proxima "></span>
        </div>
        <?php $i = 34;  ?>
        <?php if($fic): ?>
        <?php foreach($reserva as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:17.5%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->proyecto ?></span>
        </div>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:3%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>
         <?php endif; ?>
        <?php if(!$fic): ?>
        <?php foreach($presupuesto as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:17.5%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->proyecto ?></span>
        </div>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:3%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php $i = 34;  ?>
        <?php if($fic): ?>
        <?php foreach($reserva as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:31.7%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->partida ?></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>
         <?php endif; ?>
        <?php if(!$fic): ?>
        <?php foreach($presupuesto as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:31.7%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->partida ?></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>
         <?php endif; ?>
         <?php $i = 34;  ?>
         <?php if($fic): ?>
        <?php foreach($reserva as $r): ?>
              <div style="position:absolute; text-align:center; top:<?=$i?>%; left:46.4%; width:13%; background-color:white; font-size:10px; height:12px; line-height:12px;">
                    <span class="proxima">
                        <?php 
                        $importe = $r->importe;
                        if (is_numeric($importe)) {
                            echo '$' . number_format(floatval($importe), 2);
                        } else {
                            $limpio = preg_replace('/[^\d\.\-]/', '', (string)$importe);
                            echo '$' . (is_numeric($limpio) ? number_format(floatval($limpio), 2) : '0.00');
                        }
                        ?>
                    </span>
                </div>
            <?php $i += 1.5; ?>
        <?php endforeach; ?>
        <?php endif; ?>
         <?php if(!$fic): ?>
        <?php foreach($importe as $r): ?>
              <div style="position:absolute; text-align:center; top:<?=$i?>%; left:46.4%; width:13%; background-color:white; font-size:10px; height:12px; line-height:12px;">
                    <span class="proxima">
                        <?php 
                        $importe = $r->importe;
                        if (is_numeric($importe)) {
                            echo '$' . number_format(floatval($importe), 2);
                        } else {
                            $limpio = preg_replace('/[^\d\.\-]/', '', (string)$importe);
                            echo '$' . (is_numeric($limpio) ? number_format(floatval($limpio), 2) : '0.00');
                        }
                        ?>
                    </span>
                </div>
            <?php $i += 1.5; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        
    
             <?php $i = 34;  ?>
            <?php foreach( $uuid as $u ): ?>
                <div style="position:absolute; text-align:center; top:<?=$i?>%; left:2.7%; width:13.8%; background-color:white; font-size: 12px;  height:12px;">
                  <p><?= $u->uuid; ?></p>
                 </div>
            <?php $i += 1.5; ?>
            <?php endforeach; ?>

   
        <div  style="position:absolute; text-align:right; top:52.2%; left:44%; width:15%; background-color:white; font-size: 12px;  height:12px;">
            <span>$<?= ($fic)?$reserva[0]->total_importe:$registro->total_importe; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:51.8%; left:60%; width:37.5%; background-color:white; font-size: 12px;  height:20px;">
            <span class="proxima "><strong><?= ($numero_texto); ?></strong></span>
        </div>
         <div  style="position:absolute; top:48.1%; left:22%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= (isset($reserva[0]->no_convenio) && !empty($reserva[0]->no_convenio))?strtoupper($reserva[0]->no_convenio):'' ?></span>
        </div>
         <div  style="position:absolute; top:49.4%; left:12%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">
                <?php 
                $valores_unicos = array_unique(array_column($presupuesto, 'no_reserva'));
                $total = count($valores_unicos);
                $current = 0;

                foreach($valores_unicos as $no_reserva):
                    $current++;
                    echo $no_reserva;
                    if ($current < $total) {
                        echo ', ';
                    }
                endforeach;
                ?>
                
            </span>
        </div>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:3.5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= ($fic)?'LIC. RODRIGO GONZÁLEZ GUERRERO':strtoupper($registro->director); ?></span>
        </div>
        <?php if(!$fic): ?>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:35.5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->secretario); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:68.9%; left:35.5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->dsc_puesto_secretario); ?></span>
        </div>
        <?php endif; ?>
        <?php if($fic): ?>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:35.5%; width:30%; background-color:white; font-size: 15px;  height:28px;">
            <span class="proxima ">MTRO. DAVID AYALA SAUCEDO - DIRECTOR GENERAL DE DESARROLLO TURÍSTICO POR ACUERDO SECRETARIAL N° 003/2025</span>
        </div>
        <?php endif; ?>
         <?php if(!$fic): ?>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $usu_sub->dsc_subsecretario?></span>
        </div>
         <?php endif; ?>
         <?php if($fic): ?>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima">MTRO. DAVID AYALA SAUCEDO  </span>
        </div>
         <?php endif; ?>
         <div  style="position:absolute;  text-align:center; top:69%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">DIRECTOR GENERAL DE DESARROLLO TURÍSTICO </span>
        </div>
         <div  style="position:absolute;  text-align:center; top:79%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= (isset($responsableGasto->nombre_completo) && !empty($responsableGasto->nombre_completo))?$responsableGasto->nombre_completo:'' ?> </span>
        </div>
         <div  style="position:absolute;  text-align:center; top:80.4%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= (isset($responsableGasto->dsc_puesto) && $responsableGasto->dsc_puesto)?$responsableGasto->dsc_puesto:'' ?> </span>
        </div>

        
        
       
   
    
