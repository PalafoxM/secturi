<div class="page-wrapper">
  <div class="page-content-tab">
    <div class="container-fluid">

      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-3">

              <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h4 class="header-title mt-0 mb-0 text-dark">
                  Consultar Recibos 
                </h4>

                <div class="d-flex">
                  <a class="btn btn-secondary shadow-sm mr-2"
                     href="<?= base_url('index.php/Inicio/InventarioPromocion/' . intval($id_convenio_promo ?? $id_convenio ?? 0)) ?>">
                    ⬅ Volver a Inventario
                  </a>

                  <a class="btn btn-primary shadow-sm mr-2"
                    href="<?= base_url('index.php/Inicio/FormularioPromo/' . intval($id_convenio_promo ?? $id_convenio ?? 0)) ?>">
                    ➕ Nuevo recibo
                  </a>
                </div>
              </div>

              <div class="table-responsive">
                <table id="tablaRecibos" class="table table-striped table-bordered" style="width:100%;">
                  <thead class="thead-light">
                    <tr class="text-center">
                      <th>Folio</th>
                      <th>Fecha</th>
                      <th>Solicitante</th>
                      <th>Concepto</th>
                      <th>Recibo</th>
                      <th>Oficio</th>
                      <th>INE</th>
                      <th>Evidencia</th>
                      <th>Estatus</th>
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
                          $oficioRel = trim((string)($r->archivo_oficio ?? ''));
                          $ineRel    = trim((string)($r->archivo_ine ?? ''));
                          $evidRel   = trim((string)($r->archivo_evidencia ?? ''));

                          $hasOficio = ($oficioRel !== '' && $oficioRel !== '0' && strtolower($oficioRel) !== 'null');
                          $hasIne    = ($ineRel    !== '' && $ineRel    !== '0' && strtolower($ineRel)    !== 'null');
                          $hasEvid   = ($evidRel   !== '' && $evidRel   !== '0' && strtolower($evidRel)   !== 'null');

                          $docsCount = ($hasOficio ? 1 : 0) + ($hasIne ? 1 : 0) + ($hasEvid ? 1 : 0);

                          // Semáforo:
                          // - Verde: todo
                          // - Amarillo: oficio + ine (aunque falte evidencia)
                          // - Rojo: lo demás
                          if ($hasOficio && $hasIne && $hasEvid) {
                              $semaforoClass = 'badge-soft-success';
                              $semaforoText  = 'Completo';
                              $semaforoIcon  = '🟢';
                          } elseif ($hasOficio && $hasIne) {
                              $semaforoClass = 'badge-soft-warning';
                              $semaforoText  = 'En proceso';
                              $semaforoIcon  = '🟡';
                          } else {
                              $semaforoClass = 'badge-soft-danger';
                              $semaforoText  = 'Pendiente';
                              $semaforoIcon  = '🔴';
                          }
                        ?>

                        <tr class="align-middle">
                          <td class="text-center font-weight-bold"><?= $folio ?></td>
                          <td class="text-center"><?= esc($fechaFmt) ?></td>
                          <td class="text-center"><?= esc($r->nombre_solicitante ?? '') ?></td>
                          <td><?= esc($r->concepto ?? '') ?></td>

                          <td class="text-center">
                            <a class="btn btn-xs btn-outline-primary"
                               href="<?= $pdfUrl ?>"
                               target="_blank">
                              📄 Ver Recibo
                            </a>
                          </td>

                          <!-- Oficio -->
                          <td class="text-center">
                            <div class="doc-cell">

                              <div class="doc-actions">
                                <?php if(!empty($oficioRel)): ?>
                                  <a href="<?= base_url($oficioRel) ?>" target="_blank" class="btn btn-xs btn-success">✅ Ver</a>
                                  <button type="button"
                                          class="btn btn-xs btn-outline-secondary btn-toggle-upload"
                                          data-target="#upload_oficio_<?= $idRecibo ?>">
                                    ✏️ Cambiar
                                  </button>
                                <?php else: ?>
                                  <span class="text-muted">Sin archivo</span>
                                <?php endif; ?>
                              </div>

                              <div id="upload_oficio_<?= $idRecibo ?>"
                                  class="doc-upload <?= !empty($oficioRel) ? 'd-none' : '' ?>">
                                <input type="file"
                                      class="form-control form-control-sm doc-input"
                                      data-id="<?= $idRecibo ?>"
                                      data-tipo="oficio"
                                      btn btn-sm btn-success>

                                <small class="text-muted d-block">Máx 300MB</small>

                                <?php if(!empty($oficioRel)): ?>
                                  <button type="button"
                                          class="btn btn-link text-muted p-0 btn-cancel-upload"
                                          data-target="#upload_oficio_<?= $idRecibo ?>">
                                    Cancelar
                                  </button>
                                <?php endif; ?>
                              </div>

                            </div>
                          </td>

                          <!-- INE -->
                          <td class="text-center">
                            <div class="doc-cell">
                              <div class="doc-actions">
                                <?php if(!empty($ineRel)): ?>
                                  <a href="<?= base_url($ineRel) ?>" target="_blank" class="btn btn-sm btn-success">✅ Ver</a>
                                  <button type="button"
                                          class="btn btn-sm btn-outline-secondary btn-toggle-upload"
                                          data-target="#upload_ine_<?= $idRecibo ?>">
                                    ✏️ Cambiar
                                  </button>
                                <?php else: ?>
                                  <span class="text-muted">Sin archivo</span>
                                <?php endif; ?>
                              </div>

                              <div id="upload_ine_<?= $idRecibo ?>" class="<?= !empty($ineRel) ? 'd-none' : '' ?>">
                                <input type="file"
                                      class="form-control form-control-sm doc-input"
                                      data-id="<?= $idRecibo ?>"
                                      data-tipo="ine"
                                      btn btn-sm btn-success>
                                <small class="text-muted d-block mt-1">Máx 300MB</small>

                                <?php if(!empty($ineRel)): ?>
                                  <button type="button"
                                          class="btn btn-xs btn-link text-muted p-0 mt-1 btn-cancel-upload"
                                          data-target="#upload_ine_<?= $idRecibo ?>">
                                    Cancelar
                                  </button>
                                <?php endif; ?>
                              </div>
                            </div>
                          </td>

                          <!-- Evidencia (al final) -->
                          <td class="text-center">
                            <div class="doc-cell">
                              <div class="doc-actions">
                                <?php if(!empty($evidRel)): ?>
                                  <a href="<?= base_url($evidRel) ?>" target="_blank" class="btn btn-sm btn-success">✅ Ver</a>
                                  <button type="button"
                                          class="btn btn-sm btn-outline-secondary btn-toggle-upload"
                                          data-target="#upload_evidencia_<?= $idRecibo ?>">
                                    ✏️ Cambiar
                                  </button>
                                <?php else: ?>
                                  <span class="text-muted">Sin archivo</span>
                                <?php endif; ?>
                              </div>

                              <div id="upload_evidencia_<?= $idRecibo ?>" class="<?= !empty($evidRel) ? 'd-none' : '' ?>">
                                <input type="file"
                                      class="form-control form-control-sm doc-input"
                                      data-id="<?= $idRecibo ?>"
                                      data-tipo="evidencia"
                                      btn btn-sm btn-success>
                                <small class="text-muted d-block mt-1">Máx 300MB</small>

                                <?php if(!empty($evidRel)): ?>
                                  <button type="button"
                                          class="btn btn-xs btn-link text-muted p-0 mt-1 btn-cancel-upload"
                                          data-target="#upload_evidencia_<?= $idRecibo ?>">
                                    Cancelar
                                  </button>
                                <?php endif; ?>
                              </div>
                            </div>
                          </td>

                          <td class="text-center">
                            <span class="badge <?= $semaforoClass ?> font-13 p-2 px-3 status-badge">
                              <?= $semaforoIcon ?> <?= esc($semaforoText) ?> (<?= $docsCount ?>/3)
                            </span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="9" class="text-center text-muted py-4">
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

