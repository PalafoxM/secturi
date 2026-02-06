

<style>
    #encabezado{
       position:absolute; 
       top:5.5%; 
       left:8%; 
       width:84.5%; 
       background-color:black;
      font-size: 14px;
        height:25px; 
        color:white;
        text-align:center;
         padding-left: 4px;     /* Espacio a la izquierda */
         padding-top: 7px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #responsable{
       position:absolute; 
       top:8.6%; 
       left:8%; 
       width: 19.5%; 
       border:solid 1px black;
       font-size: 9px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
        padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
      #nombre{
       position:absolute; 
       top:8.6%; 
       left:28.1%; 
       width: 64.15%; 
       border:solid 1px black;
       font-size: 9px;
        height:18px; 
       text-align:left;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;

    }
    #concepto{
       position:absolute; 
       top:12.5%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 9px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #comision{
       position:absolute; 
       top:10.65%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 9px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
        padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;

    }
    #partida{
       position:absolute; 
       top:14.4%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 9px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #factura{
       position:absolute; 
       top:16.3%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 9px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #fecha{
       position:absolute; 
       top:18.2%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 9px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #importe{
       position:absolute; 
       top:20.1%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 9px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
  
    #comision_respuesta{
       position:absolute; 
       top:10.65%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
       font-size: 9px;
        height:18px; 
        text-align:left;
    }
    #concepto_respuesta{
       position:absolute; 
       top:12.5%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
       font-size: 9px;
        height:18px; 
        text-align:left;
    }
    #partida_respuesta{
       position:absolute; 
       top:14.4%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
       font-size: 9px;
        height:18px; 
        text-align:left;
       /* background-color: red;*/
    }
    #factura_respuesta{
       position:absolute; 
       top:16.3%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
       font-size: 9px;
        height:18px; 
        text-align:left;

        
     
        
    }
    #fecha_respuesta{
      position:absolute; 
      top:18.1%; 
      left:28.1%; 
      width: 64.15%;
      padding-left: 4px;     /* Espacio a la izquierda */
      padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
      box-sizing: border-box;
      border:solid 1px black;
      font-size: 9px;
      height:18px; 
      text-align:left;
    }
    #importe_respuesta{
       position:absolute; 
       top:20.1%; 
       left:28.1%; 
       border:solid 1px black;
       font-size: 9px;
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
   <?php if(isset($responsableGasto->nombre_completo) && !empty($responsableGasto->nombre_completo)):  ?>
   <span ><?= $responsableGasto->nombre_completo ?> - <?= $responsableGasto->dsc_puesto ?> - <?= $responsableGasto->dsc_area; ?> </span>
    <?php endif; ?>
</div>
<div id="comision">
   COMISION / REUNION / EVENTO:
</div>
 <div id="comision_respuesta">
   <span > &nbsp;<?= (isset($comision) && !empty($comision)) ? $comision : $registro->comision; ?>  </span>
</div>
<div id="concepto">
   CONCEPTO DEL PAGO:
</div>
 <div id="concepto_respuesta">
   <span> &nbsp;<?= (isset($concepto))?$concepto:''; ?>  </span>
</div>
<div id="partida">
   PARTIDA:
</div>
<div id="partida_respuesta">
 
  <?= (isset($partida) && !empty($partida))?$partida:''; ?> <?= (isset($encabezado) && !empty($encabezado))? ' - ' . $encabezado:''; ?>

</div>
<div id="factura">
   FACTURA / RECIBO No: 
</div>
<div id="factura_respuesta">
   <?= (isset($uuid) && !empty($uuid))?$uuid:''; ?>
</div>
<div id="fecha">
   FECHA DEL GASTO:
</div>

 <div id="fecha_respuesta">
   <?php if(isset($inicio) && !empty($fin)): ?>
   <span > DEL <?= date('d-m-Y', strtotime($inicio));?> AL <?= date('d-m-Y',strtotime($fin));?>  </span>
   <?php endif; ?>
</div>


<div id="importe">
   IMPORTANTE EN PESOS (MXN):
</div>

<div id="importe_respuesta">
    <?php if(isset($total2) && !empty($total2)): ?>
        <span><?= '$' . number_format($total2, 2) .' ('.$monto2.')' ?></span>
    <?php elseif(isset($total) && !empty($total)): ?>
        <span><?= '$' . number_format($total, 2) .' ('.$monto.')' ?></span>
    <?php else: ?>
        <span>No disponible</span>
    <?php endif; ?>
</div>

