 <div  style="position:absolute; text-align:center;  top:14%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute; text-align:center; top:16.2%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?'GO':'TP' ?> <?= strtoupper($folio);?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:18.5%; left:70%; width:22%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($reserva->partida) && !empty($reserva->partida))?$reserva->partida.'-'.$reserva->dsc_partida:''; ?></span>
        </div>
         <div  style="position:absolute; text-align:center; top:15.5%; left:22%; width:36%; height:30px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($reserva->razon_social) && !empty($reserva->razon_social))?$reserva->razon_social:'';?></span>
        </div>
      <div  style="position:absolute; text-align:center; top:20.7%; left:22%; width:66%; height:30px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($registro->concepto_pago) && !empty($registro->concepto_pago))?$registro->concepto_pago:''; ?></span>
        </div>
       <div  style="position:absolute; top:27.6%; left:22%; width:25%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($direccion->nombre_completo ) && !empty($direccion->nombre_completo ))?$direccion->nombre_completo :'';  ?></span>
        </div>
       <div  style="position:absolute; top:29.8%; left:22%; width:25%; height:10px; background-color:white; font-size: 7px; ">
            <span class="proxima"><?= (isset($direccion->dsc_area ) && !empty($direccion->dsc_area ))?$direccion->dsc_area :'';  ?></span>
        </div>
        <div  style="position:absolute; text-align:center;top:31.5%; left:7.5%; width:85%; height:25px; background-color:white; font-size:12px; ">
            <span class="proxima">Solicito que se realice trámite de pago del comprobante fiscal con folio 110943 ,
                 derivado del contrato ó convenio número <?= (isset($GO) && !empty($GO))?'GO':'TP' ?> <?= strtoupper($folio);?> por la cantidad de $1450
                 , por el servicio ___________ prestado por el proveedor ____________. Se cuenta con suficiencia presupuestal en la partida correspondiente.
           </span>
        </div>
        <div  style="position:absolute; text-align:center; top:50.5%; left:25%; width:70%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= strtoupper($registro->otros); ?></span>
        </div>


        