<style>
  /* Tabla: que no se desmadre */
  #tablaRecibos td, #tablaRecibos th { vertical-align: middle; }
  #tablaRecibos td { padding: .6rem .6rem; }
  #tablaRecibos th { white-space: nowrap; }

  /* Columnas fijas (ajusta si quieres) */
  #tablaRecibos th:nth-child(1), #tablaRecibos td:nth-child(1) { min-width: 160px; } /* Folio */
  #tablaRecibos th:nth-child(2), #tablaRecibos td:nth-child(2) { min-width: 150px; } /* Fecha */
  #tablaRecibos th:nth-child(3), #tablaRecibos td:nth-child(3) { min-width: 200px; } /* Solicitante */
  #tablaRecibos th:nth-child(4), #tablaRecibos td:nth-child(4) { min-width: 200px; } /* Concepto */
  #tablaRecibos th:nth-child(5), #tablaRecibos td:nth-child(5) { min-width: 140px; } /* Recibo */
  #tablaRecibos th:nth-child(6), #tablaRecibos td:nth-child(6),
  #tablaRecibos th:nth-child(7), #tablaRecibos td:nth-child(7),
  #tablaRecibos th:nth-child(8), #tablaRecibos td:nth-child(8) { min-width: 180px; } /* Docs */
  #tablaRecibos th:nth-child(9), #tablaRecibos td:nth-child(9) { min-width: 140px; } /* Estatus */

  /* Celdas de documentos: stack vertical uniforme */
  .doc-cell { display: flex; flex-direction: column; gap: .35rem; align-items: center; }
  .doc-actions { display: flex; gap: .35rem; flex-wrap: wrap; justify-content: center; }

  .doc-upload { width: 100%; }
  .doc-upload input[type="file"] { width: 100%; font-size: .82rem; }
  .doc-upload small { font-size: .75rem; }

  /* Botones consistentes */
  .btn-xs {
    padding: .2rem .5rem;
    font-size: .78rem;
    line-height: 1.2;
    border-radius: .25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
  }

  /* Que el badge de estatus no rompa línea */
  .status-badge { white-space: nowrap; }

  /* Si el solicitante o concepto es largo, corta bonito */
  .truncate-2 {
    max-width: 240px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>

<script>
  $(document).ready(function(){

    $('#tablaRecibos').DataTable({
      destroy: true,
      ordering: false,
      pageLength: 10,
      autoWidth: false,
      responsive: false,
      language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
    });

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
    
    $(document).on('click', '.btn-toggle-upload', function(){
      const target = $(this).data('target');
      if (target) $(target).removeClass('d-none');
    });

    $(document).on('click', '.btn-cancel-upload', function(){
      const target = $(this).data('target');
      if (target) {
        $(target).find('input[type="file"]').val('');
        $(target).addClass('d-none');
      }
    });
  });
</script>