
        <div  style="position:absolute; text-align:center; top:18.2%; left:13%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">21</span>
        </div>
        <div  style="position:absolute; text-align:center; top:18.2%; left:45.5%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($vehiculo->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute;text-align:center; top:18.2%; left:70.5%; width:25%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">PT <?= $folio.'-V';?></span>
        </div>
      
        <div  style="position:absolute; top:36.2%; left:60.7%; width:35%; background-color:white; font-size: 12px;  height:12px;">
      
            <span class="proxima "> <?= $proveedor;?> </span>
         
        </div>
         <div  style="position:absolute; top:37.4%; left:73.5%; width:20%; background-color:white; font-size: 9px;  height:12px;">
            <span >  <?= $no_proveedor ?> </span>
        </div>
        <div  style="position:absolute; top:38.5%; left:64%; width:33%; background-color:white; font-size: 9px;  height:12px;">
            <span class="proxima "> <?= $rfc ?> </span>
        </div>
        <div  style="position:absolute; top:40.8%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>NOMBRE DEL PROVEEDOR :</STRONG> <?= $proveedor;?> </span>
        
        </div>
          <div  style="position:absolute; top:42%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><strong>NO. CUENTA : <?= isset($proveedorBanco->no_cuenta) && !empty($proveedorBanco->no_cuenta)?$proveedorBanco->no_cuenta:'' ?> </span>
        </div>
        <div  style="position:absolute; top:43%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>BANCO: </STRONG><?= isset($proveedorBanco->banco) && !empty($proveedorBanco->banco)?$proveedorBanco->banco:'' ?></span>
        </div>
         <div  style="position:absolute; top:44.3%; left:60.8%; width:36%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><STRONG>CLABE:</STRONG> <?= isset($proveedorBanco->clabe) && !empty($proveedorBanco->clabe)?$proveedorBanco->clabe:'' ?></span>
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
                       <?= number_format($vehiculo->xml_monto, 2)  ?>
                    </span>
              </div>
              <div style="position:absolute; text-align:center; top:34%; left:2.7%; width:13.8%; background-color:white; font-size: 14px;  height:32px;">
                    <span class="proxima ">  <?= $vehiculo->xml_uuid ?> </span>
                </div>
    

   
        <div  style="position:absolute; text-align:right; top:52.2%; left:44%; width:15%; background-color:white; font-size: 12px;  height:12px;">
            <span> <?= number_format($vehiculo->xml_monto, 2)  ?> </span>
        </div>
        <div  style="position:absolute; text-align:center; top:51.8%; left:60%; width:37.5%; background-color:white; font-size: 12px;  height:20px;">
            <span class="proxima "><strong><?= ($numero_texto); ?></strong></span>
        </div>
         <div  style="position:absolute; top:48.1%; left:22%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= (isset($reserva[0]->no_convenio) && !empty($reserva[0]->no_convenio))?strtoupper($reserva[0]->no_convenio):'' ?></span>
        </div>
         <div  style="position:absolute; top:48.2%; left:22%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $vehiculo->convenio; ?></span>
        </div>
         <div  style="position:absolute; top:49.4%; left:12%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">S/N</span>
        </div>
         <div  style="position:absolute;  text-align:center; top:67.7%; left:3.5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= 'LIC. RODRIGO GONZÁLEZ GUERRERO' ?></span>
        </div>
  
         <div  style="position:absolute;  text-align:center; top:67.7%; left:35.5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= $secretario->dsc_secretario ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:68.9%; left:35.5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><strong> <?= $secretario->dsc_puesto ?></strong></span>
        </div>
       
         <div  style="position:absolute;  text-align:center; top:67.7%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:25px;">
            <span class="proxima "> </span>
        </div>
 

         <div  style="position:absolute;  text-align:center; top:79%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= (isset($resposableGasto->nombre_completo) && !empty($resposableGasto->nombre_completo))?$resposableGasto->nombre_completo:'' ?> </span>
        </div>
         <div  style="position:absolute;  text-align:center; top:80.4%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> <?= (isset($resposableGasto->dsc_puesto) && $resposableGasto->dsc_puesto)?$resposableGasto->dsc_puesto:'' ?> </span>
        </div>

        
        
       
   
    
