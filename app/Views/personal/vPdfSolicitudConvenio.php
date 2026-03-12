<style>
body { font-family: sans-serif; font-size: 11px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
th, td { border: 1px solid #000; padding: 5px; text-align: left; }
.text-center { text-align: center; }
.bg-primary { background-color: #0b2e59; color: white; font-weight: bold; }
</style>
<h3 class="text-center">SOLICITUD DE ELABORACIÓN DE CONVENIO</h3>

<table>
    <tr><td colspan="2" class="bg-primary text-center">INFORMACIÓN DEL ÁREA SOLICITANTE</td></tr>
    <tr>
        <td width="30%"><strong>Responsable del Proyecto:</strong></td>
        <td><?= isset($solicitud->nombre_proyecto) ? $solicitud->nombre_proyecto : '' ?></td>
    </tr>
    <tr>
        <td><strong>Responsable de Seguimiento:</strong></td>
        <td><?= isset($solicitud->nombre_seguimiento) ? $solicitud->nombre_seguimiento : '' ?></td>
    </tr>
</table>

<table>
    <tr><td colspan="4" class="bg-primary text-center">INFORMACIÓN PRESUPUESTAL</td></tr>
    <tr>
        <th>Proyecto</th>
        <th>Partida</th>
        <th>Suficiencia Presupuestal</th>
    </tr>
    <tr>
        <td><?= isset($solicitud->dsc_proyecto) ? $solicitud->dsc_proyecto : '' ?></td>
        <td><?= isset($solicitud->cuenta_cable) ? $solicitud->cuenta_cable : '' ?></td>
        <td>Anexa: <?= $solicitud->archivo_suficiencia ? 'Sí' : 'No' ?></td>
    </tr>
</table>

<table>
    <tr><td colspan="2" class="bg-primary text-center">APORTACIONES AL CONVENIO</td></tr>
    <tr><td width="50%"><strong>Monto SECTURI:</strong></td><td><?= isset($solicitud->monto_secturi) ? $solicitud->monto_secturi : '' ?></td></tr>
    <tr><td><strong>Monto Federal:</strong></td><td><?= isset($solicitud->monto_federal) ? $solicitud->monto_federal : '' ?></td></tr>
    <tr><td><strong>Monto Otra Dependencia:</strong></td><td><?= isset($solicitud->monto_otra) ? $solicitud->monto_otra : '' ?></td></tr>
    <tr><td><strong>Monto Total:</strong></td><td><?= isset($solicitud->monto_total) ? $solicitud->monto_total : '' ?></td></tr>
</table>

<table>
    <tr><td class="bg-primary text-center">DESCRIPCIÓN DE ACCIONES A CONVENIR</td></tr>
    <tr><td><?= isset($solicitud->objeto_convenio) ? $solicitud->objeto_convenio : '' ?></td></tr>
</table>

<table>
    <tr><td colspan="4" class="bg-primary text-center">VIGENCIA Y MINISTRACIONES</td></tr>
    <tr>
        <td><strong>Fecha Inicio:</strong> <?= isset($solicitud->fecha_inicio) ? date('d/m/Y', strtotime($solicitud->fecha_inicio)) : '' ?></td>
        <td colspan="3"><strong>Fecha Término:</strong> <?= isset($solicitud->fecha_termino) ? date('d/m/Y', strtotime($solicitud->fecha_termino)) : '' ?></td>
    </tr>
    <tr>
        <th>Ministración</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Entregable</th>
    </tr>
    <?php if(!empty($pagos)): ?>
        <?php foreach($pagos as $p): ?>
        <tr>
            <td><?= $p->numero_pago ?></td>
            <td>$<?= number_format((float)$p->monto, 2) ?></td>
            <td><?= $p->fecha ?></td>
            <td><?= $p->entregable ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4" class="text-center">Sin ministraciones</td></tr>
    <?php endif; ?>
</table>

<table>
    <tr><td colspan="2" class="bg-primary text-center">INFORMACIÓN DEL PROVEEDOR/CONVENIO</td></tr>
    <tr><td width="30%"><strong>Nombre / Razón Social:</strong></td><td><?= isset($solicitud->proveedor_nombre) ? $solicitud->proveedor_nombre : '' ?></td></tr>
    <tr><td><strong>Domicilio fiscal:</strong></td><td><?= isset($solicitud->proveedor_domicilio) ? $solicitud->proveedor_domicilio : '' ?></td></tr>
    <tr><td><strong>Cédula de Reg. Proveedores:</strong></td><td><?= isset($solicitud->proveedor_cedula) ? $solicitud->proveedor_cedula : '' ?></td></tr>
    <tr><td><strong>RFC:</strong></td><td><?= isset($solicitud->proveedor_rfc) ? $solicitud->proveedor_rfc : '' ?></td></tr>
    <tr><td><strong>Representante Legal:</strong></td><td><?= isset($solicitud->proveedor_representante) ? $solicitud->proveedor_representante : '' ?></td></tr>
    <tr><td><strong>Nombre quien asiste Rep.:</strong></td><td><?= isset($solicitud->proveedor_asistente) ? $solicitud->proveedor_asistente : '' ?></td></tr>
    <tr><td><strong>Resp. Seguimiento:</strong></td><td><?= isset($solicitud->proveedor_seguimiento) ? $solicitud->proveedor_seguimiento : '' ?></td></tr>
    <tr><td><strong>Correo Electrónico:</strong></td><td><?= isset($solicitud->proveedor_correo) ? $solicitud->proveedor_correo : '' ?></td></tr>
</table>

<br>
<table>
    <tr><td colspan="4" class="bg-primary text-center">SOPORTE DOCUMENTAL</td></tr>
    <tr>
        <th width="5%">No.</th>
        <th width="75%">CONCEPTO</th>
        <th width="10%">APLICA</th>
        <th width="10%">NO APLICA</th>
    </tr>
    <?php 
    $documentos = [
        1 => 'Acta de Sesión de Comité',
        2 => 'Dictamen',
        3 => 'Validaciones',
        4 => 'Propuesta de Acciones',
        5 => 'Autorización de Tratamiento de Datos Personales en Posesión de Sujetos Obligados',
        6 => 'Escritura Constitutiva y sus modificaciones si es el caso, inscritas en el Registro Público que corresponda',
        7 => 'Poder del Representante Legal; tratándose de municipios (Acta en la que se autoriza al Presidente a firmar Contratos y Convenios; Acta o nombramiento en la que se designe al Secretario del Ayuntamiento y Nombramiento del Responsable de Seguimiento)',
        8 => 'Identificación Oficial (Representante Legal)',
        9 => 'Constancia de Situación Fiscal (R.F.C.); en el caso de persona extranjera remitir el documento análogo en su país de origen.',
        10 => 'Comprobante de domicilio vigente, en caso de que el domicilio convencional sea distinto al domicilio fiscal.',
        11 => 'Opinión de Cumplimiento de Obligaciones Fiscales en sentido positivo-SAT ($300,000.00) / Manifestación de Cumplimiento de Obligaciones Fiscales-Formato (menos de $300,000.00)',
        12 => 'Carta de declaración de intereses; (no será exigible en los convenios celebrados con Municipios).',
        13 => 'Manifestación de que no se encuentra impedido legalmente, bajo ningún supuesto, que pueda afectar las estipulaciones establecidas en el presente instrumento jurídico.',
        14 => 'Manifiesto de contar con infraestructura (formato E); (no será exigible en los convenios celebrados con Municipios).'
    ];
    foreach($documentos as $key => $doc): ?>
    <tr>
        <td class="text-center"><?= $key ?></td>
        <td><?= $doc ?></td>
        <td></td>
        <td></td>
    </tr>
    <?php endforeach; ?>
</table>

