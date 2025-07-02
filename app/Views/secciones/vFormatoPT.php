
        <div  style="position:absolute; top:19.2%; left:19%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">21</span>
        </div>
        <div  style="position:absolute; top:19.2%; left:47.5%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute; top:19.2%; left:72%; width:25%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">PT <?= strtoupper($registro->folio);?></span>
        </div>
        <div  style="position:absolute; top:36.8%; left:67%; width:25%; background-color:white; font-size: 9px;  height:12px;">
            <span ><?= strtoupper($registro->dsc_proveedor); ?></span>
        </div>
         <div  style="position:absolute; top:38%; left:73.5%; width:20%; background-color:white; font-size: 9px;  height:12px;">
            <span ><?= strtoupper($registro->no_proveedor); ?></span>
        </div>
        <div  style="position:absolute; top:39.1%; left:64.2%; width:20%; background-color:white; font-size: 9px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->rfc); ?></span>
        </div>
        <div  style="position:absolute; top:43.5%; left:77.1%; width:20%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->dsc_proveedor); ?></span>
        </div>
        <div  style="position:absolute; top:44.6%; left:66%; width:20%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->banco); ?></span>
        </div>
         <div  style="position:absolute; top:45.7%; left:65.9%; width:20%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->no_cuenta); ?></span>
        </div>
        
        
       
   
    
