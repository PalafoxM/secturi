
        <div  style="position:absolute; text-align:center; top:18.2%; left:13%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">21</span>
        </div>
        <div  style="position:absolute; text-align:center; top:18.2%; left:45.5%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($vehiculo->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute;text-align:center; top:18.2%; left:70.5%; width:25%; height:18px; background-color:white; font-size: 12px; ">
           
            <span class="proxima">PT <?= $folio;?></span>
        </div>
        <div  style="position:absolute; top:36.2%; left:60.7%; width:35%; background-color:white; font-size: 12px;  height:12px;">
      
            <span class="proxima "> <?= $proveedor->razon_social;?> </span>
         
        </div>
         <div  style="position:absolute; top:37.4%; left:73.5%; width:20%; background-color:white; font-size: 9px;  height:12px;">
            <span > </span>
        </div>
        <div  style="position:absolute; top:38.5%; left:64%; width:33%; background-color:white; font-size: 9px;  height:12px;">
            <span class="proxima "> <?= $proveedor->rfc ?> </span>
        </div>
        <div  style="position:absolute; top:40.8%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>NOMBRE DEL PROVEEDOR :</STRONG> <?= $proveedor->razon_social;?> </span>
        
        </div>
          <div  style="position:absolute; top:42%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><strong>NO. CUENTA : <?= $proveedorBanco->no_cuenta ?> </span>
        </div>
        <div  style="position:absolute; top:43%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>BANCO: </STRONG><?= $proveedorBanco->banco?></span>
        </div>
         <div  style="position:absolute; top:44.3%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>CLABE:</STRONG> <?= $proveedorBanco->clabe ?></span>
        </div>
         <div  style="position:absolute; top:45.3%; left:60.8%; width:35%; background-color:white; font-size: 12px;  height:15px;">
            <span class="proxima "></span>
        </div>

         <div  style="position:absolute; text-align:center; top:34%; left:17.5%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $proyecto->proyecto ?></span>
        </div>

         <div  style="position:absolute; text-align:center; top:34%; left:31.7%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> 3550 </span>
        </div>
  
              <div style="position:absolute; text-align:center; top:34%; left:46.4%; width:13%; background-color:white; font-size:10px; height:12px; line-height:12px;">
                    <span class="proxima">
                       importe4
                    </span>
                </div>
    

                <div style="position:absolute; text-align:center; top:34%; left:2.7%; width:13.8%; background-color:white; font-size: 12px;  height:12px;">
                  <p>uuid</p>
                 </div>
    

   
        <div  style="position:absolute; text-align:right; top:52.2%; left:44%; width:15%; background-color:white; font-size: 12px;  height:12px;">
            <span>$importe</span>
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

        
        
       
   
    
