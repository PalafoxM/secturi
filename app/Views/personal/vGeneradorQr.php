<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item active">Generador QR</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Generador de Codigo QR</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form id="formGeneradorQr">
                                <div class="form-group">
                                    <label for="link_qr">Link</label>
                                    <input type="url" class="form-control" id="link_qr" name="link" placeholder="https://..." required>
                                </div>
                                <button type="submit" class="btn btn-primary" id="btnGenerarQr">
                                    <i class="la la-qrcode mr-1"></i> Generar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div id="qrPlaceholder" class="text-muted py-5">
                                <i class="la la-qrcode" style="font-size: 64px;"></i>
                            </div>
                            <div id="qrResultado" style="display: none;">
                                <img id="imagenQr" src="" alt="Codigo QR" class="img-fluid mb-3" style="max-width: 320px;">
                                <div class="text-truncate mb-3" id="linkQrGenerado"></div>
                                <a id="descargarQr" href="#" download="codigo_qr.png" class="btn btn-success">
                                    <i class="mdi mdi-download mr-1"></i> Descargar QR
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#formGeneradorQr').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#btnGenerarQr');
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin mr-1"></i> Generando');

            $.ajax({
                type: 'POST',
                url: '<?= base_url('index.php/Principal/generarQrLink') ?>',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.respuesta, 'error');
                        return;
                    }

                    $('#imagenQr').attr('src', response.dataUri);
                    $('#descargarQr').attr('href', response.dataUri);
                    $('#linkQrGenerado').text(response.link);
                    $('#qrPlaceholder').hide();
                    $('#qrResultado').show();
                },
                error: function() {
                    Swal.fire('Error', 'No fue posible generar el codigo QR.', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="la la-qrcode mr-1"></i> Generar');
                }
            });
        });
    });
</script>
