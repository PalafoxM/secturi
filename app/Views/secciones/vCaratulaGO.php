
        <div  style="position:absolute; text-align:center; top:10.65%; left:75%; width:15%; height:10px; background-color:white; font-size: 8px; ">
           $<?= number_format($total_importe, 2); ?>
        </div>
        <div  style="position:absolute; text-align:center; top:10.8%; left:49.5%; width:4%; height:10px; background-color:white; font-size: 8px; ">
           <?= isset( $docAmpara) && !empty( $docAmpara)? $docAmpara:'' ?>
        </div>
        <div  style="position:absolute;text-align:center; top:14.75%; left:53.5%; width:40.5%; height:15px; background-color:black; color:white; font-size: 12px; ">
           21 SECRETARIA DE TURISMO E IDENTIDAD
        </div>
       <div  style="position:absolute; text-align:center; top:19.5%; left:61.35%; width:6%; height:10px; background-color:white; font-size: 8px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($fecha_tramite)); ?></span>
        </div>
        <div  style="position:absolute;text-align:center; top:19.5%; left:70%; width:23%; height:10px; background-color:white; font-size: 12px; ">
           
            <span class="proxima">GO <?=  strtoupper($prefijoCompleto); ?></span>
        </div>
  
     
        
        <?php 
           $i = 27.5; 
           $incremento = 1.5;
        ?>
        
        <?php foreach($listaOrdenada as $fila): ?>
            <?php 
            $comprobante = $fila['comprobante'];
            $isLong = strlen($comprobante) > 15;
            if ($isLong) {
                $half = ceil(strlen($comprobante) / 2);
                $comprobante = substr($comprobante, 0, $half) . '<br>' . substr($comprobante, $half);
            }

            $contribuyente = $fila['contribuyente'];
            $isLongName = strlen($contribuyente) > 30; // Bajamos umbral para ajustar mejor
            ?>
            <!-- Comprobante (Folio/UUID) -->
            <div style="position:absolute; text-align:center; top:<?=$i?>%; left:11.8%; width:10.2%; background-color:white; font-size: <?= $isLong ? '7px' : '9px' ?>; height:12px; line-height:<?= $isLong ? '6px' : '12px' ?>; overflow:hidden;">
                <span class="proxima"><strong><?= $comprobante ?></strong></span>
            </div>

            <!-- Proyecto Meta -->
            <div style="position:absolute; text-align:center; top:<?=$i?>%; left:22.1%; width:10.4%; background-color:white; font-size: 9px; height:12px; line-height:12px; overflow:hidden;">
                <span class="proxima"><?= $fila['proyecto'] ?></span>
            </div>

            <!-- Partida No. -->
            <div style="position:absolute; text-align:center; top:<?=$i?>%; left:32.6%; width:16%; background-color:white; font-size: 9px; height:12px; line-height:12px; overflow:hidden;">
                <span class="proxima"><?= $fila['partida'] ?></span>
            </div>

            <!-- Importe -->
            <div style="position:absolute; text-align:center; top:<?=$i?>%; left:50%; width:11%; background-color:white; font-size: 10px; height:12px; line-height:12px; overflow:hidden;">
                <span class="proxima">$<?= number_format($fila['importe'], 2) ?></span>
            </div>

            <!-- Datos del Contribuyente (Nombre) -->
            <div style="position:absolute; text-align:left; padding-left:2px; top:<?=$i?>%; left:61.35%; width:17.5%; background-color:white; font-size: <?= $isLongName ? '7px' : '9px' ?>; height:12px; line-height:<?= $isLongName ? '6px' : '12px' ?>; overflow:hidden;">
                <span class="proxima"><?= $contribuyente ?></span>
            </div>

            <!-- RFC -->
            <div style="position:absolute; text-align:center; top:<?=$i?>%; left:81%; width:11%; background-color:white; font-size: 10px; height:12px; line-height:12px; overflow:hidden;">
                <span class="proxima"><?= $fila['rfc'] ?></span>
            </div>

            <?php $i += $incremento; ?>
        <?php endforeach; ?>

   
        <div  style="position:absolute; text-align:right; top:69.5%; left:50.5%; width:10%; background-color:white; font-size: 12px;  height:12px;">
            <span><?= number_format($total_importe, 2); ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:69.5%; left:63%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><strong><?= ($numero_texto); ?></strong></span>
        </div>
         <div  style="position:absolute; top:48.1%; left:22%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "></span>
        </div>
   
         <div  style="position:absolute;  text-align:center; top:75.7%; left:16%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= 'L.R.I. RODRIGO GONZALEZ GUERRERO' ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:78.7%; left:16%; width:30%; background-color:white; font-size: 12px;  height:13px;">
            <span class="proxima ">DIRECTOR GENERAL ADMINISTRATIVO</span>
        </div>
   
         <div  style="position:absolute;  text-align:center; top:75.7%; left:55.5%; width:19%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $nombreSecretario ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:78.7%; left:55.5%; width:19%; background-color:white; font-size: 12px;  height:13px;">
            <span class="proxima "><?= $puestoSecretario ?></span>
        </div>
        <?php if(in_array($idReponsableSolicitud,[56,101,60])): ?>
         <div  style="position:absolute;  text-align:center; top:75.7%; left:75.3%; width:18.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">MARÍA GUADALUPE ROBLES LEÓN</span>
        </div>
        
         <div  style="position:absolute;  text-align:center; top:78.7%; left:75.3%; width:18.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">SECRETARIO/A DE TURISMO E IDENTIDAD</span>
        </div>
        <?php else: ?> 
       <div  style="position:absolute;  text-align:center; top:75.7%; left:75.3%; width:18.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $nombreResponsable == 'NO APLICA' ? '' : $nombreResponsable ?></span>
        </div>
        
         <div  style="position:absolute;  text-align:center; top:78.7%; left:75.3%; width:18.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $puestoResponsable == "NO APLICA" ? '' : $puestoResponsable ?></span>
        </div>
       <?php endif; ?>
         <div  style="position:absolute;  text-align:center; top:86%; left:75.3%; width:18.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= $nombreReponsableSolicitud ?> </span>
        </div>
         <div  style="position:absolute;  text-align:center; top:88%; left:75.3%; width:18.5%; background-color:white; font-size: 9px;  height:18px;">
            <span class="proxima "> <?= $puestoReponsableSolicitud ?> </span>
        </div>