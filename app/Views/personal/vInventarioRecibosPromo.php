<div class="page-wrapper">
  <div class="page-content-tab">
    <div class="container-fluid">

      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-3">

              <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h4 class="header-title mt-0 mb-0 text-dark">
                  Consultar Recibos —
                  <strong>
                    <?= $materiales ? ($materiales->convenio . " - " . ($materiales->razon_social ?? '')) : 'Contrato no encontrado' ?>
                  </strong>
                </h4>

                <div class="d-flex">
                  <a class="btn btn-secondary shadow-sm mr-2"
                     href="<?= base_url('index.php/Inicio/InventarioPromocion/' . intval($id_convenio_promo ?? $id_convenio ?? 0)) ?>">
                    ⬅ Volver a Inventario
                  </a>

                  <a class="btn btn-primary shadow-sm"
                     href="<?= base_url('index.php/Inicio/FormularioPromoPorConvenio/' . intval($id_convenio_promo ?? $id_convenio ?? 0)) ?>">
                    ➕ Nuevo recibo
                  </a>
                </div>
              </div>

              <div class="table-responsive">
                <table id="tablaRecibos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%;">
                  <thead class="thead-light">
                    <tr class="text-center">
                      <th>Folio</th>
                      <th>Fecha</th>
                      <th>Solicitante</th>
                      <th>Concepto</th>
                      <th>PDF</th>
                      <th>Oficio</th>
                      <th>Evidencia</th>
                      <th>INE</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($recibos)): ?>
                      <?php foreach($recibos as $r): ?>
                        <?php
                          $idRecibo = intval($r->id_salida_inventario ?? 0);
                          $folio = esc($r->folio ?? '');
                          $fechaRaw = $r->fec_reg ?? null;
                          $fechaFmt = '';
                          if ($fechaRaw) {
                            try {
                              $dt = new DateTime($fechaRaw);
                              $fechaFmt = $dt->format('d/m/Y h:i A');
                            } catch(Exception $e) {
                              $fechaFmt = (string)$fechaRaw;
                            }
                          }

                          $pdfUrl = base_url('index.php/Inicio/generarPDFConvenio/' . $idRecibo);

                          // rutas existentes si ya están cargadas
                          $oficioRel = $r->archivo_oficio ?? '';
                          $evidRel   = $r->archivo_evidencia ?? '';
                          $ineRel    = $r->archivo_ine ?? '';
                        ?>
                        <tr class="align-middle">
                          <td class="text-center font-weight-bold"><?= $folio ?></td>
                          <td class="text-center"><?= esc($fechaFmt) ?></td>
                          <td class="text-center"><?= esc($r->nombre_solicitante ?? '') ?></td>
                          <td><?= esc($r->concepto ?? '') ?></td>

                          <td class="text-center">
                            <a class="btn btn-sm btn-outline-primary"
                               href="<?= $pdfUrl ?>"
                               target="_blank">
                              📄 Ver PDF
                            </a>
                          </td>

                          <!-- Oficio -->
                          <td class="text-center">
                            <div class="mb-2">
                              <?php if(!empty($oficioRel)): ?>
                                <a href="<?= base_url($oficioRel) ?>" target="_blank" class="btn btn-sm btn-success">✅ Ver</a>
                              <?php else: ?>
                                <span class="text-muted">Sin archivo</span>
                              <?php endif; ?>
                            </div>
                            <input type="file"
                                   class="form-control form-control-sm doc-input"
                                   data-id="<?= $idRecibo ?>"
                                   data-tipo="oficio"
                                   accept=".pdf,image/*">
                            <small class="text-muted d-block mt-1">Máx 300MB</small>
                          </td>

                          <!-- Evidencia -->
                          <td class="text-center">
                            <div class="mb-2">
                              <?php if(!empty($evidRel)): ?>
                                <a href="<?= base_url($evidRel) ?>" target="_blank" class="btn btn-sm btn-success">✅ Ver</a>
                              <?php else: ?>
                                <span class="text-muted">Sin archivo</span>
                              <?php endif; ?>
                            </div>
                            <input type="file"
                                   class="form-control form-control-sm doc-input"
                                   data-id="<?= $idRecibo ?>"
                                   data-tipo="evidencia"
                                   accept=".pdf,image/*">
                            <small class="text-muted d-block mt-1">Máx 300MB</small>
                          </td>

                          <!-- INE -->
                          <td class="text-center">
                            <div class="mb-2">
                              <?php if(!empty($ineRel)): ?>
                                <a href="<?= base_url($ineRel) ?>" target="_blank" class="btn btn-sm btn-success">✅ Ver</a>
                              <?php else: ?>
                                <span class="text-muted">Sin archivo</span>
                              <?php endif; ?>
                            </div>
                            <input type="file"
                                   class="form-control form-control-sm doc-input"
                                   data-id="<?= $idRecibo ?>"
                                   data-tipo="ine"
                                   accept=".pdf,image/*">
                            <small class="text-muted d-block mt-1">Máx 300MB</small>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                          No hay recibos generados para este contrato.
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- libs -->
<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function(){

        // DataTable (opcional)
        if ($.fn.DataTable) {
            $('#tablaRecibos').DataTable({
            destroy: true,
            ordering: false,
            pageLength: 10,
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
            });
        }

        // Upload por cambio de input
        $(document).on('change', '.doc-input', function(){
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) return;

            const idRecibo = parseInt($(this).data('id') || 0, 10);
            const tipo = ($(this).data('tipo') || '').toString().trim();

            if (!idRecibo || !tipo) {
            Swal.fire('Error', 'Datos de carga inválidos.', 'error');
            return;
            }

            // validar tamaño 300MB en cliente también
            const maxBytes = 300 * 1024 * 1024;
            if (file.size > maxBytes) {
            Swal.fire('Error', 'El archivo excede 300 MB.', 'error');
            $(this).val('');
            return;
            }

            const fd = new FormData();
            fd.append('id_salida_inventario', idRecibo);
            fd.append('tipo', tipo);
            fd.append('archivo', file);

            Swal.fire({
            title: 'Subiendo...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
            });

            $.ajax({
            url: '<?= base_url("index.php/Inicio/subirDocumentoRecibo") ?>',
            type: 'POST',
            data: fd,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(res){
                if (res && res.error === false) {
                Swal.fire('Listo', res.respuesta || 'Archivo cargado.', 'success')
                    .then(() => location.reload());
                } else {
                Swal.fire('Error', (res && res.respuesta) ? res.respuesta : 'No se pudo subir.', 'error');
                }
            },
            error: function(xhr){
                console.log('UPLOAD ERROR:', xhr.status, xhr.responseText);
                Swal.fire('Error', 'Error al subir. Revisa consola/network.', 'error');
            }
            });
        });

    });
</script>