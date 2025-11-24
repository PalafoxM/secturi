 <div  style="position:absolute; text-align:center;  top:14%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($vehiculo->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute; text-align:center; top:16.2%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima">PT <?= $vehiculo->folio.'-V';?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:18.5%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima">3550
            </span>
        </div>
         <div  style="position:absolute; text-align:center; top:15.5%; left:22%; width:36%; height:30px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($proveedor) && !empty($proveedor))?$proveedor:'';?></span>
        </div>
      <div  style="position:absolute; text-align:center; top:20.7%; left:22%; width:66%; height:30px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($vehiculo->concepto) && !empty($vehiculo->concepto))?$vehiculo->concepto:''; ?></span>
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
           <?= $vehiculo->xml_uuid ?>
            </strong>
                 derivado del contrato ó convenio número <strong> <?= (isset($vehiculo->convenio) && !empty($vehiculo->convenio))?$vehiculo->convenio:'S/N' ;?></strong> por la cantidad de <strong>$<?= $vehiculo->xml_monto ;?></strong>
                 <strong>(<?= $numero_texto?>)</strong>, por el servicio de
                 <strong>
                  <?= (isset($vehiculo->concepto) && !empty($vehiculo->concepto))?$vehiculo->concepto:''; ?>
                </strong> prestado por el proveedor <strong><?=(isset($proveedor) && !empty($proveedor))?$proveedor:'' ?></strong>. Se cuenta con suficiencia presupuestal en la partida correspondiente.
           </span>
        </div>
        
        <div  style="position:absolute; top:43.2%; left:49.5%; width:2%; height:20px; background-color:white; font-size: 10px; ">
            <span class="proxima">SI</span>
        </div>
        <div  style="position:absolute; top:46.8%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= $responsable->nombre_completo ?></span>
        </div>
        <div  style="position:absolute; top:49.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"> <?=$responsable->dsc_puesto?></span>
        </div>
         <div  style="position:absolute; top:52.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?=  $responsable->dsc_area ?></span>
        </div>
        <div  style="position:absolute; top:56.2%; left:49.5%; width:2%; height:20px; background-color:white; font-size: 10px; ">
            <span class="proxima">SI</span>
        </div>
      
        <div  style="position:absolute; top:60%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= $responsable->nombre_completo ?></span>
        </div>
         <div  style="position:absolute; top:62.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"> <?= $responsable->dsc_puesto?></span>
        </div>
         <div  style="position:absolute; top:65.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
             <span class="proxima"> <?= $responsable->dsc_area?></span>
        </div>
         <div  style="position:absolute; top:73%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= $responsable->nombre_completo ?></span>
        </div>
         <div  style="position:absolute; top:75.5%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
            <span class="proxima"> <?= $responsable->dsc_puesto?></span>
        </div>
         <div  style="position:absolute; top:78%; left:21.2%; width:30%; height:12px; background-color:white; font-size: 12px; ">
             <span class="proxima"> <?= $responsable->dsc_area ?></span>
        </div>

        <div  style="position:absolute; top:94.7%; left:21.2%; width:30%; height:10px; background-color:white; font-size: 10px; ">
          
           <span class="proxima"><?= $responsable->nombre_completo ?></span>
          
          
        </div>
         <div  style="position:absolute; top:96.8%; left:21.2%; width:35%; height:10px; background-color:white; font-size: 10px; ">
          
           <span class="proxima"><?= $responsable->dsc_puesto ?></span>
        
       
        </div>


        