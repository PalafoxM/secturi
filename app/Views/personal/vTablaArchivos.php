

<?php $session = \Config\Services::session(); ?>
<div class="page-wrapper">

    <!-- Page Content-->
    <div class="page-content-tab">

        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Secturi</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">pago</a></li>
                                <li class="breadcrumb-item active">PAM</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Respaldo Documental</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
     
            <!--end row-->
            <div class="row">
                <div class="col-12">
                    <div class="tab-content detail-list" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="general_detail">
                            <div class="row">
                
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="header-title mt-0">Respaldo Documental</h4>
                                                <p class="text-muted mb-3"> Adjunto al PAM para Pago</p>
                                                <div class="table-responsive-sm">
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr>
                                                            <th class="text-center" scope="col">Nombre del documento</th>
                                                            <?php if($PT): ?>
                                                            <th class="text-center" scope="col">Pago a terceros</th>
                                                            <?php endif; ?>
                                                            <?php if($GO): ?>
                                                            <th class="text-center" scope="col">Gasto de operación</th>
                                                            <?php endif; ?>
                                                            <?php if($GRC): ?>
                                                            <th class="text-center" scope="col">Solicitud Gasto a Reserva de Comprobar</th>
                                                            <?php endif; ?>
                                                            <?php if($FIC): ?>
                                                            <th class="text-center" scope="col">Contenido FIC</th>
                                                            <?php endif; ?>
                                                            <th class="text-center" scope="col">Archivo</th>
                                                            </tr>
                                                        </thead>
                                                      <tbody>
                                                        <!-- Anexo 1 -->
                                                        <tr class="table-primary">
                                                            <th scope="row">Anexo 1</th>
                                                            <?php if($PT): ?>
                                                            <td>Reporte de integración documental (.xlsx).</td>
                                                            <?php endif; ?>
                                                             <?php if($GO): ?>
                                                            <td>Reporte de integración documental (.xlsx).</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>Reporte de integración documental (.xlsx).</td>
                                                            <?php endif; ?>
                                                             <?php if($FIC): ?>
                                                            <td>Reporte de integración documental (.xlsx).</td>
                                                            <?php endif; ?>
                                                            <td class="text-center">
                                                                <?php if($PT): ?>
                                                                <a target="_blank" href="<?= base_url().'index.php/Principal/Archivo/'.$id_registro.'/1' ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo01.pdf</h6>
                                                                </a> 
                                                                <?php endif; ?> 
                                                                <?php if($FIC): ?>
                                                                <a target="_blank" href="<?= base_url().'index.php/Principal/ArchivoFIC/'.$id_registro.'/1' ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo01.pdf</h6>
                                                                </a> 
                                                                <?php endif; ?> 
                                                                 <?php if($GO): ?>
                                                                <a target="_blank" href="<?= base_url().'index.php/Principal/ArchivoGO/'.$id_registro.'/1' ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo01.pdf</h6>
                                                                </a> 
                                                                <?php endif; ?>  
                                                            </td>
                                                        </tr>
                                                        
                                                        <!-- 01_Oficio de solicitud -->
                                                        <tr>
                                                            <th scope="row">01_Oficio de solicitud</th>
                                                            <?php if($PT): ?><td>N/A</td><?php endif; ?>
                                                            <?php if($GO): ?><td>N/A</td><?php endif; ?>
                                                            <?php if($GRC): ?><td>FINANCIEROS</td><?php endif; ?>
                                                            <?php if($FIC): ?><td>N/A</td><?php endif; ?>
                                                            <td class="text-center"></td>
                                                        </tr>

                                                        <!-- 02_Póliza -->
                                                        <tr>
                                                            <th scope="row">02_Póliza</th>
                                                            <?php if($PT): ?><td>FINANCIEROS</td><?php endif; ?>
                                                            <?php if($GO): ?><td>FINANCIEROS</td><?php endif; ?>
                                                            <?php if($GRC): ?><td>FINANCIEROS</td><?php endif; ?>
                                                            <?php if($FIC): ?><td>N/A</td><?php endif; ?>
                                                            <td class="text-center"></td>
                                                        </tr>

                                                        <!-- 03_CFDI -->
                                                        <tr class="table-secondary">
                                                            <th scope="row">03_CFDI</th>
                                                            <?php if($PT): ?>
                                                            <td>Comprobante Fiscal Digital por Internet o comprobante que ampara el pago (.pdf).</td>
                                                            <?php endif; ?>
                                                             <?php if($GO): ?>
                                                            <td>Comprobante Fiscal Digital por Internet o comprobante que ampara el pago (.pdf).</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>N/A</td>
                                                            <?php endif; ?>
                                                             <?php if($FIC): ?>
                                                            <td>Comprobante Fiscal Digital por Internet o comprobante que ampara el pago (.pdf).</td>
                                                            <?php endif; ?>
                                                            <td class="text-center">
                                                                <?php if($PT): ?>
                                                                <a style="cursor:pointer;" onclick="ini.inicio.links(<?=$id_registro?>);" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo03.pdf</h6>
                                                                </a>  
                                                                <?php endif; ?> 
                                                                <?php if($FIC): ?>
                                                                <a style="cursor:pointer;" onclick="ini.inicio.links(<?=$id_registro?>);" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo03.pdf</h6>
                                                                </a>  
                                                                <?php endif; ?> 
                                                                 <?php if($GO): ?>
                                                                <a style="cursor:pointer;" onclick="ini.inicio.linksGo(<?=$id_registro?>);" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo03.pdf</h6>
                                                                </a>  
                                                                <?php endif; ?> 
                                                            </td>
                                                        </tr>

                                                        <!-- 04_Contrato o convenio -->
                                                        <tr>
                                                            <th scope="row">04_Contrato o convenio</th>
                                                             <?php if($PT): ?>
                                                            <td>Instrumento jurídico correspondiente (.pdf).</td>
                                                              <?php endif; ?>
                                                              <?php if($GO): ?>
                                                            <td>N/A</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>N/A</td>
                                                            <?php endif; ?>
                                                            <?php if($FIC): ?><td>N/A</td><?php endif; ?>
                                                            <td class="text-center">
                                                                <?php if($PT): ?>
                                                                <a target="_blank"  href="<?= base_url().'index.php/Principal/Archivo/'.$id_registro.'/4' ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo04.pdf</h6>
                                                                </a>  
                                                                <?php endif; ?> 
                                                                <?php if($GO): ?>
                                                                <a target="_blank"  href="<?= base_url().'index.php/Principal/ArchivoGO/'.$id_registro.'/4' ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo04.pdf</h6>
                                                                </a>  
                                                                <?php endif; ?> 
                                                            </td>
                                                        </tr>

                                                        <!-- 09_GRC-1 Gastos a Reserva de Comprobar -->
                                                        <tr>
                                                            <th scope="row">09_GRC-1 Gastos a Reserva de Comprobar</th>
                                                            <?php if($PT): ?><td>N/A</td><?php endif; ?>
                                                            <?php if($GO): ?><td>N/A</td><?php endif; ?>
                                                            <?php if($GRC): ?><td>Solicitud de Gasto a Reserva de Comprobar GRC-1 (.pdf).</td><?php endif; ?>
                                                            <?php if($FIC): ?><td>N/A</td><?php endif; ?>
                                                            <td class="text-center">
                                                                <input type="file" id="archivo09" name="archivo09[]" multiple accept=".pdf">
                                                            </td>
                                                        </tr>

                                                        <!-- 10_Oficio de Autorización de Partidas Restringidas -->
                                                        <tr>
                                                            <th scope="row">10_Oficio de Autorización de Partidas Restringidas</th>
                                                            <?php if($PT): ?><td>Oficio de Autorización de la Subsecretaría de Administración para el ejercicio de partidas restringidas (3710, 3760, 3810, 3820, 3830, 3310, 3330, 3340, y 3390), debidamente firmado (.pdf).</td><?php endif; ?>
                                                            <?php if($GO): ?><td>Oficio de Autorización de la Subsecretaría de Administración para el ejercicio de partidas restringidas (3710), Oficio de Autorización de la DGRMSG (2610) debidamente firmado (.pdf).</td><?php endif; ?>
                                                            <?php if($GRC): ?><td>Oficio de Autorización de la Subsecretaría de Administración para el ejercicio de partidas restringidas (3710, 3760, 3810, 3820, 3830, 3310, 3330, 3340, y 3390), debidamente firmado (.pdf).</td><?php endif; ?>
                                                            <?php if($FIC): ?><td>N/A</td><?php endif; ?>
                                                            <td class="text-center">
                                                                 <input type="file" id="archivo10" name="archivo10[]" accept=".pdf" >
                                                            </td>
                                                        </tr>

                                                        <!-- 11_Oficio de Autorización de Comunicación Social -->
                                                        <tr>
                                                            <th scope="row">11_Oficio de Autorización de Comunicación Social</th>
                                                            <?php if($PT): ?><td>Oficio de Autorización emitido por la Coordinación General de Comunicación Social para el ejercicio de partidas de difusión (3611, 3612, 3639, 3660 y 3690), debidamente firmado (.pdf).</td><?php endif; ?>
                                                            <?php if($GO): ?><td>N/A</td><?php endif; ?>
                                                            <?php if($GRC): ?><td>N/A</td><?php endif; ?>
                                                            <?php if($FIC): ?><td>N/A</td><?php endif; ?>
                                                            <td class="text-center">
                                                                 <input type="file" id="archivo11" name="archivo11[]" accept=".pdf" >
                                                            </td>
                                                        </tr>

                                                        <!-- 12_Oficio de Autorización de Pasivos -->
                                                        <tr>
                                                            <th scope="row">12_Oficio de Autorización de Pasivos</th>
                                                            <?php if($PT): ?><td>Oficio de Autorización de Pasivos (.pdf).</td><?php endif; ?>
                                                            <?php if($GO): ?><td>N/A</td><?php endif; ?>
                                                            <?php if($GRC): ?><td>N/A</td><?php endif; ?>
                                                            <?php if($FIC): ?><td>N/A</td><?php endif; ?>
                                                            <td class="text-center">
                                                                 <input type="file" id="archivo12" name="archivo12[]" accept=".pdf" >
                                                            </td>
                                                        </tr>

                                                        <!-- 14_Otros -->
                                                        <tr class="table-warning">
                                                            <th scope="row">14_Otros</th>
                                                            <?php if($PT): ?>
                                                            <td>Se creará una carpeta que contenga cada uno de los siguientes archivos (.pdf), según apliquen y por separado: Carátula de pago, oficio delegatorio, oficio de liberación de pago, CFDI con su respectivo encabezado, oficio de comisión, evidencia fotográfica, soporte de datos bancarios, registro en el padrón de proveedores, garantía, CURP en caso de ayudas y subsidios a persona físicas, listado de beneficiarios, minutas, programas, agendas, itinerarios, lista de asistencia, etc.</td>
                                                            <?php endif; ?>
                                                            <?php if($GO): ?>
                                                            <td>Se creará una carpeta que contenga cada uno de los siguientes archivos (.pdf), según apliquen y por separado: Carátula de pago, oficio delegatorio, oficio de liberación de gasto, un archivo por cada CFDI (con su respectivo encabezado, notas de consumo, oficio de comisión, check in-out, vouchers, tickets, programas, agendas, itinerarios, evidencia fotográfica, recibo de verificación, lista de asistencia, etc.) y formato de desglose de gastos (viáticos por persona).</td>
                                                            <?php endif; ?>
                                                            <?php if($GRC): ?>
                                                            <td>Se creará una carpeta que contenga cada uno de los siguientes archivos (.pdf), según apliquen y por separado: oficio de comisión, invitaciones, programas, agendas, itinerarios, etc., y formato de desglose de gastos (viáticos por persona).</td>
                                                            <?php endif; ?>
                                                            <?php if($FIC): ?><td>Soporte de datos bancarios, Registro en el Padrón de Proveedores...</td><?php endif; ?>
                                                            <td><input type="file" id="archivo14" name="archivo14[]" multiple accept=".zip, .pdf, .rar"></td>
                                                        </tr>
                                                    </tbody>

                                                    </table><!--end /table-->
                                                    <br>
                                                       <div class="row mb-5">
                                                            <div class="col-md-12 text-right">
                                                                <?php if($FIC): ?>
                                                                <a id="btnZip" style="color:white" class="btn btn-info" onclick="ini.inicio.generarZipFIC(<?= $id_registro ?>);" >
                                                                    <i class="mdi mdi-content-save"></i> Generar Zip
                                                                </a>
                                                                <?php endif; ?>
                                                                <?php if($PT): ?>
                                                                <a id="btnZip" style="color:white" class="btn btn-info" onclick="ini.inicio.generarZip(<?= $id_registro ?>);" >
                                                                    <i class="mdi mdi-content-save"></i> Generar Zip
                                                                </a>
                                                                <?php endif; ?>
                                                                <?php if($GO): ?>
                                                                <a id="btnZip" style="color:white" class="btn btn-info" onclick="ini.inicio.generarZipGO(<?= $id_registro ?>);" >
                                                                    <i class="mdi mdi-content-save"></i> Generar Zip
                                                                </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                </div><!--end /tableresponsive-->
                                            </div><!--end card-body-->
                                        </div><!--end card-->
                                    </div><!--end col-->
                             
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- container -->
    </div>
    <!-- end page content -->
</div>


<div class="modal fade" id="modalLinks" tabindex="-1" aria-labelledby="modalEliminarReservaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalEliminarReservaLabel">Agregar Estatus Reserva</h5>
        <button type="button" onclick="ini.inicio.cerrarModalLink()" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
     
      <div class="modal-body">
        <form id="formEliminarReserva">
          <input type="hidden" id="id_reserva_estatus" name="id_reserva_estatus">
           <div class="row">
              <div class="col-lg-12">
                  <div class="card">
                      <div class="card-body">        

                        <div class="row" id="links">
                                                                                                                                                  
                         </div>
                    
                  </div><!--end card-->
              </div><!--end col-->
          </div><!--end col-->    
        
         
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" onclick="ini.inicio.cerrarModalLink()" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>





<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/pages/jquery.analytics_customers.init.js"></script>



<script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>
<script>
$(document).ready(function() {

    $('#datatableReserva').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
        // Función debounce para retrasar la ejecución
    });



</script>