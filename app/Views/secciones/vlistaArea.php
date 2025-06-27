


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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Pagina</a></li>
                                <li class="breadcrumb-item active">Listado de Área</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Áreas</h4>
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
                                <div class="col-12">
                                    <div class="card">

                                        <div class="card-body">
                                            <span>ÁREAS</span>
                                            <button onclick="ini.inicio.agregarArea()"
                                                class="btn btn-gradient-primary px-4 float-right mt-0 mb-3"><i
                                                    class="mdi mdi-plus-circle-outline mr-2"></i>Agregar Área</button>
                                            <table id="datatableCategorias" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">ID AREA</th>
                                                        <th class="text-center">AREA</th>
                                                        <th class="text-center">ESTATUS</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($cat_area as $p): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $p->id_area?></td>
                                                        <td class="text-center"><?= $p->dsc_area?></td>
                                                        <td class="text-center">
                                                            <?php if($p->visible == 1):?>
                                                            <i class="mdi mdi-eye text-success font-18"></i>
                                                            <?php endif; ?>
                                                            <?php if($p->visible == 0):?>
                                                            <i class="mdi mdi-eye-off text-danger font-18"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button title="editar"
                                                                onclick="ini.inicio.editarArea(<?= $p->id_area?>)"
                                                                class="btn btn-gradient-warning px-4"><i
                                                                    class="dripicons-pencil font-21"></i>
                                                            </button>
                                                          
                                                            <button title="eliminar"
                                                                onclick="ini.inicio.eliminarArea(<?= $p->id_area?>)"
                                                                class="btn btn-gradient-danger px-4 "><i
                                                                    class="dripicons-trash font-21"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--end tab-content-->

                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div><!-- container -->



    </div>
    <!-- end page content -->
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

<script src="<?= base_url()?>plugins/apexcharts/apexcharts.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>


<script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>



<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>
ini.inicio.guardarCursos();
$(document).ready(function() {
    $('#summernote').summernote({
        height: 500, // Altura del editor
        lang: 'es-ES',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
    });
    $('#datatableCategorias,#datatablePeriodos,#datatableCursos').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
    // Inicializar Select2 en todos los select con la clase .select2
    $('.select2').select2({
        placeholder: "Selecciona una opción", // Texto del placeholder
        allowClear: true, // Permite borrar todas las selecciones
        width: '100%', // Ajusta el ancho al 100% del contenedor
        color: 'black'
    });
    $("#img_ruta").on('change', function() {
        console.log('entro al change');
        const file = this.files[0]; // Obtener el archivo seleccionado

        if (file) {
            const reader = new FileReader();

            // Leer el archivo como una URL de datos
            reader.onload = function(e) {
                // Mostrar la nueva imagen en la vista previa
                $('#vista_img_ruta').html(
                    `<img src="${e.target.result}" class="img-uniforme" alt="Nueva imagen" class="rounded-circle">`
                );
            };

            reader.readAsDataURL(file); // Convertir el archivo a una URL de datos
        } else {
            // Limpiar la vista previa si no se selecciona ningún archivo
            $('#vista_img_deta_ruta').html('<p>No hay imagen seleccionada.</p>');
        }
    });
    $("#img_deta_ruta").on('change', function() {
        const file = this.files[0]; // Obtener el archivo seleccionado

        if (file) {
            const reader = new FileReader();

            // Leer el archivo como una URL de datos
            reader.onload = function(e) {
                // Mostrar la nueva imagen en la vista previa
                $('#vista_img_deta_ruta').html(
                    `<img src="${e.target.result}" class="img-uniforme" alt="Nueva imagen" class="rounded-circle">`
                );
            };

            reader.readAsDataURL(file); // Convertir el archivo a una URL de datos
        } else {
            // Limpiar la vista previa si no se selecciona ningún archivo
            $('#vista_img_deta_ruta').html('<p>No hay imagen seleccionada.</p>');
        }
    });
    $('#guardarContenido').on('click', function() {
        // Obtener el contenido del editor
        var contenido = $('#summernote').summernote('code');
        // Asignar el contenido al campo oculto
        $('#des_larga').val(contenido);
        // Cerrar el modal
        $('#modalTextEditor').modal('hide');
        $('#modalAgregarCategoria').modal('show');
    });
    $('#guardarDetalle').on('click', function() {
        let dirigido = $('#detalle_dirigido').val();
        let duracion = $('#detalle_duracion').val();
        let autogestivo = $('#detalle_autogestivo').val();
        let horas = $('#detalle_horas').val();
        let curso_linea = $('#detalle_curso_linea').val();
        let informacion = $('#detalle_informacion').val();
        $('#dirigido').val(dirigido);
        $('#duracion').val(duracion);
        $('#autogestivo').val(autogestivo);
        $('#horas').val(horas);
        $('#curso_linea').val(curso_linea);
        $('#informacion').val(informacion);
        $('#modalAgregarDetalles').modal('hide');
        $('#modalAgregarCategoria').modal('show');

    });

});

function descripcion() {
    $('#modalAgregarCategoria').modal('hide');
    $('#modalTextEditor').modal('show');
}

function detallerCurso() {
    $('#modalAgregarCategoria').modal('hide');
    $('#modalAgregarDetalles').modal('show');
}
</script>