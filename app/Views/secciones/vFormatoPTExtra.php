
        <div  style="position:absolute; text-align:center; top:14.8%; left:49.5%; width:42%; height:22px; background-color:BLACK; color:white; font-size:8px; ">
            <span class="proxima">21 SECRETARIA DE TURISMO E IDENTIDAD</span>
        </div>
        <div  style="position:absolute; text-align:right; top:9.4%; left:72.4%; width:12%; background-color:white; font-size: 12px;  height:18px;">
            <span>$<?= (isset($suma) && !empty($suma))? number_format($suma,2):''; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:19.2%; left:53%; width:10%; height:12px; background-color:white; font-size: 10px; ">
            <span class="proxima">21</span>
        </div>
        <div  style="position:absolute; text-align:center; top:19.2%; left:66.6%; width:7.5%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute;text-align:center; top:19.2%; left:75.5%; width:15%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima">PT <?= strtoupper($registro->folio);?> </span>
        </div>
      
         <div  style="position:absolute; top:31.6%; left:54.5%; width:27%; background-color:white; font-size: 9px;  height:12px;">
            <span >No. PROVEEDOR : <?= (isset($registro->no_proveedor) && !empty($registro->no_proveedor))?strtoupper($registro->no_proveedor):'' ?></span>
        </div>
        <div  style="position:absolute; top:30%; left:54.5%; width:33%; background-color:white; font-size: 9px;  height:12px;">
            <span class="proxima ">RFC : <?= (isset($registro->rfc) && !empty($registro->rfc))?strtoupper($registro->rfc):'' ?></span>
        </div>
        <div  style="position:absolute; top:37.3%; left:54.5%; width:36%; background-color:white; font-size: 12px;  height:12px;">

            <span class="proxima "><STRONG>NOMBRE : </STRONG><?= (isset($registro->dsc_proveedor) && !empty($registro->dsc_proveedor))?strtoupper($registro->dsc_proveedor):'' ?></span>

        </div>
          <div  style="position:absolute; top:38.7%; left:54.5%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><strong>NO. CUENTA : </strong><?= $no_cuenta ?></span>
        </div>
        <div  style="position:absolute; top:43%; left:54.5%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>BANCO : </STRONG><?= $banco ?></span>
        </div>
         <div  style="position:absolute; top:40.7%; left:54.5%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>CLABE :</STRONG> <?= $clabe ?></span>
        </div>
         <div  style="position:absolute; top:45%; left:54.5%; width:35%; background-color:white; font-size: 12px;  height:63px;">
            <span class="proxima "></span>
        </div>
        <?php $i = 28;  ?>
 
        <?php if( isset($periodo_factura) && !empty($periodo_factura) ): ?>
        <?php foreach($periodo_factura as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:23.5%; width:10%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->proyecto ?></span>
        </div>
    
        <?php $i = $i + 1.2; ?>
        <?php endforeach; ?>
        <?php endif; ?>

       <?php $i = 28;  ?>
        <?php if( isset($periodo_factura) && !empty($periodo_factura) ): ?>
        <?php foreach($periodo_factura as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:34%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->partida ?></span>
        </div>
          <?php $i = $i + 1.2; ?>
        <?php endforeach; ?>
         <?php endif; ?>
         <?php $i = 28;  ?>
         <?php if( isset($uuid) && !empty($uuid) ): ?>
        <?php foreach($uuid as $r): ?>
              <div style="position:absolute; text-align:right; top:<?=$i?>%; left:47.3%; width:6.7%; background-color:white; font-size:8px; height:12px; line-height:12px;">
                    <span class="proxima">
                        <?php 
                        $importe = $r->total;
                        if (is_numeric($importe)) {
                            echo '$' . number_format(floatval($importe), 2);
                        } else {
                            $limpio = preg_replace('/[^\d\.\-]/', '', (string)$importe);
                            echo '$' . (is_numeric($limpio) ? number_format(floatval($limpio), 2) : '0.00');
                        }
                        ?>
                    </span>
                </div>
        <?php $i = $i + 1.2; ?>
        <?php endforeach; ?>
        <?php endif; ?>
      
             <?php $i = 28;  ?>
            <?php foreach( $uuid as $u ): ?>
                <div style="position:absolute; text-align:center; top:<?=$i?>%; left:12%; width:11.1%; background-color:white; font-size: 12px;  height:12px;">
                  <span class="proxima"><?= isset($u->folio) && !empty($u->folio)?$u->folio:$u->uuid ?></span>
                 </div>
         <?php $i = $i + 1.2; ?>
            <?php endforeach; ?>

   
        <div  style="position:absolute; text-align:right; top:55.5%; left:42%; width:12%; background-color:white; font-size: 12px;  height:18px;">
            <span>$<?= (isset($suma) && !empty($suma))? number_format($suma,2):''; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:55.5%; left:55%; width:36%; background-color:white; font-size: 12px;  height:18px;">
            <span class="proxima "><strong><?= ($suma_texto); ?></strong></span>
        </div>
         <div  style="position:absolute; top:55.6%; left:12%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">No. Convenio / Contrato : <?= (isset($reserva[0]->no_convenio) && !empty($reserva[0]->no_convenio))?strtoupper($reserva[0]->no_convenio):'' ?></span>
        </div>
         <div  style="position:absolute; top:57.8%; left:12%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">
                <?php 
                $valores_unicos = array_unique(array_column($presupuesto, 'no_reserva'));
                $total = count($valores_unicos);
                $current = 0;

                foreach($valores_unicos as $no_reserva):
                    $current++;
                    echo 'No. Reserva : '.$no_reserva;
                    if ($current < $total) {
                        echo ', ';
                    }
                endforeach;
                ?>
                
            </span>
        </div>
         <div  style="position:absolute;  text-align:center; top:66%; left:15%; width:30%; background-color:white; font-size: 12px;  height:18px;">
            <span class="proxima "><?= 'L.R.I. RODRIGO GONZÁLEZ GUERRERO' ?></span>
        </div>

         <div  style="position:absolute;  text-align:center; top:66%; left:50%; width:24%; background-color:WHITE; font-size: 12px;  height:18px;">
            <span class="proxima "><?= strtoupper($registro->secretario); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:68.9%; left:50%; width:24%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->dsc_puesto_secretario); ?></span>
        </div>

      
      
         <div  style="position:absolute;  text-align:center; top:66%; left:75%; width:16%; background-color:white; font-size: 12px;  height:18px;">
            <span class="proxima "><?= $usu_sub->dsc_subsecretario?></span>
        </div>
      
     
         <div  style="position:absolute;  text-align:center; top:74.3%; left:76%; width:15%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= (isset($responsableGasto->nombre_completo) && !empty($responsableGasto->nombre_completo))?$responsableGasto->nombre_completo:'' ?> </span>
        </div>
         <div  style="position:absolute;  text-align:center; top:75.7%; left:75%; width:16%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= (isset($responsableGasto->dsc_puesto) && $responsableGasto->dsc_puesto)?$responsableGasto->dsc_puesto:'' ?> </span>
        </div>

        
        
       
   
    
