

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
                                                            <th class="text-center" scope="col">Nombre</th>
                                                            <?php if($PT): ?>
                                                            <th class="text-center" scope="col">Contenido PT</th>
                                                            <?php endif; ?>
                                                            <?php if($GO): ?>
                                                            <th class="text-center" scope="col">Contenido GO</th>
                                                            <?php endif; ?>
                                                            <?php if($GRC): ?>
                                                            <th class="text-center" scope="col">Contenido GRC</th>
                                                            <?php endif; ?>
                                                            <th class="text-center" scope="col">Archivo</th>
                                                            </tr>
                                                        </thead>
                                                      <tbody>
                                                        <tr class="table-primary">
                                                            <th scope="row">01. Anexos y formato de los LTPOFB</th>
                                                            <?php if($PT): ?>
                                                            <td>Anexo 2 - Reporte de Integración Documental y Formato de conformidad del producto recibido.</td>
                                                            <?php endif; ?>
                                                             <?php if($GO): ?>
                                                            <td>Anexo 2 - Reporte de Integración Documental</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>Anexo 2 - Reporte de Integración Documental</td>
                                                            <?php endif; ?>
                                                            <td class="text-center">
                                                                <?php if($PT): ?>
                                                                <a target="_blank" href="<?= base_url().'index.php/Principal/Archivo/'.$id_registro.'/1' ?>" class="text-center">
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
                                            
                                                        <tr class="table-secondary">
                                                            <th scope="row">03. CFDI</th>
                                                            <?php if($PT): ?>
                                                            <td>Comprobante Fiscal Digital por Internet en su representación PDF</td>
                                                            <?php endif; ?>
                                                             <?php if($GO): ?>
                                                            <td>Comprobante Fiscal Digital por Internet en su representación PDF</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>N/A</td>
                                                            <?php endif; ?>
                                                            <td class="text-center">
                                                                <?php if($PT): ?>
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
                                                          <?php if(!$GO): ?>
                                                        <tr>
                                                            <th scope="row">04. Contrato o Convenio (según corresponda)</th>
                                                             <?php if($PT): ?>
                                                            <td>Instrumento jurídico que corresponda</td>
                                                              <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>N/A</td>
                                                            <?php endif; ?>
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
                                                          <?php endif; ?>
                                                        <tr class="table-success">
                                                            <th scope="row">05. Formatos de los LRADP</th>
                                                              <?php if($PT): ?>
                                                            <td>OMVE-1 (Orden de Ministración de Viáticos en el Extranjero)</td>
                                                              <?php endif; ?>
                                                               <?php if($GO): ?>
                                                            <td>OMVE-1 (Orden de Ministración de Viáticos Nacionales)</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>Solicitud de gastos a reserva de comprobar GRC-1 y OMVE-1 (Orden de Ministración de Viáticos en el Extranjero)</td>
                                                            <?php endif; ?>
                                                            <td class="text-center">
                                                             <input type="file" id="archivo05" name="archivo05[]" accept=".pdf" >
                                                            </td>
                                                        </tr>
                                                          <?php if(!$GO): ?>
                                                        <tr>
                                                            <th scope="row">06. Oficios de Autorizaciones</th>
                                                             <?php if($PT): ?>
                                                            <td>Autorización de Servicios Profesionales (1330, 3330, 3340 y 3390), Validación de la Coordinación General de Comunicación Social (3611, 3612, 3630, 3660 y 3690), Validación de imagen, Autorización de partidas restringidas (3710, 3760, 3810, 3820 y 3830), Autorización de pasivos, Autorización de refrendos.</td>
                                                           <?php endif; ?>
                                                               <?php if($GRC): ?>
                                                            <td>Autorización de Servicios Profesionales (3310, 3330, 3340 y 3390), Autorización de partidas restringidas (3710, 3760, 3810 y 3830)</td>
                                                           <?php endif; ?>
                                                            <td><input type="file" id="archivo06" name="archivo06[]" accept=".pdf" ></td>
                                                        </tr>
                                                         <?php endif; ?>
                                                        <tr class="table-info">
                                                            <th scope="row">07. Formatos diversos</th>
                                                             <?php if($PT): ?>
                                                            <td>Carátula de pago, Oficio(s) Delegatorio(s), Oficio de Liberación de Pago, CFDI con su respectivo encabezado de factura, Oficio de Comisión, Evidencia Fotográfica, Lista de Asistencia.</td>
                                                              <?php endif; ?>
                                                               <?php if($GO): ?>
                                                            <td>Carátula de pago, Oficio(s) Delegatorio(s), Oficio de Liberación de Gastos, CFDI con su respectivo encabezado de factura (incluir notas de consumo, check-in/out vouchers, tickets de peajes), Formato de Desglose de Gastos (Viáticos por persona), Oficio de Comisión, Evidencia Fotográfica, Lista de Asistencia.</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>Formato de Desglose de Gastos (Viáticos por persona presupuestado), Oficio de Comisión.</td>
                                                            <?php endif; ?>
                                                           <td class="text-center">
                                                               <?php if($PT): ?>
                                                                <a target="_blank"  href="<?= base_url().'index.php/Principal/ImprimirPT/'.$id_registro ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo07.pdf</h6>
                                                                </a> 
                                                                <?php endif; ?>  
                                                               <?php if($GO): ?>
                                                                <a target="_blank"  href="<?= base_url().'index.php/Principal/ImprimirGO/'.$id_registro ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo07.pdf</h6>
                                                                </a> 
                                                                <?php endif; ?>  
                                                            </td>
                                                        </tr>
                                                          <?php if(!$GO): ?>
                                                        <tr>
                                                            <th scope="row">08. Evidencia de entregable</th>
                                                             <?php if($PT): ?>
                                                            <td>Entregable con sello, fecha y firma de recibido</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>N/A</td>
                                                            <?php endif; ?>
                                                            <td><input type="file" id="archivo08" name="archivo08[]" accept=".pdf" ></td>
                                                        </tr>
                                                          <?php endif; ?>
                                                        <tr class="table-warning">
                                                            <th scope="row">09. Otros</th>
                                                             <?php if($PT): ?>
                                                            <td>Soporte de datos bancarios, Registro en el Padrón de Proveedores (vigente), Soporte documental inherente al trámite (Garantía, CURP; en caso de ayudas y subsidios a personas físicas: listado de beneficiarios, minutas, programas, agendas, itinerarios, etc.).</td>
                                                            <?php endif; ?>
                                                             <?php if($GO): ?>
                                                            <td>Programas, agendas, itinerarios, recibo de verificación, etc.</td>
                                                            <?php endif; ?>
                                                              <?php if($GRC): ?>
                                                            <td>Programas, agendas, itinerarios, etc.</td>
                                                            <?php endif; ?>
                                                            <td><input type="file" id="archivo09" name="archivo09[]" multiple accept=".pdf"></td>
                                                        </tr>
                                                    </tbody>

                                                    </table><!--end /table-->
                                                    <br>
                                                       <div class="row mb-5">
                                                            <div class="col-md-12 text-right">
                                                                <a id="btnZip" style="color:white" class="btn btn-info" onclick="ini.inicio.generarZip(<?= $id_registro ?>);" >
                                                                    <i class="mdi mdi-content-save"></i> Generar Zip
                                                                </a>
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