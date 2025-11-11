 
 
 <style>
    #encabezado{
       position:absolute; 
       top:5%; 
       left:8%; 
       width:84.5%; 
       background-color:black;
      font-size: 8px;
        height:18px; 
        color:white;
        text-align:center;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #responsable{
       position:absolute; 
       top:6.77%; 
       left:8%; 
       width: 19.5%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
        padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #concepto{
       position:absolute; 
       top:10.5%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #comision{
       position:absolute; 
       top:8.65%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
        padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;

    }
    #partida{
       position:absolute; 
       top:12.4%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
      font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #factura{
       position:absolute; 
       top:14.3%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #fecha{
       position:absolute; 
       top:16.2%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #importe{
       position:absolute; 
       top:18.1%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #nombre{
       position:absolute; 
       top:6.62%; 
       left:28.1%; 
       width: 64.15%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
       text-align:left;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;

    }
    #comision_respuesta{
       position:absolute; 
       top:8.65%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
    }
    #concepto_respuesta{
       position:absolute; 
       top:10.5%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
    }
    #partida_respuesta{
       position:absolute; 
       top:12.4%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       /* background-color: red;*/
    }
    #factura_respuesta{
       position:absolute; 
       top:14.3%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;

        
     
        
    }
    #fecha_respuesta{
      position:absolute; 
      top:16.1%; 
      left:28.1%; 
      width: 64.15%;
      padding-left: 4px;     /* Espacio a la izquierda */
      padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
      box-sizing: border-box;
      border:solid 1px black;
      font-size: 7px;
      height:18px; 
      text-align:left;
    }
    #importe_respuesta{
       position:absolute; 
       top:18.1%; 
       left:28.1%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
        width: 64.15%;
        padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }

 </style>
 <div id="encabezado">
    <strong>ENCABEZADO DE FACTURA</strong>
</div>
<div id="responsable">
   RESPONSABLE / CARGO / AREA:
</div>
 <div id="nombre">

   <?php if(isset($solicitud->nombre_completo) && !empty($solicitud->nombre_completo)):  ?>
   <span ><?= $solicitud->nombre_completo; ?> - <?= $solicitud->dsc_puesto ?> - <?= $solicitud->dsc_area ?> </span>
    <?php endif; ?>


</div>
<div id="comision">
   COMISION / REUNION / EVENTO:
</div>
 <div id="comision_respuesta">
   <span > &nbsp;<?= ($vehiculo->comision); ?>  </span>
</div>
<div id="concepto">
   CONCEPTO DEL PAGO:
</div>
 <div id="concepto_respuesta">
   <span> &nbsp;<?= ($vehiculo->concepto); ?>  </span>
</div>
<div id="partida">
   PARTIDA:
</div>


<div id="partida_respuesta">
   3550
</div>

<div id="factura">
   FACTURA / RECIBO No: 
</div>
<div id="factura_respuesta">
<?= $vehiculo->xml_uuid ?>
</div>
<div id="fecha">
   FECHA DEL GASTO:
</div>

 <div id="fecha_respuesta">
   <?php if(isset($vehiculo->fec_inicio) && !empty($vehiculo->fec_fin)): ?>
   <span > DEL <?= date('d-m-Y', strtotime($vehiculo->fec_inicio));?> AL <?= date('d-m-Y',strtotime($vehiculo->fec_fin));?>  </span>
   <?php endif; ?>
</div>


<div id="importe">
   IMPORTANTE EN PESOS (MXN):
</div>

<div id="importe_respuesta">
<?= $vehiculo->xml_monto.' ('.$numero_texto.')' ?>
</div>

