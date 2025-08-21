
        <div  style="position:absolute; text-align:center; top:18.2%; left:13%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">21</span>
        </div>
        <div  style="position:absolute; text-align:center; top:18.2%; left:45.5%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute;text-align:center; top:18.2%; left:70.5%; width:25%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?'GO':'PT';?> <?= strtoupper($registro->folio);?></span>
        </div>
        <div  style="position:absolute; top:36.2%; left:66.8%; width:30%; background-color:white; font-size: 9px;  height:12px;">
            <span ><?= (isset($registro->dsc_proveedor) && !empty($registro->dsc_proveedor))?strtoupper($registro->dsc_proveedor):'' ?></span>
        </div>
         <div  style="position:absolute; top:37.4%; left:73.5%; width:20%; background-color:white; font-size: 9px;  height:12px;">
            <span ><?= (isset($registro->no_proveedor) && !empty($registro->no_proveedor))?strtoupper($registro->no_proveedor):'' ?></span>
        </div>
        <div  style="position:absolute; top:38.5%; left:64%; width:33%; background-color:white; font-size: 9px;  height:12px;">
            <span class="proxima "><?= (isset($registro->rfc) && !empty($registro->rfc))?strtoupper($registro->rfc):'' ?></span>
        </div>
        <div  style="position:absolute; top:43.1%; left:77.2%; width:20.5%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= (isset($registro->dsc_proveedor) && !empty($registro->dsc_proveedor))?strtoupper($registro->dsc_proveedor):'' ?></span>
        </div>
        <div  style="position:absolute; top:44.1%; left:65.6%; width:29%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->banco); ?></span>
        </div>
         <div  style="position:absolute; top:45.3%; left:65.2%; width:20%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->no_cuenta); ?></span>
        </div>
        <?php $i = 34;  ?>
        <?php foreach($reserva as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:17.5%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->proyecto ?></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>
        <?php $i = 34;  ?>
        <?php foreach($reserva as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:31.7%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->partida ?></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>
         <?php $i = 34;  ?>
        <?php foreach($reserva as $r): ?>
            <div style="position:absolute; text-align:center; top:<?=$i?>%; left:46.4%; width:13%; background-color:white; font-size:10px; height:12px; line-height:12px;">
                <span class="proxima"><?= '$' . number_format($r->importe, 2) ?></span>
            </div>
            <?php $i += 1.5; ?>
        <?php endforeach; ?>
        <div  style="position:absolute; text-align:right; top:52.2%; left:44%; width:15%; background-color:white; font-size: 12px;  height:12px;">
            <span>$<?= ($reserva[0]->total_importe); ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:51.8%; left:60%; width:37.5%; background-color:white; font-size: 12px;  height:20px;">
            <span class="proxima "><strong><?= ($numero_texto); ?></strong></span>
        </div>
         <div  style="position:absolute; top:48.1%; left:22%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= (isset($reserva[0]->no_convenio) && !empty($reserva[0]->no_convenio))?strtoupper($reserva[0]->no_convenio):'' ?></span>
        </div>
         <div  style="position:absolute; top:49.4%; left:12%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($reserva[0]->no_reserva); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:3.5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->director); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:35.5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->secretario); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:67.1%; width:30%; background-color:gray; font-size: 12px;  height:12px;">
            <span class="proxima ">SUBSECRETARIA</span>
        </div>
         <div  style="position:absolute;  text-align:center; top:79%; left:67.1%; width:30%; background-color:gray; font-size: 12px;  height:12px;">
            <span class="proxima "> CLAUDIA </span>
        </div>

        
        
       
   
    
