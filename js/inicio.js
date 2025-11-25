var ini = window.ssa || {};

ini.inicio = (function () {
    return {
        
        abrirVentanaPdf: function(idTurno) {
            var pdfUrl = base_url + "index.php/Inicio/pdfTurno?id_turno=" + idTurno;
            var opcionesVentana = 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=800';
            window.open(pdfUrl, '_blank', opcionesVentana);
        },
        obtenerNombreMes: function(indiceMes) {
            var meses = [
              "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO",
              "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
            ];
            return meses[indiceMes - 1];
          },
        calculaFecha: function(valor,dias){
            var fechaReferencia = new Date(valor); 
            var fechaActual = new Date();
            var diferenciaMilisegundos = fechaActual - fechaReferencia;
            var diferenciaDias = Math.floor(diferenciaMilisegundos / (1000 * 60 * 60 * 24));
            var diasParaVerificar = dias;
            if (diferenciaDias >= diasParaVerificar) {
                return true;
            } else {
                return false;
            }
        },
        
        formatterAccionesTurno: function(value,row){
            let accion = "<div class='contenedor'>"+
                "<button type='button' onclick='ini.inicio.abrirVentanaPdf("+ row.id_turno+")' class='btn btn-secondary' title='Mostrar'><i class='mdi mdi-file-pdf'></i> </button>"+
                "<button type='button'  class='btn btn-warning' title='Modificar' style='margin-left:5px'><i class='mdi mdi-lead-pencil'></i> </button>"+
                "</div>";
            return accion;
        },
        formatterTruncaTexto:function(value, row) {
            if(value === null) return "";
            var maxLength = 30;
            var truncatedValue = value.length > maxLength ? value.substring(0, maxLength) + '...' : value;
            return '<span data-toggle="tooltip" title="' + value + '">' + truncatedValue + '</span>';
        },
        formatteStatusResultadoTurno:function(value,row){
            if (value === '1') {
                return '<span  title="CON RESULTADO">CON RESULTADO</span>';
            }else if (value ==='2'){
                return '<span  title="SIN RESULTADO">SIN RESULTADO</span>';
            }else if (value ==='3'){
                return '<span  title="AMBOS">AMBOS</span>';
            }else{
                return '<span  title="SIN RESULTADO">SIN RESULTADO</span>';
            }
        },
        formatteStatus: function(value, row){
            // TODO lo se es una mala practica hacer esto pero en este caso me es de mucha ayuda I'm sorry
            // opcion 1  
            // if(value ==1){
            //     let clase = ini.inicio.calculaFecha(row.fecha_recepcion, 10) ? '#fa5c7c' : (ini.inicio.calculaFecha(row.fecha_recepcion, 5)) ? '#f9bc0d': '#47d420';
            //     let titulo = ini.inicio.calculaFecha(row.fecha_recepcion, 10) ? 'Vencido' :ini.inicio.calculaFecha(row.fecha_recepcion, 5) ? 'Por vencer':'En proceso';
            //     return `<button type="button" class="btn" style="background:${clase}; color:#1D438A;" data-toggle="tooltip" title="${titulo}">En proceso </button>`;
            // }
            // if(value ==2){
            //     return '<button type="button" class="btn" style="background:#baddfd;color:#1D438A;" data-toggle="tooltip" title="Resuelta">Resuelta</button>';
            // }
            // opcion 2  
            if (value === '1') {
                let opciones = {
                    10: { clase: '#fa5c7c', titulo: 'Vencido' },
                    5: { clase: '#f9bc0d', titulo: 'Por vencer' },
                    default: { clase: '#47d420', titulo: 'En proceso' }
                };
                let key = ini.inicio.calculaFecha(row.fecha_recepcion, 10) ? 10 : ini.inicio.calculaFecha(row.fecha_recepcion, 5) ? 5 : 'default';
                let { clase, titulo } = opciones[key];
                return `<button type="button" class="btn" style="background:${clase}; color:#1D438A;" data-toggle="tooltip" title="${titulo}">${titulo}</button>`;
            }
            if (value === '2') {
                return '<button type="button" class="btn" style="background:#baddfd;color:#1D438A;" data-toggle="tooltip" title="Resuelta">Resuelta</button>';
            }     
        },
        formattFechaRecepcion: function(value,row){
           
            var fechaOriginalString = value;
            var fechaOriginal = new Date(fechaOriginalString);
            fechaOriginal.setMinutes(fechaOriginal.getTimezoneOffset());
            var dia = fechaOriginal.getDate();
            var mes = ini.inicio.obtenerNombreMes(fechaOriginal.getMonth()); // Sumar 1 al índice del mes
            var año = fechaOriginal.getFullYear();
            var nuevoFormato = dia + " de " + mes + " de " + año;
            return '<strong>' + nuevoFormato + '</strong>';
        },
        formattAcciones: function(value,row){
            let Botones = "<div class='contenedor'>" +
            "<button type='button' class='btn btn-danger' title='Remover' id='remover' onclick='ini.inicio.deleteUsuario(" + row.id_usuario + ")'><i class='mdi mdi-account-off'></i></button>" +
            "<button type='button' title='Editar' data-bs-toggle='modal' data-bs-target='#staticBackdrop' class='btn btn-warning' onclick='ini.inicio.getUsuario(" + row.id_usuario + ")'><i class='mdi mdi-account-edit'></i></button>" +
            "</div>";
           return Botones;
        },
        deleteParticipante: function(id){
    
        Swal.fire({
            title: "Atención",
            text: "Desea eliminar Usuario de la tabla",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Usuario/deleteParticipante",
                    dataType: "json",
                    data:{id_participante:id},
                    success: function(data) {
                        if (!data.error) {
                            Swal.fire("Éxito", data.respuesta, "success");
                            window.location.reload();
                        } else {
                            Swal.fire("info",  data.respuesta , "info");
                        }
                    },
                    error: function() {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                });
            }
        });
          
        },
       formContrasenia: function()
       {
          var formData = $("#formContrasenia").serialize();
           $.ajax({
                type: "POST",
                url: base_url + "index.php/Principal/formContrasenia",
                dataType: "json",
                data:formData,
                beforeSend: function()
                {
                 $('#btnPass').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                },
                success: function(data) {
                    console.log(data);
                    if (!data.error) {
                        Swal.fire("Éxito", data.respuesta, "success");
                        setTimeout(() => {
                          window.location.href = base_url + 'index.php/Login/cerrar';
                        }, 1500);
                      
                    } else {
                        Swal.fire("info",  data.respuesta , "info");
                    }
                },
                complete: function()
                {
                 $("#btnPass").prop('disabled', false).html('Guardar');
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    $("#btnPass").prop("disabled", false).html('Guardar');
                }
            });
       },
        enviarCorreo: function(id_participante)
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/enviarCorreo",
                dataType: "json",
                data:{id_participante},
                success: function(data) {
                    if (!data.error) {
                        Swal.fire("Éxito", data.respuesta, "success");
                        window.location.href = base_url + 'index.php/Inicio/usuarios';
                    } else {
                        Swal.fire("info",  data.respuesta , "info");
                    }
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            });
        },
        deleteDetenido: function(id){
    
        Swal.fire({
            title: "Atención",
            text: "Desea eliminar Usuario de la tabla",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
        
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Usuario/deleteDetenido",
                    dataType: "json",
                    data:{id_detenido:id},
                    success: function(data) {
                        if (!data.error) {
                            Swal.fire("Éxito", data.respuesta, "success");
                            window.location.reload();
                        } else {
                            Swal.fire("info",  data.respuesta , "info");
                        }
                    },
                    error: function() {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                });
            }
        });
          
        },
        cerrarUsuario: function()
        {
          $("#modalUsuario").modal('hide');
        },
        verDetalles: function(id){
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getUsuario",
                dataType: "json",
                data:{id_usuario:id},
              success: function(data) {
                    let img = (data.ruta_foto_relativa)?`<img src="${base_url+data.ruta_foto_relativa}" class="img-fluid rounded"/>`:'';
                    if (data) {
                        $(".met-profile-main-pic").html(`
                            <p> ${data.nombre} ${data.primer_apellido} ${data.segundo_apellido}</p>
                            <p><strong> Personal de:  ${data.dsc_tipo_empleado} | ${data.dsc_puesto}</strong></p>
                            ${img}
                        `);
                    } else {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                },
                complete: function(){
                   $("#modalUsuario").modal('show');
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            });
        },
        closeCumple: function(){
          $("#verDetallesCumple").modal('hide');
        },
     verDetallesCumple: function(id){
        // lanzar confetti
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 },
            scalar: 1.2,
            shapes: ["circle", "square"],
            colors: ["#ff0000", "#ff8000", "#ffff00", "#00ff00", "#0000ff"]
        });

        // forzar el z-index del canvas del confetti
        let canvasConfetti = document.querySelector('canvas');
        if (canvasConfetti) {
            canvasConfetti.style.position = 'fixed';
            canvasConfetti.style.top = '0';
            canvasConfetti.style.left = '0';
            canvasConfetti.style.width = '100%';
            canvasConfetti.style.height = '100%';
            canvasConfetti.style.pointerEvents = 'none';
            canvasConfetti.style.zIndex = '9999'; // más alto que el modal
        }

        $.ajax({
            type: "POST",
            url: base_url + "index.php/Usuario/getUsuario",
            dataType: "json",
            data:{id_usuario:id},
            success: function(data) {
                let img = (data.ruta_foto_relativa)
                    ? `<img src="${base_url+data.ruta_foto_relativa}" class="img-fluid rounded"/>`
                    : '';
                if (data) {
                    $(".met-profile-main-pic2").html(`
                        <p>${data.nombre} ${data.primer_apellido} ${data.segundo_apellido}</p>
                        ${img}
                    `);
                } else {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            },
            complete: function(){
                $("#verDetallesCumple").modal('show');
            },
            error: function() {
                Swal.fire("info", "No se encontraron datos del usuario.", "info");
            }
        });
    },
        getUsuario: function(id){
            
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getUsuario",
                dataType: "json",
                data:{id_usuario:id},
                success: function(data) {
                    if (data) {
                        console.log(data);
                        
                        $('#staticBackdropLabel').text('Editar Usuario');
                        
                        $('#editar').prop('disabled', true);
                        $('#editar').val('');
                        $('#extencion').val(data.extencion);
                        $('#prueba').val(data.extencion);
                        $('#id_usuario').prop('disabled', false);
                        $('#id_usuario').val(data.id_usuario);
                        $('#usuario').val(data.usuario);
                        $('#contrasenia').val(data.contrasenia);
                        $('#nombre').val(data.nombre);
                        $('#primer_apellido').val(data.primer_apellido);
                        $('#segundo_apellido').val(data.segundo_apellido);
                        $('#sexo').val(data.id_sexo);
                        $('#correo').val(data.correo);
                        $('#id_perfil').val(data.id_perfil);
                        $('#nivel').val(data.nivel);
                      

                    } else {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            });
        },
        cargaCsv: function()
        {
         $('#standard-modal').modal('show');
        },
        cerrarModal: function()
        {
         $('#standard-modal').modal('hide');
        },
        reserva: function(id_proveedor)
        {
         $('#modalReserva').modal('show');
         $('.dropdown-toggle').dropdown();
         ini.inicio.traerReserva(id_proveedor);
        },
        editarFic: function(){
                $("#editarFic").submit(function (e) {
                e.preventDefault(); 
                 var formData = $("#editarFic").serialize();
            
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Principal/editarFic",
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
      
                        if(response.error == false){
                            Swal.fire("Exitó", response.respuesta, "success");

                            //window.location.reload();
                                                    
                        }else{
                            Swal.fire("Error", response.respuesta , "error"); 
                            //$("#formParticipante")[0].reset();                         
                            return false;
                        } 
                    },
                    complete: function(){
                        $("#btn_guardar_detenido").show();             
                        $("#btn_load_detenido").hide();   
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse (response.responseText);
                       //  console.log(res.message);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                   }
                });
            });

        },
      
        editarProveedor: function(id_proveedor)
        {
         $('#modalProveedor').modal('show');
          $.ajax({
            url: base_url + "index.php/Principal/getProveedor",
            type: 'POST',
            dataType: "json",
            data: {id_proveedor:id_proveedor},
            success: function(response) {
                 console.log(response);
                 if(!response.error){
                    $("#id_proveedor").val(id_proveedor);
                    $("#razon_social").val(response.data.proveedor.razon_social);
                    $("#no_proveedor").val(response.data.proveedor.no_proveedor);
                    $("#rfc").val(response.data.proveedor.rfc);
                    $("#fic").val(response.data.proveedor.fic);
                    const tbody = $('#makeEditable3 tbody');
                    tbody.empty();

                    response.data.proveedor_banco.forEach(p => {
                        tbody.append(`
                            <tr class="success" data-id="${p.id_proveedor_banco}">
                                <td><input type="text" class="form-control banco" value="${p.banco}"></td>
                                <td><input type="text" class="form-control no_cuenta" value="${p.no_cuenta}"></td>
                                <td><input type="text" class="form-control clabe" value="${p.clabe}"></td>
                                <td>
                                    <a style="cursor:pointer;" onclick="ini.inicio.guardarBanco(this)" title="Guardar" class="px-4">
                                        <i class="fa fa-save"></i>
                                    </a>
                                    <a style="cursor:pointer;" onclick="ini.inicio.eliminarBanco(this)" title="Eliminar" class="px-4">
                                        <i class="mdi mdi-trash-can font-21"></i>
                                    </a>
                                </td>
                            </tr>
                        `);
                    });
                                                                                                   
                 }else{
                     Swal.fire("Error", "Favor de llamar al Administrador", "error")
                 }
               
            },
            complete: function(){
                $("#btn_csv").show();
                $("#load_csv").hide();
            },
            error: function(xhr, status, error) {
                console.log(error);
                Swal.fire("Error", "Favor de llamar al Administrador", "error")
                $("#btn_csv").show();
                $("#load_csv").hide();
                //alert("Error en la solicitud: " + error);
            }
         });
        },
        EnviarCorreoIncidencias: function ()
        {
            Swal.fire({
                title: "¿Está seguro?",
                text: "¿Se enviar correo a todo el personal que tiene incidencias?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Enviar",
            }).then((result) => {
                if (result.isConfirmed) {
                   $.ajax({
                        url: base_url + 'index.php/Principal/EnviarCorreoIncidencias',
                        type: 'GET',
                        dataType: 'json',
                        beforeSend: function(){
                           $('#btnCorreo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enviando...');
                        },
                        success: function(response) {
                            if (!response.error) {
                                Swal.fire('Éxito', 'Se ha enviado el correo satisfactoriamente', 'success');
                                window.location.reload(); 
                            } else {
                                Swal.fire('Error', 'No se pudo enviar el correo masivo', 'error');
                            }
                        },
                        complete: function(){
                           $('#btnCorreo').prop('disabled', false).html('<i class="mdi mdi-plus-circle-outline mr-2"></i>Enviar Correo');
                        },
                        error: function() {
                            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                        }
                    }); 
                            
                        }
             });

        },
        guardarBanco : function (element) {
            const row = $(element).closest('tr');
            const id = row.data('id');
            const banco = row.find('.banco').val();
            const noCuenta = row.find('.no_cuenta').val();
            const clabe = row.find('.clabe').val();

            Swal.fire({
                title: "¿Está seguro?",
                text: "¿Desea Guardar los datos del banco del proveedor?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Guardar",
            }).then((result) => {
                if (result.isConfirmed) {
                   $.ajax({
                        url: base_url + 'index.php/Principal/actualizarBanco',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_proveedor_banco: id,
                            banco: banco,
                            no_cuenta: noCuenta,
                            clabe: clabe
                        },
                        success: function(response) {
                            if (!response.error) {
                                Swal.fire('Éxito', 'Los datos se actualizaron correctamente', 'success');
                                window.location.reload(); 
                            } else {
                                Swal.fire('Error', 'No se pudo actualizar', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                        }
                    }); 
                            
                        }
             });
        },
        nuevoProveedor: function () {
            Swal.fire({
                title: 'Agregar Nuevo Proveedor',
                html:
                    '<input id="razon" class="swal2-input" placeholder="RAZON SOCIAL" autocomplete="off">' +
                    '<input id="rfcPro" class="swal2-input" placeholder="RFC" autocomplete="off">' +
                    '<input id="numero" class="swal2-input" placeholder="No. PROVEEDOR" autocomplete="off">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const razon = document.getElementById('razon').value;
                    const rfc = document.getElementById('rfcPro').value;
                    const numero = document.getElementById('numero').value;
                    console.log(razon, rfc, numero);
                    if (!razon || !rfc || !numero) {
                        Swal.showValidationMessage('Todos los campos son obligatorios');
                        return false;
                    }

                    return { razon:razon, rfc:rfc, numero:numero };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const datos = result.value;

                    // Aquí puedes hacer la llamada AJAX o lógica con los datos:
                    console.log("Datos del nuevo proveedor:", datos);

                    // Ejemplo de llamada AJAX
                    $.ajax({
                        url: base_url + "index.php/Principal/agregarProveedor",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            razon_social: datos.razon.toUpperCase(),
                            rfc: datos.rfc.toUpperCase(),
                            no_proveedor: datos.numero.toUpperCase()
                        },
                        success: function (response) {
                            if (!response.error) {
                                Swal.fire('Éxito', 'Proveedor agregado correctamente', 'success');
                                // Recargar o actualizar tabla si es necesario
                            } else {
                                Swal.fire('Error', 'No se pudo agregar el proveedor', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                        }
                    });
                }
            });
        },
        nuevoBanco: function (element) {
             $('#modalProveedor').modal('hide');
            let id_proveedor = $("#id_proveedor").val();
            Swal.fire({
                title: 'Agregar Banco',
                html:
                    '<input id="swal-banco" class="swal2-input" placeholder="Banco" autocomplete="off">' +
                    '<input id="swal-no-cuenta" class="swal2-input" placeholder="No. Cuenta" autocomplete="off">' +
                    '<select id="fic" class="swal2-input">' +
                        '<option value="">¿FIC?</option>' +
                        '<option value="1">Sí</option>' +
                        '<option value="0">No</option>' +
                    '</select>' +
                    '<input id="swal-clabe" class="swal2-input" placeholder="Clabe" autocomplete="off">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const banco = document.getElementById('swal-banco').value.trim();
                    const no_cuenta = document.getElementById('swal-no-cuenta').value.trim();
                    const fic = document.getElementById('fic').value.trim();
                    const clabe = document.getElementById('swal-clabe').value.trim();

                    if (!banco || !no_cuenta || !fic || !clabe) {
                        Swal.showValidationMessage('Todos los campos son obligatorios');
                        return false;
                    }

                    return { banco, no_cuenta, fic, clabe };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const datos = result.value;

                    // Aquí puedes hacer la llamada AJAX o lógica con los datos:
                    console.log("Datos del nuevo proveedor:", datos);

                    // Ejemplo de llamada AJAX
                    $.ajax({
                        url: base_url + "index.php/Principal/agregarBanco",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_proveedor,
                            banco: datos.banco,
                            no_cuenta: datos.no_cuenta,
                            clabe: datos.clabe,
                            fic: datos.fic
                        },
                        success: function (response) {
                            if (!response.error) {
                                Swal.fire('Éxito', 'Datos agregado correctamente', 'success');
                                // Recargar o actualizar tabla si es necesario
                            } else {
                                Swal.fire('Error', 'No se pudo agregar el proveedor', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                        }
                    });
                }
            });
        },

        eliminarBanco : function (element) {
            const row = $(element).closest('tr');
            const id = row.data('id');

            Swal.fire({
                title: "¿Está seguro?",
                text: "¿Desea Eliminar los datos del banco del proveedor?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Eliminar",
            }).then((result) => {
                if (result.isConfirmed) {
                   $.ajax({
                        url: base_url + 'index.php/Principal/eliminarBanco',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_proveedor_banco: id,
                        },
                        success: function(response) {
                            if (!response.error) {
                                Swal.fire('Éxito', 'Los datos se actualizaron correctamente', 'success');
                                window.location.reload(); 
                            } else {
                                Swal.fire('Error', 'No se pudo actualizar', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                        }
                    }); 
                            
                        }
                    });
        },
        editarReservaGo: function(id_reserva_go, id) {
     
            $.ajax({
                url: base_url + "index.php/Principal/editarReservaGo",
                type: 'POST',
                dataType: "json",
                data: { id_reserva_go},
                success: function(response) {
                    if (response && response.data && response.data.reserva) {
                        const reserva = response.data.reserva;
                        const presupuesto = response.data.presupuesto;
                        const partidas = response.data.partida;
                        const proyectos = response.data.proyecto;
                        $("#total_importe_editar").val(reserva.total_importe || '');
                        $("#id_reserva_go").val(reserva.id_reserva_go || '');
                
                           const tbody = $('#editarReservaGo tbody');
                            tbody.empty();

                            presupuesto.forEach(p => {
                                let opcionesProyecto = '<option value="">Seleccione</option>';
                               proyectos.forEach(c => {
                                    opcionesProyecto += `<option value="${c.id_proyecto}" ${(c.id_proyecto == p.id_proyecto) ? 'selected' : ''}>${c.proyecto}</option>`;
                                });

                                let opcionesPartida = '<option value="">Seleccione</option>';
                                partidas.forEach(pa => {
                                    opcionesPartida += `<option value="${pa.id_partida}" ${(pa.id_partida == p.id_partida) ? 'selected' : ''}>${pa.cuenta_cable}</option>`;
                                });

                                const fila = `
                                    <tr>
                                        <td>
                                            <select class="select2 form-control" name="proyecto_go[]">
                                                ${opcionesProyecto}
                                            </select>
                                            <input type="hidden" name="id_presupuesto_go[]" value="${p.id_presupuesto_go}">
                                        </td>
                                        <td>
                                            <select class="select2 form-control" name="partida_go[]">
                                                ${opcionesPartida}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" autocomplete="off" class="form-control" name="importe_go[]" placeholder="0,000.00" value="${p.importe}" >
                                        </td>
                                         <td>
                                            <input type="text" autocomplete="off" class="form-control" name="propina_go[]" placeholder="0,000.00" value="${p.propina}" >
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                tbody.append(fila);
                            });

                       
                    } else {
                        Swal.fire("Atención", "No se encontró la información de la reserva.", "warning");
                    }
                },
                complete: function() {
                    $('#reservaGo').modal('show');
                    $('.dropdown-toggle').dropdown();
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    Swal.fire("Error", "Favor de llamar al Administrador", "error");
                }
            });
        },
        editarReserva: function(id_reserva, id) {
     
            $.ajax({
                url: base_url + "index.php/Principal/editarReserva",
                type: 'POST',
                dataType: "json",
                data: { id_reserva: id_reserva },
                success: function(response) {

                    if (response && response.data && response.data.reserva) {
                        const reserva = response.data.reserva;
                        const presupuesto = response.data.presupuesto;
                        const partidas = response.data.partida;
                        const proyectos = response.data.proyecto;
                        console.log('entro');
                       console.log(reserva.razon_social);
                          console.log(reserva.no_proveedor);
                             console.log(reserva.razon_social);
                                console.log(reserva.total_importe);
                                console.log(reserva.id_reserva);
                 


                        $("#nombre_proveedor_editar").val(reserva.razon_social || '');
                        $("#no_proveedor_editar").val(reserva.no_proveedor || '');
                        $("#total_importe_editar").val(reserva.total_importe || '');
                        $("#id_reserva").val(reserva.id_reserva || '');
                        $("#previews2").empty(); // Limpiar contenido anterior del contenedor
                        // Verifica que haya un instrumento antes de agregar el enlace
                        if (reserva.instrumento) {
           
                            const fileUrl = base_url + reserva.instrumento;
                            const link = `<a href="${fileUrl}" target="_blank" class="me-2">
                                            <i class="mdi mdi-file"></i> Ver archivo
                                        </a>`;
                            $("#previews2").append(link);
                        }
                           $("#no_convenio_editar").val(reserva.no_convenio || '');
                           const tbody = $('#makeEditableEditar tbody');
                            tbody.empty();

                            presupuesto.forEach(p => {
                                let opcionesProyecto = '<option value="">Seleccione</option>';
                               proyectos.forEach(c => {
                                    opcionesProyecto += `<option value="${c.id_proyecto}" ${(c.id_proyecto == p.id_proyecto) ? 'selected' : ''}>${c.proyecto}</option>`;
                                });

                                let opcionesPartida = '<option value="">Seleccione</option>';
                                partidas.forEach(pa => {
                                    opcionesPartida += `<option value="${pa.id_partida}" ${(pa.id_partida == p.id_partida) ? 'selected' : ''}>${pa.cuenta_cable}</option>`;
                                });

                                const fila = `
                                    <tr>
                                        <td>
                                            <select class="select2 form-control" name="proyecto[]">
                                                ${opcionesProyecto}
                                            </select>
                                            <input type="hidden" name="id_presupuesto[]" value="${p.id_presupuesto}">
                                        </td>
                                        <td>
                                            <select class="select2 form-control" name="partida[]">
                                                ${opcionesPartida}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" autocomplete="off" class="form-control" name="importe[]" value=${p.importe} placeholder="0,000.00">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                tbody.append(fila);
                            });

                       
                    } else {
                        Swal.fire("Atención", "No se encontró la información de la reserva.", "warning");
                    }
                },
                complete: function() {
                         $('#modalEditarReserva2').modal('show');
                    $('.dropdown-toggle').dropdown();
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    Swal.fire("Error", "Favor de llamar al Administrador", "error");
                }
            });
        },
        cerrarModalAdmin: function()
        {
            $('#modalEstatusReserva').modal('hide');
        },
        traerReserva: function(id_proveedor){
          $.ajax({
            url: base_url + "index.php/Principal/Proveedor",
            type: 'POST',
            dataType: "json",
            data: {id_proveedor:id_proveedor},
            success: function(response) {
             
                if(response.error){
                    Swal.fire({
                        title: "Atención",
                        text: response.respuesta,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#modalReserva').modal('hide');
                            }
                    });
                }else{
                  $("#nombre_proveedor").val(response.data.proveedor.razon_social);
                  $("#no_proveedor").val(response.data.proveedor.no_proveedor);
                  $("#id_proveedor").val(response.data.proveedor.id_proveedor);
                  let bancoSelect = $("#banco");
                    bancoSelect.empty(); // Limpia opciones anteriores
                    bancoSelect.append('<option value="">Selecciona un banco</option>');
                    response.data.banco.forEach(function(item) {
                            console.log(item);
                        bancoSelect.append(
                            `<option value="${item.id_proveedor_banco}">${item.banco+'-'+item.no_cuenta}</option>`
                        );
                    });

                }
            },
            complete: function(){
                $("#btn_csv").show();
                $("#load_csv").hide();
            },
            error: function(xhr, status, error) {
                console.log(error);
                Swal.fire("Error", "Favor de llamar al Administrador", "error")
                $("#btn_csv").show();
                $("#load_csv").hide();
                //alert("Error en la solicitud: " + error);
            }
         });
        },
           validarFormularioGo: function () {
    
            // Validar que al menos haya una fila en la tabla
            if($('#makeEditable3 tbody tr').length === 0) {
                //toastr.warning('Debe agregar al menos un proyecto');
                Swal.fire("Atenición", "Debe agregar al menos un proyecto", "info");
                return false;
            }

            // Validar que todas las filas tengan datos completos
            var filasValidas = true;
            $('#makeEditable3 tbody tr').each(function() {
                if($(this).find('[name="proyecto[]"]').val() === '' || 
                $(this).find('[name="partida[]"]').val() === '' || 
                $(this).find('[name="importe[]"]').val() === '') {
                    filasValidas = false;
                    return false; // Sale del each
                }
            });

            if(!filasValidas) {
                Swal.fire("Error", "Debe agregar al menos un proyecto/partida/importe", "error");
                return false;
            }

            return true;
        },
           validarFormulario: function () {
            // Validar campos principales
            if($('#nombre_proveedor').val() === '') {
                //toastr.warning('El nombre del proveedor es requerido');
                Swal.fire("Atenición", "El nombre del proveedor es requerido", "info");
                return false;
            }
            if($('#banco').val() === '') {
                //toastr.warning('El nombre del proveedor es requerido');
                Swal.fire("Atenición", "El numero del <strong>BANCO</strong> es requerido", "info");
                return false;
            }

            // Validar que al menos haya una fila en la tabla
            if($('#makeEditable2 tbody tr').length === 0) {
                //toastr.warning('Debe agregar al menos un proyecto');
                Swal.fire("Atenición", "Debe agregar al menos un proyecto", "info");
                return false;
            }

            // Validar que todas las filas tengan datos completos
            var filasValidas = true;
            $('#makeEditable2 tbody tr').each(function() {
                if($(this).find('[name="proyecto[]"]').val() === '' || 
                $(this).find('[name="partida[]"]').val() === '' || 
                $(this).find('[name="importe[]"]').val() === '') {
                    filasValidas = false;
                    return false; // Sale del each
                }
            });

            if(!filasValidas) {
                Swal.fire("Error", "Debe agregar al menos un proyecto/partida/importe", "error");
                return false;
            }

            return true;
        },
         guardarReservaEdicionGo: function()
        {
            $('#btnReservaGo').click(function(e) {
            e.preventDefault();

            // Crear FormData para enviar tanto el formulario como el archivo
            var formData = new FormData();
            
       
            formData.append('id_reserva_go', $('#id_reserva_go').val());
            formData.append('total_importe', $('#total_importe_editar').val());
          

            // Agregar datos de la tabla
            $('#editarReservaGo tbody tr').each(function(index) {
                formData.append('proyecto[]', $(this).find('[name="proyecto_go[]"]').val());
                formData.append('partida[]', $(this).find('[name="partida_go[]"]').val());
                formData.append('importe[]', $(this).find('[name="importe_go[]"]').val());
                formData.append('propina[]', $(this).find('[name="propina_go[]"]').val());
                formData.append('id_presupuesto[]', $(this).find('[name="id_presupuesto_go[]"]').val());
            });

            // Enviar datos via AJAX
            $.ajax({
                url: base_url + "index.php/Principal/guardarReservaEditarGo",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btnReservaGo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                },
                success: function(response) {
                  if(response.error) {
                        Swal.fire("Error", response.respuesta, "error");
                    } else {
                        Swal.fire("Correcto", response.respuesta, "success");
                         setTimeout(() => {
                                    window.location.reload()

                            }, 1000); 
                        
                    } 
                },
                error: function() {
                    //toastr.error('Error de conexión');
                    Swal.fire("Error", "Error de conexión", "error");
                },
                complete: function() {
                    $('#btnReservaGo').prop('disabled', false).html('Guardar');
                }
            });
            });

        },
         guardarReservaEdicion: function()
        {
            $('#btn_guardar_edicion').click(function(e) {
            e.preventDefault();

            // Crear FormData para enviar tanto el formulario como el archivo
            var formData = new FormData();
            
       
            formData.append('id_reserva', $('#id_reserva').val());
            formData.append('total_importe', $('#total_importe_editar').val());
            formData.append('no_convenio', $('#no_convenio_editar').val());
            
            // Agregar archivo si existe
            var instrumentoFile = $('#instrumento_editar')[0].files[0];
            if(instrumentoFile) {
                formData.append('instrumento', instrumentoFile);
            }
            
          

            // Agregar datos de la tabla
            $('#makeEditableEditar tbody tr').each(function(index) {
                formData.append('proyecto[]', $(this).find('[name="proyecto[]"]').val());
                formData.append('partida[]', $(this).find('[name="partida[]"]').val());
                formData.append('importe[]', $(this).find('[name="importe[]"]').val());
                formData.append('id_presupuesto[]', $(this).find('[name="id_presupuesto[]"]').val());
            });

            // Enviar datos via AJAX
            $.ajax({
                url: base_url + "index.php/Principal/guardarReservaEditar",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btn_guardar_edicion').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                },
                success: function(response) {
                  if(response.error) {
                        Swal.fire("Error", response.respuesta, "error");
                    } else {
                        Swal.fire("Correcto", response.respuesta, "success");
                        setTimeout(() => {
                                    window.location.href = base_url + "index.php/Principal/listaReservaPT";

                            }, 1000);
                        
                    } 
                },
                error: function() {
                    //toastr.error('Error de conexión');
                    Swal.fire("Error", "Error de conexión", "error");
                },
                complete: function() {
                    $('#btn_guardar_edicion').prop('disabled', false).html('Guardar');
                }
            });
            });

        },
        linksGo: function(id_registro_go)
        {
            $.ajax({
                url: base_url + "index.php/Principal/getLinkGo",
                type: 'POST',
                dataType: "json",
                data: { id_registro_go },
                success: function(response) {
                    console.log(response);
                    if (response) {
                            response.forEach(p => {
                                const fila = `
                                           <div class="col-lg-12 mb-2">
                                                <a target="_blank" href="${base_url + p.ruta_relativa}" class="d-flex align-items-center text-decoration-none text-dark">
                                                    <i class="far fa-file-pdf text-danger me-2" style="font-size: 1.5rem;"></i>
                                                    <span class="text-truncate" title="Archivo ${p.id_factura_pdf}" style="max-width: 85%;">
                                                        Archivo ${p.id_factura_pdf}
                                                    </span>
                                                </a>
                                                <hr class="my-2">
                                            </div>
                                `;
                                $('#links').append(fila);
                            });

                       
                    } else {
                        Swal.fire("Atención", "No se encontró la información de la reserva.", "warning");
                    }
                },
                complete: function() {
                $('#modalLinks').modal('show');
                
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    Swal.fire("Error", "Favor de llamar al Administrador", "error");
                }
            });

        },
        links: function(id_registro_pt)
        {
            $.ajax({
                url: base_url + "index.php/Principal/getLink",
                type: 'POST',
                dataType: "json",
                data: { id_registro_pt },
                success: function(response) {
                    console.log(response);
                    if (response) {
                            response.forEach(p => {
                                const fila = `
                                           <div class="col-lg-12 mb-2">
                                                <a target="_blank" href="${base_url + p.ruta_relativa}" class="d-flex align-items-center text-decoration-none text-dark">
                                                    <i class="far fa-file-pdf text-danger me-2" style="font-size: 1.5rem;"></i>
                                                    <span class="text-truncate" title="Archivo ${p.id_factura_pdf}" style="max-width: 85%;">
                                                        Archivo ${p.id_factura_pdf}
                                                    </span>
                                                </a>
                                                <hr class="my-2">
                                            </div>
                                `;
                                $('#links').append(fila);
                            });

                       
                    } else {
                        Swal.fire("Atención", "No se encontró la información de la reserva.", "warning");
                    }
                },
                complete: function() {
                $('#modalLinks').modal('show');
                
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    Swal.fire("Error", "Favor de llamar al Administrador", "error");
                }
            });

        },
        cerrarModalGo: function(){
         $('#modalReservaGo').modal('hide');
        },
        cerrarModalLink: function()
        {
            $('#modalLinks').modal('hide');
            $('#links').empty();
        },
         estatusReservaGo: function(id_reserva, id_estatus)
        {

        id_estatus == 3?$('#btnConfirmarReservaGo').hide():$('#btnConfirmarReservaGo').show();      
           $('#id_reserva_estatus_go').val(id_reserva);
           $('#motivo_go').val('');
           $('#observaciones_go').val('');
           $('#validar_no_reserva_go').val('');
            $.ajax({
                url: base_url + "index.php/Principal/editarReservaGo",
                type: 'POST',
                dataType: "json",
                data: { id_reserva_go: id_reserva },
                success: function(response) {
                    console.log(response);
                    if (response && response.data && response.data.reserva) {
                        const reserva = response.data.reserva;
                        const presupuesto = response.data.presupuesto;
                        const partidas = response.data.partida;
                        const proyectos = response.data.proyecto;


                        if(reserva.no_reserva){
                           $("#validar_no_reserva_go").val(reserva.no_reserva).prop("readonly", true);
                        }
                   
                           const tbody = $('#tablaReservaGo tbody');
                            tbody.empty();

                            presupuesto.forEach(p => {
                                let opcionesProyecto = '<option value="">Seleccione</option>';
                               proyectos.forEach(c => {
                                    opcionesProyecto += `<option value="${c.id_proyecto}" ${(c.id_proyecto == p.id_proyecto) ? 'selected' : ''}>${c.proyecto}</option>`;
                                });

                                let opcionesPartida = '<option value="">Seleccione</option>';
                                partidas.forEach(pa => {
                                    opcionesPartida += `<option value="${pa.id_partida}" ${(pa.id_partida == p.id_partida) ? 'selected' : ''}>${pa.cuenta_cable}</option>`;
                                });

                                const fila = `
                                    <tr>
                                        <td>
                                            <select class="select2 form-control"  readonly>
                                                ${opcionesProyecto}
                                            </select>
                                            <input type="hidden" value="${p.id_presupuesto}" readonly>
                                        </td>
                                        <td>
                                            <select class="select2 form-control" readonly>
                                                ${opcionesPartida}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" autocomplete="off" class="form-control" name="importe[]" value=${p.importe} readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                tbody.append(fila);
                            });

                       
                    } else {
                        Swal.fire("Atención", "No se encontró la información de la reserva.", "warning");
                    }
                },
                complete: function() {
                $("#modalReservaGo").modal('show');
                    $('.dropdown-toggle').dropdown();
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    Swal.fire("Error", "Favor de llamar al Administrador", "error");
                }
            });

        },
        estatusReserva: function(id_reserva)
        {
           $('#id_reserva_estatus').val(id_reserva);
           $('#motivo').val('');
           $('#observaciones').val('');
           $('#validar_no_reserva').val('');
            $.ajax({
                url: base_url + "index.php/Principal/editarReserva",
                type: 'POST',
                dataType: "json",
                data: { id_reserva: id_reserva },
                success: function(response) {
                    if (response && response.data && response.data.reserva) {
                        const reserva = response.data.reserva;
                        const presupuesto = response.data.presupuesto;
                        const partidas = response.data.partida;
                        const proyectos = response.data.proyecto;
                       

                        $("#varlidar_nombre_proveedor").val(reserva.razon_social || '');
                        $("#validar_no_proveedor").val(reserva.no_proveedor || '');
                        $("#validar_total_importe").val(reserva.total_importe || '');
                        if (reserva.instrumento) {
                            const fileUrl = base_url + reserva.instrumento;
                            const link = `<a href="${fileUrl}" target="_blank" class="me-2">
                                            <i class="mdi mdi-file font-21"></i> Ver archivo
                                        </a>`;
                            $("#previews").append(link);
                        }

                        if(reserva.no_reserva){
                            $('#numero').show();
                           $("#validar_no_reserva").val(reserva.no_reserva).prop("readonly", true);
                        }
                      
                           $("#validar_no_convenio").val(reserva.no_convenio || '');
                           const tbody = $('#ValidarMakeEditableEditar tbody');
                            tbody.empty();

                            presupuesto.forEach(p => {
                                let opcionesProyecto = '<option value="">Seleccione</option>';
                               proyectos.forEach(c => {
                                    opcionesProyecto += `<option value="${c.id_proyecto}" ${(c.id_proyecto == p.id_proyecto) ? 'selected' : ''}>${c.proyecto}</option>`;
                                });

                                let opcionesPartida = '<option value="">Seleccione</option>';
                                partidas.forEach(pa => {
                                    opcionesPartida += `<option value="${pa.id_partida}" ${(pa.id_partida == p.id_partida) ? 'selected' : ''}>${pa.cuenta_cable}</option>`;
                                });

                                const fila = `
                                    <tr>
                                        <td>
                                            <select class="select2 form-control"  readonly>
                                                ${opcionesProyecto}
                                            </select>
                                            <input type="hidden" value="${p.id_presupuesto}" readonly>
                                        </td>
                                        <td>
                                            <select class="select2 form-control" readonly>
                                                ${opcionesPartida}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" autocomplete="off" class="form-control" name="importe[]" value=${p.importe} readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                tbody.append(fila);
                            });

                       
                    } else {
                        Swal.fire("Atención", "No se encontró la información de la reserva.", "warning");
                    }
                },
                complete: function() {
                $("#modalEstatusReserva").modal('show');
                    $('.dropdown-toggle').dropdown();
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    Swal.fire("Error", "Favor de llamar al Administrador", "error");
                }
            });

        },
        selectMotivoGo: function()
        {
        let motivo = $('#motivo_go').val();
        console.log(motivo);
           if(motivo == 1){
               $('#observacion_go').hide();
              $('#numero_go').hide();
            }
            if(motivo == 2){
               $('#observacion_go').show();
                 $('#numero_go').hide();
            }
             if(motivo == 3){
                $("#validar_no_reserva_go").prop("readonly", false);
               $('#numero_go').show();
                $('#observacion_go').show();
            }
        },
        selectMotivo: function()
        {
 
        let motivo = $('#motivo').val();
 
           if(motivo == 1){
               $('#observacion').hide();
              $('#numero').hide();
            }
            if(motivo == 2){
               $('#observacion').show();
                 $('#numero').hide();
            }
             if(motivo == 3){
                $("#validar_no_reserva").prop("readonly", false);
               $('#numero').show();
                $('#observacion').show();
            }
        },
        formEliminarReserva: function()
        {
            $('#btnConfirmarReserva').on('click', function () {
            const id = $('#id_reserva_estatus').val();
            const motivo = $('#motivo').val();
            const observaciones = $('#validar_observaciones').val();
            const numero_reserva = $('#validar_no_reserva').val();
        
            if (!motivo) {
                 Swal.fire("Estatus", "Debe seleccionar un motivo para eliminar la reserva.", "error");
                return;
            }
            if (motivo==3 && !numero_reserva) {
                 Swal.fire("No, Reserva", "El numero de reserva es requerido.", "error");
                return;
            }
                $.ajax({
                    url: base_url + "index.php/Usuario/estatusReserva",
                    type: "POST",
                    dataType: "json",
                    data: {
                    id_reserva: id,
                    motivo: motivo,
                    observaciones: observaciones,
                    numero_reserva: numero_reserva
                    },
                    beforeSend: function()
                    {
                     $('#btnConfirmarReserva').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.error) {
                            Swal.fire("Atención", response.respuesta, "warning");
                        } else {
                            Swal.fire("Correcto", response.respuesta, "success");
                        }
                    },
                    complete: function () {
                    $('#modalEstatusReserva').modal('hide');
                    $('#btnConfirmarReserva').prop('disabled', false).html('Guardar');
                     Swal.fire("Correcto",'se guardo correctamente', "success");
                     setTimeout(() => window.location.reload(), 1500);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                   // alert("Error al eliminar");
                    console.log("Error(s):", textStatus, errorThrown);
                    }
                });
            });

        },
        formEliminarReservaGo: function()
        {
            $('#btnConfirmarReservaGo').on('click', function () {
            const id = $('#id_reserva_estatus_go').val();
            const motivo = $('#motivo_go').val();
            const observaciones = $('#observaciones_go').val();
            const numero_reserva = $('#validar_no_reserva_go').val();
        
            if (!motivo) {
                 Swal.fire("Estatus", "Debe seleccionar un motivo para eliminar la reserva.", "error");
                return;
            }
            if (motivo==3 && !numero_reserva) {
                 Swal.fire("No, Reserva", "El numero de reserva es requerido.", "error");
                return;
            }
                $.ajax({
                    url: base_url + "index.php/Usuario/estatusReservaGo",
                    type: "POST",
                    dataType: "json",
                    data: {
                    id_reserva: id,
                    motivo: motivo,
                    observaciones: observaciones,
                    numero_reserva: numero_reserva
                    },
                    beforeSend: function()
                    {
                     $('#btnConfirmarReservaGo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.error) {
                            Swal.fire("Atención", response.respuesta, "warning");
                        } else {
                            Swal.fire("Correcto", response.respuesta, "success");
                        }
                    },
                    complete: function () {
                    $('#modalReservaGo').modal('hide');
                    $('#btnConfirmarReservaGo').prop('disabled', false).html('Guardar');
                     setTimeout(() => window.location.reload(), 1500);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                   // alert("Error al eliminar");
                    console.log("Error(s):", textStatus, errorThrown);
                    }
                });
            });

        },
        finalizarPago: function(id)
        {
        Swal.fire({
                        title: "¿Está seguro?",
                        text: "¿Se Finalizara el Pago?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Eliminar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: base_url + "index.php/Usuario/finalizarPago",
                                type: "post",
                                dataType: "json", //expect return data as html from server
                                data: { id_reserva: id },
                                success: function (response, textStatus, jqXHR) {
                               
                                    if (response.error) {
                                        Swal.fire("Atención", response.respuesta, "warning");
                                    }else{
                                         Swal.fire("Eliminado", response.respuesta, "success");
                                    }
                                },
                                complete: function(){
                                 // window.location.reload();
                                  window.location.href = base_url + "index.php/Inicio";
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                  Swal.fire("Error",textStatus, "error");
                                },
                            });
                        }
                    });
        },
        eliminarReservaGo: function(id)
        {
        Swal.fire({
                        title: "¿Está seguro?",
                        text: "¿Desea eliminar la reserva?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Eliminar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: base_url + "index.php/Usuario/deleteReservaGo",
                                type: "post",
                                dataType: "json", //expect return data as html from server
                                data: { id_reserva: id },
                                success: function (response, textStatus, jqXHR) {
                               
                                    if (response.error) {
                                        Swal.fire("Atención", response.respuesta, "warning");
                                    }else{
                                         Swal.fire("Eliminado", response.respuesta, "success");
                                    }
                                },
                                complete: function(){
                                  window.location.reload();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                  Swal.fire("Error",textStatus, "error");
                                },
                            });
                        }
                    });
        },
        eliminarReserva: function(id)
        {
        Swal.fire({
                        title: "¿Está seguro?",
                        text: "¿Desea eliminar la reserva?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Eliminar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: base_url + "index.php/Usuario/deleteReserva",
                                type: "post",
                                dataType: "json", //expect return data as html from server
                                data: { id_reserva: id },
                                success: function (response, textStatus, jqXHR) {
                                    if (response.error) {
                                        Swal.fire("Atención", response.respuesta, "warning");
                                    }else{
                                         Swal.fire("Eliminado", response.respuesta, "success");
                                    }
                                },
                                complete: function(){
                                  window.location.reload();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    alert("error");
                                    console.log("error(s):" + textStatus, errorThrown);
                                    $("#mensajes").html("");
                                },
                            });
                        }
                    });
        },
        getVehiculo: function(id_vehiculo)
        {
          $('#modalVehiculo').modal('show');
       
      

          console.log(id_vehiculo);
           $.ajax({
            url: base_url + "index.php/Principal/getVehiculo",
            type: 'POST',
            data: {id_vehiculo},
            success: function(response) {
                if(!response.error) {
                let datos = response.data;
                $('#no_control').val(datos.no_control);
                $('#marca').val(datos.marca);
                $('#tipo').val(datos.tipo);
                $('#modelo').val(datos.modelo);
                $('#activo').val(datos.no_activo_fijo);
                $('#no_tarjeta').val(datos.no_tarjeta);
                $('#dotacion').val(datos.dotacion);
                $('#placa').val(datos.placa);
                $('#no_serie').val(datos.no_serie);
                $('#id_vehiculo').val(id_vehiculo);
                $('#id_usuario').val(datos.id_usuario).change();
                    
                } else {
                     Swal.fire("Error", response.respuesta, "error");
                }
            },
            error: function() {
                //toastr.error('Error de conexión');
                Swal.fire("Error", "Error de conexión", "error");
            },
         
           });
        },
        guardarVehiculo: function()
        {
            let no_control = $('#no_control').val();
            let marca      = $('#marca').val();
            let tipo       = $('#tipo').val();
            let modelo     = $('#modelo').val();
            let no_activo_fijo=$('#activo').val();
            let no_tarjeta = $('#no_tarjeta').val();
            let dotacion   = $('#dotacion').val();
            let placa      = $('#placa').val();
            let id_usuario = $('#id_usuario').val();
            let no_serie   = $('#no_serie').val();
            let id_vehiculo= $('#id_vehiculo').val();
          $.ajax({
                url: base_url + "index.php/Principal/guardarVehiculo",
                type: 'POST',
                data: {id_vehiculo,no_control, marca, tipo, modelo, no_activo_fijo, no_tarjeta, dotacion, placa, no_serie, id_usuario},
                beforeSend: function() {
                    $('#btn_vehiculo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                },
                success: function(response) {
                    if(response.error) {
                        Swal.fire("Error", response.respuesta, "error");
                    } else {
                        Swal.fire("Correcto", response.respuesta, "success");
                        setTimeout(() => {
                                    window.location.reload();

                            }, 1000);
                    }
                },
                error: function() {
                    //toastr.error('Error de conexión');
                    Swal.fire("Error", "Error de conexión", "error");
                },
                complete: function() {
                    $('#btn_vehiculo').prop('disabled', false).html('Guardar');
                }
            });

        },
        cerrarModalVehiculo: function(){
          $('#modalVehiculo').modal('hide');
        },
        guardarGo: function()
        {
        $('#btn_guardarGo').click(function() {
        // Validación básica
        if(!ini.inicio.validarFormularioGo()) {
            return false;
        }
        // Crear FormData para enviar tanto el formulario como el archivo
        var formData = new FormData();
        
        // Agregar datos del formulario principal
        formData.append('nombre_go', $('#nombre_go').val());
        formData.append('id_proveedor', $('#id_proveedor').val());
        formData.append('total_importe', $('#total_importe').val());
        formData.append('banco_go', $('#banco_go').val());


        // Agregar datos de la tabla
        $('#makeEditable3 tbody tr').each(function(index) {
            formData.append('proyecto[]', $(this).find('[name="proyecto[]"]').val());
            formData.append('partida[]', $(this).find('[name="partida[]"]').val());
            formData.append('importe[]', $(this).find('[name="importe[]"]').val());
            formData.append('propina[]', $(this).find('[name="propina[]"]').val());
        });

        // Enviar datos via AJAX

        $.ajax({
            url: base_url + "index.php/Principal/guardarReservaGO",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#btn_guardarGo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            },
            success: function(response) {
                if(response.error) {
                    Swal.fire("Error", response.respuesta, "error");
                } else {
                     Swal.fire("Correcto", response.respuesta, "success");
                       setTimeout(() => {
                                window.location.href = base_url + "index.php/Principal/listaReservaGO";

                        }, 1000);
                    
                }
            },
            error: function() {
                //toastr.error('Error de conexión');
                Swal.fire("Error", "Error de conexión", "error");
            },
            complete: function() {
                $('#btn_guardarGo').prop('disabled', false).html('Guardar');
            }
           });
        });

        },
        guardarReserva: function()
        {
        $('#btn_guardar').click(function(e) {
        e.preventDefault();
        
        // Validación básica
        if(!ini.inicio.validarFormulario()) {
            return false;
        }

        // Crear FormData para enviar tanto el formulario como el archivo
        var formData = new FormData();
        
        // Agregar datos del formulario principal
        formData.append('nombre_proveedor', $('#nombre_proveedor').val());
        formData.append('no_proveedor', $('#no_proveedor').val());
        formData.append('id_proveedor', $('#id_proveedor').val());
        formData.append('total_importe', $('#total_importe').val());
        formData.append('banco', $('#banco').val());

       // Obtener el estado del checkbox (true/false)
        let sinInstrumento = $('#customSwitch1').is(':checked');
    
        if (!sinInstrumento) {
             var instrumentoFile = $('#instrumento')[0].files[0];
             if(instrumentoFile){
               formData.append('instrumento', instrumentoFile);
               formData.append('no_convenio', $('#no_convenio').val());
             }else{
               Swal.fire("Atención", 'Sin Instrumento Jurídico', 'info');
               return false;
             }   
        } 

        // Agregar datos de la tabla
        $('#makeEditable2 tbody tr').each(function(index) {
            formData.append('proyecto[]', $(this).find('[name="proyecto[]"]').val());
            formData.append('partida[]', $(this).find('[name="partida[]"]').val());
            formData.append('importe[]', $(this).find('[name="importe[]"]').val());
        });

        // Enviar datos via AJAX

        $.ajax({
            url: base_url + "index.php/Principal/guardarReserva",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#btn_guardar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            },
            success: function(response) {
                if(response.error) {
                    Swal.fire("Error", response.respuesta, "error");
                } else {
                     Swal.fire("Correcto", response.respuesta, "success");
                       setTimeout(() => {
                                 window.location.href = base_url + "index.php/Principal/listaReservaPT";

                        }, 1000);
                    
                }
            },
            error: function() {
                //toastr.error('Error de conexión');
                Swal.fire("Error", "Error de conexión", "error");
            },
            complete: function() {
                $('#btn_guardar').prop('disabled', false).html('Guardar');
            }
           });
        });

        },
        principios: function(id_usuario)
        {
            Swal.fire({
                    title: 'Selecciona un Principio',
                    input: 'select',
                    inputOptions: {
                        '1': 'Legalidad',
                        '2': 'Honradez',
                        '3': 'Lealtad',
                        '4': 'Imparcialidad',
                        '5': 'Eficiencia',
                        '6': 'Economía',
                        '7': 'Disciplina',
                        '8': 'Profesionalismo',
                        '9': 'Objetividad',
                        '10': 'Transparencia',
                        '11': 'Rendición de Cuentas',
                        '12': 'Competencia pormérito',
                        '13': 'Eficacia',
                        '14': 'Integridad',
                        '15': 'Equidad'
                    },
                    inputPlaceholder: 'Selecciona...',
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (value) => {
                        if (!value) {
                            Swal.showValidationMessage('Por favor selecciona una opción');
                        }
                        return value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Opción seleccionada:', result.value);
                        ini.inicio.guardarHonestidad(id_usuario, result.value, 1);
                    }
                });
        },
        valores: function(id_usuario)
        {
            Swal.fire({
                    title: 'Seleccione un valor',
                    input: 'select',
                    inputOptions: {
                        '1': 'Interés Publico',
                        '2': 'Respeto',
                        '3': 'Respeto a los Derechos Humanos',
                        '4': 'Igualdad y no discriminación',
                        '5': 'Equidad de Género',
                        '6': 'Entorno Cultural y Ecológico',
                        '7': 'Cooperación',
                        '8': 'Liderazgo'
                    },
                    inputPlaceholder: 'Selecciona...',
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (value) => {
                        if (!value) {
                            Swal.showValidationMessage('Por favor selecciona una opción');
                        }

                        return value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Opción seleccionada:', result.value);
                       ini.inicio.guardarHonestidad(id_usuario, result.value, 2);
                    }
                });
        },
        reglas: function(id_usuario)
        {
            Swal.fire({
                    title: 'Selecciona una regla',
                    input: 'select',
                    inputOptions: {
                        '1': 'Actuación pública',
                        '2': 'Información pública',
                        '3': 'Contrataciones públicas, Licencias, permisos, autorización y concesiones.',
                        '4': 'Programas gubernamentales',
                        '5': 'Trámites y servicios',
                        '6': 'Recursos humanos',
                        '7': 'Administración de bines muebles e inmuebles',
                        '8': 'Procesos de evaluación',
                        '9': 'Control Interno',
                        '10': 'Procedimiento administrativo',
                        '11': 'Desempeño permanente con integridad',
                        '12': 'Cooperación con la integridad',
                        '13': 'Comportamiento digno'
                    },
                    inputPlaceholder: 'Selecciona...',
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (value) => {
                        if (!value) {
                            Swal.showValidationMessage('Por favor selecciona una opción');
                        }
                        return value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Opción seleccionada:', result.value);
                        ini.inicio.guardarHonestidad(id_usuario, result.value, 3);
                    }
                });
        },
        guardarHonestidad: function(id_usuario, valor, principio)
        {
             $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Principal/guardarHonestidad",
                    data : {id_usuario, valor, principio},
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);

                        if(response.error == false){
                            Swal.fire("Exitó", response.respuesta, "success");
                              setTimeout(() => {
                                 window.location.href = `${base_url}index.php/Inicio`;
                               }, 1500);
                                                    
                        }else{
                            Swal.fire("Error", response.respuesta , "error"); 
                            
                        } 
                    },
            
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse (response.responseText);
                       $('#btnComentario').prop('disabled', false).html('Guardar'); 
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                   }
                });

        },
        formComentario: function()
        {
             $("#formComentario").submit(function (e) {
                e.preventDefault();                
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Principal/formComentario",
                    data: $(this).serialize(),
                    dataType: 'json',
                    beforeSend: function(){
                        $('#btnComentario').prop('disabled', true).html('Guardando...'); 
                    },
                    success: function (response) {
                        console.log(response);

                        if(response.error == false){
                            Swal.fire("Exitó", response.respuesta, "success");
                              setTimeout(() => {
                                 window.location.reload();
                               }, 1500);
                                                    
                        }else{
                            Swal.fire("Error", response.respuesta , "error"); 
                            
                        } 
                    },
                    complete: function(){
                      $('#btnComentario').prop('disabled', false).html('Guardar'); 
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse (response.responseText);
                       $('#btnComentario').prop('disabled', false).html('Guardar'); 
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                   }
                });
            });
        },
        formActividad: function()
        {
            $("#form_actividad").submit(function (e) {
                e.preventDefault();                
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Principal/formActividad",
                    data: $(this).serialize(),
                    dataType: 'json',
                    beforeSend: function(){
                        $('#btnActividad').prop('disabled', true).html('Guardando...'); 
                    },
                    success: function (response) {
                        console.log(response);

                        if(response.error == false){
                            Swal.fire("Exitó", response.respuesta, "success");
                      
                            window.location.reload();
                                                    
                        }else{
                            Swal.fire("Error", response.respuesta , "error"); 
                            //$("#formParticipante")[0].reset();                         
                            return false;
                        } 
                    },
                    complete: function(){
                      $('#btnActividad').prop('disabled', false).html('Guardar'); 
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse (response.responseText);
                       $('#btnActividad').prop('disabled', false).html('Guardar'); 
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                   }
                });
            });

        },
        subirCsv: function()
        {
            let formData = new FormData();
            let csvFile = $('#fileParticipantes')[0].files[0];
            formData.append('fileParticipantes', $('#fileParticipantes')[0].files[0]);
        
            if (!csvFile) {
                Swal.fire("Error", "Es requerido el archivo CSV", "error");
                return;
            }
        
            Swal.fire({
                title: "Atención",
                text: "Esta operación puede regresar información, que no sea correcta",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Proceder"
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#btn_csv").hide();
                    $("#load_csv").show();
                    $.ajax({
                        url: base_url + "index.php/Principal/uploadCSV",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if(!response.error){
                                Swal.fire("Correcto", response.respuesta, "success");
                            }else{
                                Swal.fire("Error", "Favor de llamar al Administrador", "error");
                            }
                        },
                        complete: function(){
                            $("#btn_csv").show();
                            $("#load_csv").hide();
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            Swal.fire("Error", "Favor de llamar al Administrador", "error")
                            $("#btn_csv").show();
                            $("#load_csv").hide();
                            //alert("Error en la solicitud: " + error);
                        }
                    });
                }
            });
        },
        updateUsuario: function(){
                $('#formUsuario').submit(function(event) {
                    event.preventDefault();

                    var formData = $(this).serialize();
                  
                    console.log(formData);
                    
                    // var params = new URLSearchParams(formData);
                    // var editar = params.get('editar');

                    // console.log('Valor de editar:', editar);
                    //    if( editar===1 ){

                    //    }     
                    $.ajax({
                        url: base_url + "index.php/Usuario/UpdateUsuario",
                        type: "post",
                        dataType: "json",
                        data: formData,
                        beforeSend: function () {
                            // element.disabled = true;
                            $('#btnGuardar').prop('disabled', true);
                        },
                        complete: function () {
                            // element.disabled = false;
                            $('#btnGuardar').prop('disabled', false);
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.error) {
                                Swal.fire("Atención", response.respuesta, "warning");
                                return false;
                            }
                            Swal.fire("Correcto", "Registro exitoso", "success");
                            window.location.href = `${base_url}index.php/Usuario`;
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("error(s):" + jqXHR);
                        },
                    });

                });
        },
        deleteUsuario: function(id){
            // TODO preguntar si desea borrar o no con un swal 

            Swal.fire({
                title: "Estas Seguro?",
                text: "No podras revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Eliminar"
              }).then((result) => {
                if (result.isConfirmed) {
                  
                    console.log(id);
                    $.ajax({
                        url: base_url + "index.php/Usuario/deleteUsuario",
                        type: "post",
                        dataType: "json",
                        data: {'id_usuario':id},
                        beforeSend: function () {
                            // element.disabled = true;
                            $('#remover').prop('disabled', true);
                        },
                        complete: function () {
                            // element.disabled = false;
                            $('#remover').prop('disabled', false);
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.error) {
                                Swal.fire("Atención", response.respuesta, "warning");
                                return false;
                            }
                            Swal.fire("Correcto", "Registro eliminado con exito", "success");
                            window.location.href = `${base_url}index.php/Usuario`;
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("error(s):" + jqXHR);
                        },
                    });

                }
              });



            
        },
        limpiaModal:function(){
            $('#formUsuario')[0].reset();
            $('#id_clues').val('').change();
            $('#staticBackdropLabel').text('Agregar Usuario');
            $('#id_usuario').prop('disabled', true);
            $('#editar').prop('disabled', false);
            $('#editar').val(1);
            $("#contrasenia").prop("readonly", false);
        },
        obtenerCursosSac: function() {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/obtenerCursosSac",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    let html = ''; // Variable para almacenar el HTML de todas las filas
        
                    // Verifica si data es un array y itera sobre él
                    if (Array.isArray(data)) {
                        data.forEach(function(e) {
                            let icon = '';
                            let boton = '';
                            let idMoodle = '';
                  
                            if(e.id_moodle != null ){
                                idMoodle = e.id_moodle;
                            }
                            // Define el ícono según el valor de "visible"
                            if (e.activo == 1) {
                                icon += `<i class="mdi mdi-eye text-success font-18"></i>`;
                            } else if (e.activo == 0) {
                                icon += `<i class="mdi mdi-eye-off text-danger font-18"></i>`;
                            }
        
                            boton += `<button title="Ver detalle"
                                          onclick="ini.inicio.verDetalle(${e.id_cursos_sac})"
                                          class="btn btn-gradient-info px-4"><i
                                              class="mdi mdi-file-document-box font-21"></i>
                                      </button>
                                      <button title="editar"
                                          onclick="ini.inicio.editarCursoSac(${e.id_cursos_sac})"
                                          class="btn btn-gradient-warning px-4"><i
                                              class="dripicons-pencil font-21"></i>
                                      </button>`;
                            if (e.activo == 1) {
                                boton += `
                                   <button title="Desactivar"
                                         onclick="ini.inicio.activarCursoSac(${e.id_cursos_sac},3)"
                                         class="btn btn-gradient-success px-4 "><i
                                             class="mdi mdi-eye font-21"></i>
                                     </button>`;
                            } else if (e.activo == 0) {
                                boton += `
                                     <button title="Activar"
                                         onclick="ini.inicio.activarCursoSac(${e.id_cursos_sac},4)"
                                         class="btn btn-gradient-danger px-4 "><i
                                             class="mdi mdi-eye-off font-21"></i>
                                     </button>`;
                            }
                            boton +=`  <button title="eliminar"
                                           onclick="ini.inicio.eliminarCursoSac(${e.id_cursos_sac})"
                                           class="btn btn-gradient-danger px-4 "><i
                                               class="dripicons-trash font-21"></i>
                                       </button>`;
        
                            // Construye la fila
                            html += `
                                <tr>
                                    <td class="text-center">${e.id_cursos_sac}</td>
                                    <td class="text-center">${idMoodle}</td>
                                    <td class="text-center">${e.dsc_curso}</td>
                                    <td class="text-center">${icon}</td>
                                    <td class="text-center">${boton}</td>
                                </tr>`;
                        });
                    } else {
                        console.error("Error: Los datos no son un array.");
                    }
        
                    // Reemplaza el contenido del tbody con el nuevo HTML
                    $('#datatableCursos tbody').html(html);
                },
                error: function() {
                    Swal.fire("Error", "Error al obtener las categorías.", "error");
                }
            });
        },
        obtenerCategorias: function() {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/obtenerCategorias",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    let html = ''; // Variable para almacenar el HTML de todas las filas
        
                    // Verifica si data es un array y itera sobre él
                    if (Array.isArray(data)) {
                        data.forEach(function(e) {
                            let icon = '';
                            let boton = '';
                            let idMoodle = '';
                            if(e.id_moodle == null ){
                                idMoodle += '';
                            }else{
                                idMoodle = e.id_moodle;
                            }
        
                            // Define el ícono según el valor de "visible"
                            if (e.activo == 1) {
                                icon += `<i class="mdi mdi-eye text-success font-18"></i>`;
                            } else if (e.activo == 0) {
                                icon += `<i class="mdi mdi-eye-off text-danger font-18"></i>`;
                            }
        
                            // Define los botones según el valor de "visible"
                            boton += `<button title="editar" onclick="ini.inicio.editarCat(${e.id_categoria_sac})" class="btn btn-gradient-warning px-4">
                                        <i class="dripicons-pencil font-21"></i>
                                    </button>`;
                            if (e.activo == 1) {
                                boton += `
                                     <button title="desactivar" onclick="ini.inicio.activarCat(${e.id_categoria_sac},3)" class="btn btn-gradient-success px-4">
                                        <i class="mdi mdi-eye font-21"></i>
                                    </button>`;
                            }
                            if (e.activo == 0) {
                                boton += `
                                    <button title="Activar" onclick="ini.inicio.activarCat(${e.id_categoria_sac},4)" class="btn btn-gradient-danger px-4">
                                        <i class="mdi mdi-eye-off font-21"></i>
                                    </button>`;
                            }
                            boton += `<button title="eliminar" onclick="ini.inicio.eliminarCat(${e.id_categoria_sac})" class="btn btn-gradient-danger px-4">
                                        <i class="dripicons-trash font-21"></i>
                                    </button>`;
                           
        
                            // Construye la fila
                            html += `
                                <tr>
          
                                    <td class="text-center">${e.dsc_categoria_sac}</td>
                                    <td class="text-center">${idMoodle}</td>
                                    <td class="text-center">${icon}</td>
                                    <td class="text-center">${boton}</td>
                                </tr>`;
                        });
                    } else {
                        console.error("Error: Los datos no son un array.");
                    }
        
                    // Reemplaza el contenido del tbody con el nuevo HTML
                    $('#datatableCategorias tbody').html(html);
                },
                error: function() {
                    Swal.fire("Error", "Error al obtener las categorías.", "error");
                }
            });
        },
        eliminarProveedor: function(id )
        {
               Swal.fire({
                title: "!Atención¡",
                text: "¿Estas seguro de eliminar Proveedor?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si"
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Principal/eliminarProveedor",
                        dataType: "json",
                        data:{id_proveedor:id},
                        success: function(data) {
                            console.log(data);
                            if (!data.error) {
                                Swal.fire("Éxito", "Se elimino correctamente.", "success");
                                  setTimeout(() => {
                                        window.location.reload();
                                     }, 500);
                               
                            } else {
                                Swal.fire("Error", "Error al guardar comentario.", "error");
                            }
                           
                           
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });
            
                }
              });

        },
    
        eliminarCursoSac: function(id)
        {
            Swal.fire({
                title: "La categoria se eliminará",
                text: "¿Estas seguro de eliminar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si"
              }).then((result) => {
                if (result.isConfirmed) {
                
            
                }
              });
        },
        eliminarPerfil: function(id)
        {
            Swal.fire({
                title: "El perfil se eliminará",
                text: "¿Estas seguro de eliminar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si"
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Usuario/guardarPerfil",
                        dataType: "json",
                        data:{id_perfil:id, editar:2},
                        success: function(data) {
                            console.log(data);
                            if (data) {
                                Swal.fire("Éxito", "Se guardo correctamente.", "success")
                               
                            } else {
                                Swal.fire("Error", "Error al guardar comentario.", "error");
                            }
          
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });
              
                }
              });
        },
        eliminarPuesto: function(id)
        {
            Swal.fire({
                title: "El puesto se eliminará",
                text: "¿Estas seguro de eliminar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si"
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Usuario/guardarPuesto",
                        dataType: "json",
                        data:{id_puesto:id, editar:2},
                        success: function(data) {
                            console.log(data);
                            if (!data.error) {
                                Swal.fire("Éxito", "Se guardo correctamente.", "success")
                               
                            } else {
                                Swal.fire("Error", data.respuesta, "error");
                            }
          
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });
              
                }
              });
        },
        eliminarArea: function(id)
        {
            Swal.fire({
                title: "El área se eliminará",
                text: "¿Estas seguro de eliminar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si"
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Usuario/guardarArea",
                        dataType: "json",
                        data:{id_area:id, editar:2},
                        success: function(data) {
                            console.log(data);
                            if (!data.error) {
                                Swal.fire("Éxito", "Se guardo correctamente.", "success")
                               
                            } else {
                                Swal.fire("Error", data.respuesta, "error");
                            }
          
                        },
                        complete: function(){
                         window.location.reload();
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });
              
                }
              });
        },
        editarCursoSac: function(id)
        {
            $('#modalAgregarCategoria').modal('show');
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getCursoSac",
                dataType: "json",
                data:{id_curso:id},
                success: function(result) {
                    console.log(result);
                    const datos= result.data;
               
                    if (datos.categoria && datos.categoria.length > 0) {
                        let categoriasSeleccionadas = datos.categoria.map(cat => cat.id_categoria);
                        $('#categoria').val(categoriasSeleccionadas).change();
                    }
                    if (datos.periodo && datos.periodo.length > 0) {
                        let periodoSeleccionadas = datos.periodo.map(cat => cat.periodo);
                        console.log(periodoSeleccionadas);
                        $('#periodos').val(periodoSeleccionadas).change();
                    }
                    $('#nombre_curso').val(datos.curso[0].dsc_curso);
                    $('#id_moodle').val(datos.curso[0].id_moodle);
                    $('#descripcion').val(datos.curso[0].descripcion);
                    $('#des_larga').val(datos.curso[0].des_larga);
                    $('#detalle_dirigido').val(datos.curso[0].dirigido);
                    $('#detalle_duracion').val(datos.curso[0].duracion);
                    $('#detalle_autogestivo').val(datos.curso[0].autogestivo);
                    $('#detalle_horas').val(datos.curso[0].horas);
                    $('#detalle_curso_linea').val(datos.curso[0].curso_linea);
                    $('#detalle_informacion').val(datos.curso[0].informacion);
                    $('#editar_curso').val(id);
                    $('#editar').val(1);
                    if (datos.curso[0].img_deta_ruta) {
                        let html = `<img src="${base_url}${datos.curso[0].img_deta_ruta}" alt="Imagen detalle" style="max-width: 100%;">`;
                        $('#vista_img_deta_ruta').html(html);
                    }
                
                    if (datos.curso[0].img_ruta) {
                        let html2 = `<img src="${base_url}${datos.curso[0].img_ruta}" alt="Imagen principal" style="max-width: 100%;">`;
                        $('#vista_img_ruta').html(html2);
                    }
                    $('#summernote').summernote('code', datos.curso[0].des_larga);
                    // Marcar o desmarcar el checkbox según el valor de new_curso
                    if (datos.curso[0].nuevo === 1) {
                        $('#new_curso').prop('checked', true);
                    } else {
                        $('#new_curso').prop('checked', false);
                    }


                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            }); 
        },
        editarPuesto: function(id)
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getPuesto",
                dataType: "json",
                data:{id_puesto:id},
                success: function(data) {
                    console.log(data);
                    Swal.fire({
                        title: "<strong>EDITAR DE LA PERFIL</strong>",
                        icon: "info",
                        html: `<textarea id="comentarioInput" class="form-control" placeholder="Escriba la Categoria">${data.dsc_puesto}</textarea>`,
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText: "Guardar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const comentario = document.getElementById("comentarioInput").value.trim();       
                        
                            if (comentario === "") {
                                Swal.fire("Error", "El campo no puede estar vacío.", "error");
                                return;
                            }
                            const data = {comentario, editar:1, id_puesto:id };
                            $.ajax({
                                type: "POST",
                                url: base_url + "index.php/Usuario/guardarPuesto",
                                dataType: "json",
                                data:data,
                                success: function(data) {
                                    console.log(data);
                                    if (!data.error) {
                                        Swal.fire("Éxito", "Se guardo correctamente.", "success")
                                       
                                    } else {
                                        Swal.fire("Error", data.respuesta, "error");
                                    }
                                   
                                },
                                error: function() {
                                    Swal.fire("Error", "Error al guardar comentario.", "error")
                                }
                            });
                        }
                    });
                   
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });
        },
       editarArea: function(id)
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getArea",
                dataType: "json",
                data:{id_area:id},
                success: function(res) {
                    console.log(res);
                    let data = res.data;
                    $('#dsc_area').val(data.dsc_area);
                    $('#dsc_corto').val(data.dsc_corto);
                    $('#id_area').val(id);
                    if(data.titular){
                      $('#titular').val(data.titular).change();
                    }
                },
                complete: function(){
                    $('#modalArea').modal('show');
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });
        },
        guardarEdicionArea: function()
        {
            let dsc_area   =  $('#dsc_area').val();
            let dsc_corto  =  $('#dsc_corto').val();
            let id_usuario =  $('#titular').val();
            let id_area    =  $('#id_area').val();

             $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/guardarArea",
                dataType: "json",
                data:{dsc_area, dsc_corto, id_usuario, editar:1, id_area },
                success: function(res) {
                    console.log(res);
                    if(res.error){
                        Swal.fire("Atención", res.respuesta, "info")
                    }else{
                         Swal.fire("Correcto", res.respuesta, "success");
                          $('#guardarEdicionArea').prop('disabled', false).html('Guardar cambios');
                          setTimeout(() => {
                            window.location.reload();
                          }, 1500);
                         
                    }
                 
                },
                beforeSend: function(){
                         $('#guardarEdicionArea').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                },
                complete: function(){
                    $('#modalArea').modal('show');
                    
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });

        },
        editarPerfil: function(id)
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getPerfil",
                dataType: "json",
                data:{id_perfil:id},
                success: function(data) {
                    console.log(data);
                    Swal.fire({
                        title: "<strong>EDITAR DE LA PERFIL</strong>",
                        icon: "info",
                        html: `<textarea id="comentarioInput" class="form-control" placeholder="Escriba la Categoria">${data.dsc_perfil}</textarea>`,
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText: "Guardar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const comentario = document.getElementById("comentarioInput").value.trim();       
                        
                            if (comentario === "") {
                                Swal.fire("Error", "El campo no puede estar vacío.", "error");
                                return;
                            }
                            const data = {comentario, editar:1, id_perfil:id };
                            $.ajax({
                                type: "POST",
                                url: base_url + "index.php/Usuario/guardarPerfil",
                                dataType: "json",
                                data:data,
                                success: function(data) {
                                    console.log(data);
                                    if (data) {
                                        Swal.fire("Éxito", "Se guardo correctamente.", "success")
                                       
                                    } else {
                                        Swal.fire("Error", "Error al guardar comentario.", "error");
                                    }
                                   
                                },
                                error: function() {
                                    Swal.fire("Error", "Error al guardar comentario.", "error")
                                }
                            });
                        }
                    });
                   
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });
        },
        tiketListo: function(id_tiket)
        {
            Swal.fire({
            title: "¿Esta seguro de cambiar el estatus?",
            text: "El tiket esta apunto de cambiar",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "!Hecho¡"
            }).then((result) => {
            if (result.isConfirmed) {
                 $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/tiketListo",
                dataType: "json",
                data:{id_tiket},
                success: function(data) {
                    console.log(data);
                    window.location.reload();
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });
            }
            });
        
         

        },
        guardarCursos: function () {
            $("#formCurso").submit(function (e) {
                e.preventDefault(); 
                let Url = '';
                var formData = new FormData(this); 
                formData.append('editar_curso', $("#editar_curso").val());
                let editar = $('#editar').val();
        
                if(editar == 1){
                    Url = base_url + "index.php/Usuario/editarCurso";
                }else{
                    Url = base_url + "index.php/Usuario/guardarCursos";
                }
                $("#guardarCursos").hide();
                $("#load_curso").show();
        
                $.ajax({
                    type: "POST",
                    url: Url,
                    data: formData,
                    processData: false,  
                    contentType: false,  
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(response.error){
                            Swal.fire("Error", `${response.respuesta}`,"error");
                        }else{
                            Swal.fire("Éxito", "Se guardó correctamente", "success");
                            $("#formCurso")[0].reset();
                            $("#categoria").val('');
                            $("#periodo").val('');
                        }
                    },
                    complete: function(){
                        $('#modalAgregarCategoria').modal('hide');
                        $("#guardarCursos").show();
                        $("#load_curso").hide();
                        $('#editar').val(0);
                        ini.inicio.getCursos();
                    },
                    error: function (response, jqXHR, textStatus, errorThrown) {
                        var res = JSON.parse(response.responseText);
                        Swal.fire("Error", '<p>' + res.message + '</p>');  
                    }
                });
            });
        },        
        
        agregarPeriodo: function()
        {
            $('#modalAgregarPeriodo').modal('show');
            $("#dia_inicio").val(0);
            $("#dia_fin").val(0);
            $("#mes").val(0);
            $("#periodo").val(0);
        },
        eliminarPeriodo: function(id)
        {
           if(id){
            Swal.fire({
                title: "Estas Seguro?",
                text: "No podras revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Eliminar"
              }).then((result) => {
                if (result.isConfirmed) {
                  
                    console.log(id);
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Usuario/eliminarPeriodo",
                        dataType: "json",
                        data:{id_periodo:id, editar:2},
                        success: function(data) {
                            console.log(data);
                            ini.inicio.getPeriodos()
                            
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });

                }
              });
       
           }
            
        },
        editarPeriodo: function(id)
        {
           if(id){
            $('#modalAgregarPeriodo').modal('show');
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/optenerPeriodo",
                dataType: "json",
                data:{id_periodo:id, editar:0},
                success: function(data) {
                    console.log(data);
                    $("#dia_inicio").val(data.dia_inicio).change();
                    $("#dia_fin").val(data.dia_fin).change();
                    $("#mes").val(data.mes).change();
                    $("#periodo").val(data.periodo).change();
                    $("#editar_periodo").val(1);
                    $("#id_periodo").val(id);
                    
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });
           }
            
        },
        guardarPeriodo: function()
        {

           
            let diaInicio = $("#dia_inicio").val();
            let diaFin     = $("#dia_fin").val();
            let mes       = $("#mes").val();
            let editar_periodo       = $("#editar_periodo").val();

            let id_periodo       = $("#id_periodo").val();
            let periodo       = $("#periodo").val();
            // Convertir a números
            diaInicio = parseInt(diaInicio, 10);
            diaFin = parseInt(diaFin, 10);

            // Validar que las fechas sean números válidos
            if (isNaN(diaInicio) || isNaN(diaFin)) {
                Swal.fire("Error", "Las fechas deben ser números válidos.", "error");
                return;
            }

            // Validar que el día de inicio no sea mayor que el día de fin
            if (diaInicio > diaFin) {
                Swal.fire("Error", "El día de inicio no puede ser mayor que el día de fin.", "error");
                return;
            }
            if(!diaInicio || !diaFin || !mes){
                Swal.fire("Error", "Todos los campos son requeridos", "error");
                return;
            }
            $("#guardar_periodo").hide();
            $("#load_periodo").show();
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/guardarPeriodo",
                dataType: "json",
                data:{diaInicio, diaFin, mes, periodo, editar_periodo, id_periodo},
                success: function(data) {
                    console.log(data);
                    if (!data.error) {
                        Swal.fire("Éxito", "Se guardo correctamente.", "success")
                       
                    } else {
                        Swal.fire("Error", data.respuesta , "error");
                    }
                    ini.inicio.getPeriodos()
                },
                complete: function(){
                 //   $('#modalAgregarPeriodo').modal('hide');
                    $("#load_periodo").hide();
                    $("#guardar_periodo").show();
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });

        
        },
        deleteUsuario: function(id){
            // TODO preguntar si desea borrar o no con un swal 

            Swal.fire({
                title: "Estas Seguro?",
                text: "No podras revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Eliminar",
                cancelButtonText: "Cancelar"
              }).then((result) => {
                if (result.isConfirmed) {
                  
                    console.log(id);
                    $.ajax({
                        url: base_url + "index.php/Usuario/deleteUsuario",
                        type: "post",
                        dataType: "json",
                        data: {'id_usuario':id},
                        success: function (response, textStatus, jqXHR) {
                            if (response.error) {
                                Swal.fire("Atención", response.respuesta, "warning");
                                return false;
                            }
                            Swal.fire("Correcto", "Registro eliminado con exito", "success");
                            //window.location.href = `${base_url}index.php/Usuario`;
                            // $('#datatableUsuario').bootstrapTable('refresh');
                            ini.inicio.getUsuarios();
                            //$('#usuariosTable').DataTable().reload(); // Refresca la tabla
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("error(s):" + jqXHR);
                        },
                    });

                }
              });
        },
        borrarDatos: function(){
            console.log('entro ');
            $('#editar').val(0);
            $("#formCurso")[0].reset();
            $("#categoria").val(null).trigger('change');
            $("#periodos").val(null).trigger('change');
            $("#periodos").val(null).trigger('change');
            $('#vista_img_deta_ruta').html('');
            $('#vista_img_ruta').html('');
            $('#summernote').summernote('code', '<p>Escribe aquí el contenido de la descripción larga</p>');
            $('#detalle_dirigido').val('');
            $('#detalle_duracion').val('');
            $('#detalle_autogestivo').val('');
            $('#detalle_horas').val('');
            $('#detalle_curso_linea').val('');
            $('#detalle_informacion').val('');
        
            
        },
        passwordForm: function(){
            $('#passwordForm').on('submit', function (e) {
                e.preventDefault(); // Evita que el formulario se envíe
                var contrasenia = $('#contrasenia').val();
                $id_usuario = $("#id_usuario").val();
                var confirmar_contrasenia = $('#confirmar_contrasenia').val();
                if(!contrasenia || !confirmar_contrasenia){
                    Swal.fire("Campo vacios", 'Favor de ingresar contraseña' ,"error");
                    return;
                }
                if (contrasenia != confirmar_contrasenia) { // Cambia "contraseñaCorrecta" por tu contraseña válida
                    Swal.fire("error", 'La contraseñas no son identicas, Favor de verificar' ,"error");
                    return;
                } 
                $("#btnCambioPass").hide();
                $("#load_btnCambioPass").show();
                
                var formData = $("#passwordForm").serialize();
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/cambioPassword",
                    data:formData,
                    dataType: "json",
                    success: function (response) {
                        if(!response.error){
                            Swal.fire("Éxito", '<p> '+ response.respuesta + '</p>', 'success'); 
                            window.location.href = base_url + 'index.php/Login/cerrar';
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'error'); 
                        }
                       
                    },
                    complete: function(){
                        $("#btnCambioPass").show();
                        $("#load_btnCambioPass").hide();
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                         var res= JSON.parse (response.responseText);
                         Swal.fire("Error", '<p> '+ res.message + '</p>', 'error');  
                    }
                });
            });

        },
      agregarUsuario: function(){
            $("#formAgregarUsuarioTsi").submit(function (e) {
                e.preventDefault(); 
                $("#id_dependencia").prop("disabled", false);
                
                // CREAR FormData PARA ENVIAR ARCHIVOS
                var formData = new FormData(this);
                
                $("#id_dependencia").prop("disabled", true);
                $("#btn_save").hide();
                $("#btn_load").show();
                
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaUsuarioSti",
                    data: formData, // Usar FormData en lugar de serialize()
                    dataType: "json",
                    contentType: false, // IMPORTANTE para FormData
                    processData: false, // IMPORTANTE para FormData
                    success: function (response) {
                        console.log(response);
                        if(response.error){
                            Swal.fire("error", response.respuesta ,"error");
                        } else {
                            Swal.fire("success", "Se guardó con éxito", 'success');
                            $("#formAgregarUsuarioTsi")[0].reset();
                            $("#btn_save").show();
                            $("#btn_load").hide();
                            window.location.href = base_url + "index.php/Inicio/usuarios";
                        }
                    },
                    error: function (response, jqXHR, textStatus, errorThrown) {
                        var res = JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>', 'error');  
                        $("#btn_save").show();
                        $("#btn_load").hide();
                    }
                });
            });
        },
        cleanDetenidos: function()
        {
            $("#btn_clean_detenidos").hide();
            $("#btn_clean_load").show();
            $.ajax({
                type: "GET",
                url: base_url + "index.php/Agregar/cleanDetenidos",
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if(!response.error){
                        Swal.fire("Éxito", '<p> '+ response.respuesta + '</p>', 'success');  
                        window.location.reload();
                    }else{
                        Swal.fire("Error", '<p> '+ response.respuesta + '</p>', 'error');  
                    }

                },
                complete: function(){
                    $("#btn_clean_load").hide();
                    $("#btn_clean_detenidos").show();
                },
                error: function (response,jqXHR, textStatus, errorThrown) {
                     var res= JSON.parse (response.responseText);
                     Swal.fire("Error", '<p> '+ res.message + '</p>', 'error');  
                     $("#btn_save").show();
                     $("#btn_load").hide();
                }
            });
        },
        getParticipante: function(id){
            
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getParticipante",
                dataType: "json",
                data:{id_usuario:id},
                success: function(data) {
                    console.log(data);
                    if (data) {

                        $('#rfc').val(data.rfc);;
                        $('#id_dependencia').val(data.id_dependencia).trigger('change');
                        $('#id_perfil').val(data.id_perfil).trigger('change');
                      //  const fechaCompleta = data.fec_nac; // Ejemplo de fecha
                      //  const fechaFormateada = fechaCompleta.split('T')[0]; // Extrae "1983-10-10"
                      //  $('#fec_nac').val(fechaFormateada); // Asigna la fecha al campo
                        $('#usuario').val(data.usuario);

                        $("#nombre").val(data.nombre);
                        $("#primer_apellido").val(data.primer_apellido);
                        $("#segundo_apellido").val(data.segundo_apellido);
                        //$("#id_municipio").val(data.id_municipio);
                        $("#id_municipio").val(data.id_municipio).trigger('change');
                        $("#curp").val(data.curp);
                        $("#curp_viejo").val(data.curp);
                        $("#fec_nac").val(data.fec_nac);
                        $("#centro_gestor").val(data.centro_gestor);
                        $("#jefe_inmediato").val(data.jefe_inmediato);
                        $("#area").val(data.area);
                        $("#correo_enlace").val(data.correo_enlace);
                        $("#correo").val(data.correo);
                        $("#denominacion_funcional").val(data.denominacion_funcional);
                        $("#funcion").val(data.funcion);
                        $("#nivel").val(data.nivel).trigger('change');
                        $("#id_sexo").val(data.id_sexo).trigger('change');
                        //Swal.fire("Exitó",response.respuesta , "success")
                        $("#editar").val(1);
                        $("#id_detenido").val(0);
                        $("#id_participante").val(id);
                        st.agregar.validarCURP();
                      

                    } else {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            });
        },
        getDetenido: function(id){
            
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getDetenido",
                dataType: "json",
                data:{id_usuario:id},
                success: function(data) {
                    console.log(data);
                    if (data) {

                        $('#rfc').val(data.rfc);;
                        $('#id_dependencia').val(data.id_dependencia).trigger('change');
                        $('#id_perfil').val(data.id_perfil).trigger('change');
                      //  const fechaCompleta = data.fec_nac; // Ejemplo de fecha
                      //  const fechaFormateada = fechaCompleta.split('T')[0]; // Extrae "1983-10-10"
                      //  $('#fec_nac').val(fechaFormateada); // Asigna la fecha al campo
                        $('#usuario').val(data.usuario);

                        $("#nombre").val(data.nombre);
                        $("#primer_apellido").val(data.primer_apellido);
                        $("#segundo_apellido").val(data.segundo_apellido);
                        //$("#id_municipio").val(data.id_municipio);
                        $("#id_municipio").val(data.id_municipio).trigger('change');
                        $("#curp").val(data.curp);
                        $("#curp_viejo").val(data.curp);
                        $("#fec_nac").val(data.fec_nac);
                        $("#centro_gestor").val(data.centro_gestor);
                        $("#jefe_inmediato").val(data.jefe_inmediato);
                        $("#area").val(data.area);
                        $("#correo_enlace").val(data.correo_enlace);
                        $("#correo").val(data.correo);
                        $("#denominacion_funcional").val(data.denominacion_funcional);
                        $("#funcion").val(data.funcion);
                        $("#id_nivel").val(data.id_nivel).trigger('change');
                        $("#id_sexo").val(data.id_sexo).trigger('change');
                        //Swal.fire("Exitó",response.respuesta , "success")
                        $("#editar").val(1);
                        $("#id_detenido").val(id);
                        $("#id_participante").val(0);
                        st.agregar.validarCURP();
                      

                    } else {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            });
        },
           selectCategory: function(category) {
            currentCategory = category;
            const chat = document.getElementById('chat');
            
            // Mostrar selección del usuario
            let categoryName = '';
            let icon = '';
            
            switch(category) {
                case 'asistencia':
                    categoryName = "ASISTENCIA TÉCNICA";
                    icon = "fas fa-tools";
                    break;
                case 'accesorios':
                    categoryName = "ACCESORIOS TI";
                    icon = "fas fa-keyboard";
                    break;
                case 'plataformas':
                    categoryName = "PLATAFORMAS WEB";
                    icon = "fas fa-globe";
                    break;
                case 'impresoras':
                    categoryName = "IMPRESORAS";
                    icon = "fas fa-print";
                    break;
                case 'otro':
                    categoryName = "OTRO";
                    icon = "fas fa-question-circle";
                    break;
            }
            
            chat.innerHTML += `
                <div class="message user-message">
                    <div class="content">
                        <p><i class="${icon}"></i> ${categoryName}</p>
                    </div>
                    <div class="avatar">YO</div>
                </div>
            `;
            
            // Mostrar opciones según categoría
            setTimeout(() => {
                if (category === 'otro') {
                    showTextInput();
                } else if (category === 'impresoras') {
                    showPrinterOptions();
                } else {
                    showDefaultOptions(category);
                }
                
                chat.scrollTop = chat.scrollHeight;
            }, 500);
        },
        openSupportModal: function() {
        $('#supportModal').modal('show');
        //  resetChat();
        },
        getUsuario: function(id){
       
            $('#agregarUsuario').modal('show');
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getUsuario",
                dataType: "json",
                data:{id_usuario:id},
                success: function(data) {
                    console.log(data);
                    if (data) {

                        $('#id_usuario').val(data.id_usuario);
                        $('#editar').val(1);
                        $('#nombre').val(data.nombre);
                        $('#correo').val(data.correo);
                        $('#rfc').val(data.rfc);
                        $('#primer_apellido').val(data.primer_apellido);
                        $('#segundo_apellido').val(data.segundo_apellido);
                        $('#usuario').val(data.usuario);
                        $('#id_sexo').val(data.id_sexo).trigger('change');
                        $('#id_perfil').val(data.id_perfil).trigger('change');
                        $('#id_area').val(data.id_area).trigger('change');
                        $('#id_jefe_inmediato').val(data.id_jefe_inmediato).trigger('change');
                        $('#id_puesto').val(data.id_puesto).trigger('change');
                        $('#id_tipo_empleado').val(data.id_tipo_empleado).trigger('change');
                        $('#no_empleado').val(data.no_empleado);
                        $('#nivel').val(data.nivel);
                        $('#extencion').val(data.extencion);
                         if (data.fec_nac) {
                            const fechaCompleta = data.fec_nac;
                            const fechaFormateada = fechaCompleta.split('T')[0];
                            $('#fec_nac').val(fechaFormateada);
                        } else {
                            $('#fec_nac').val(''); // Opcional: limpiar el campo si no hay fecha
                        }
                     

                    } else {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                },
                complete: function(){
                    $('#agregarUsuario').modal('show');
                    
                    $('#agregarUsuario').on('shown.bs.modal', function () {
                        // Pequeño delay para asegurar que el DOM esté listo
                        setTimeout(function() {
                            // Verificar que Select2 esté disponible
                            if (typeof $.fn.select2 === 'function') {
                                $('.select2').select2({
                                    placeholder: "Seleccione una opción",
                                    allowClear: true,
                                    width: '100%' // Para mejor responsive
                                });
                            } else {
                                console.error('Select2 no está cargado. Verifica el orden de carga de los scripts.');
                            }
                        }, 50);
                    });
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            });
        },
        getUsuarios: function()
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getUsuarios",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    let html = ''; // Variable para almacenar el HTML de todas las filas
                    if (Array.isArray(data)) {
                        data.forEach(function(e) {
                           let boton =`
                              <a href="javascript:void(0);" data-toggle="modal" data-animation="bounce"
                                                data-target=".bs-example" onclick="ini.inicio.getUsuario(${e.id_usuario})"><i
                                                        class="mdi mdi-pencil text-success font-18"></i></a>
                              <a href="javascript:void(0);" onclick="ini.inicio.deleteUsuario(${e.id_usuario})"><i
                                                        class="mdi mdi-trash-can text-danger font-18"></i></a>`;                
                            html += `
                                <tr>
                                    <td class="text-center">P${e.nombre_completo}</td>
                                    <td class="text-center">${e.curp}</td>
                                    <td class="text-center">${e.dsc_perfil}</td>
                                    <td class="text-center">${e.dsc_sexo}</td>
                                    <td class="text-center">${boton}</td>
                                </tr>`;
                        });
                    } else {
                        console.error("Error: Los datos no son un array.");
                    }

                    $('#datatable tbody').html(html);
                },
                error: function() {
                    Swal.fire("Error", "Error al obtener las categorías.", "error");
                }
            });

        },
        debounce: function(func, wait) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        },
       avanceActividad: function(id_actividad, avance){
           console.log(id_actividad, avance);
            $.ajax({
                    url: base_url + "index.php/Principal/avanceActividad",
                    type: 'POST',
                    data: {id_actividad, avance},
                    success: function(response) {
                       console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        Swal.fire("Error", "Favor de llamar al Administrador", "error");
                    }
                }); 
       },
       formDenuncia: function()
       {
        var formData = $("#form_denuncia").serialize();
        $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/formDenuncia",
                    data:formData,
                    dataType: "json",
                     beforeSend: function()
                    {
                      $('#btnDenuncia').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    success: function (response) {
                        console.log(response);
                        if(response.error){
                            Swal.fire("error", response.respuesta ,"error");
                        }else{
                            Swal.fire("success", response.respuesta, 'success');
                            setTimeout(() => {
                                window.location.href = base_url + "index.php/Principal/ListaDenuncia";
                            }, 1500);
                      
                       }
                       
                    },
                    complete: function()
                    {
                      $('#btnDenuncia').prop('disabled', false).html('Guardar');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                         var res= JSON.parse (response.responseText);
                        //  console.log(res.message);
                         Swal.fire("Error", '<p> '+ res.message + '</p>', 'error');  
                        $('#btnDenuncia').prop('disabled', false).html('Guardar');
                    }
                });

       },
       agregarInventario: function()
       {
        
         $("#modelInventarios").modal('show');  
          $('.select2').select2({
             placeholder: "Seleccione una opción",
             allowClear: true,
             width: '100%' // Para mejor responsive
         });
       },
       agregarAlba: function() {
            // Alternativa para asegurar que funcione
            var modal = new bootstrap.Modal(document.getElementById('modalAlba'));
            modal.show();
            document.getElementById("formAgregarAlba").reset();
            $("#previewFoto").attr("src", "").hide();
           $("#previewProtocolo").attr("src", "").hide();

        },
        formConfiguracion: function()
        {
              $("#formConfiguracion").submit(function (e) {
                e.preventDefault(); 
            
                var formData = $("#formConfiguracion").serialize();
             
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/formConfiguracion",
                    data:formData,
                    dataType: "json",
                     beforeSend: function()
                    {
                      $('#btnConfig').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    success: function (response) {
                        console.log(response);
                        if(response.error){
                            Swal.fire("error", response.respuesta ,"error");
                        }else{
                            Swal.fire("success", response.respuesta, 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                      
                       }
                       
                    },
                    complete: function()
                    {
                      $('#btnConfig').prop('disabled', false).html('Guardar Configuración');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                         var res= JSON.parse (response.responseText);
                        //  console.log(res.message);
                         Swal.fire("Error", '<p> '+ res.message + '</p>', 'error');  
                        $('#btnConfig').prop('disabled', false).html('Guardar Configuración');
                    }
                });
            });

        },
        getAlba: function(id_alba){
           var modal = new bootstrap.Modal(document.getElementById('modalAlba'));
            modal.show();
             $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/getAlba",
                    data: {id_alba},
                    dataType: "json",
                   success: function (response) {
                        if (!response.error) {
                            const data = response.data;

                            $('#editar').val(1);
                            $('#id_alba').val(id_alba);
                            $('#nombre').val(data.nombre);
                            $('#primer_apellido').val(data.primer_apellido);
                            $('#segundo_apellido').val(data.segundo_apellido);
                            $('#id_estatus').val(data.id_estatus).change();
                            $('#id_estatus').val(data.id_estatus).change();
                               // Mostrar imagen si viene de la BD
                            if (data.foto) {
                            $('#previewFoto').attr('src', base_url + data.foto).show();
                            } else {
                            $('#previewFoto').hide();
                            }

                            if (data.protocolo) {
                            $('#previewProtocolo').attr('src', base_url + data.protocolo).show();
                            } else {
                            $('#previewProtocolo').hide();
                            }
                            $('#nacionalidad').val(data.nacionalidad);
                            $('#edad').val(data.edad);
                            $('#id_sexo').val(data.id_sexo).change();

                            const fechaCompleta = data.fecha_nacimiento;
                            const fecDesactivacion = data.fec_desactivacion;
                            const fecActivacion = data.fec_activacion;
                            const fechaFormateada = fechaCompleta.split('T')[0];
                            const fechaFormateadaD = fecDesactivacion.split('T')[0];
                            const fechaFormateadaA = fecActivacion.split('T')[0];
                            $('#fecha_nacimiento').val(fechaFormateada);
                            $('#fec_desactivacion').val(fechaFormateadaD);
                            $('#fec_activacion').val(fechaFormateadaA);
                         

                         

                        } else {
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                    }
                });
        },
        deleteAlba: function(id_alba){
            Swal.fire({
                    title: "¡Esta seguro de eliminar!",
                    text:  `Esta accion eliminara el registro`,
                    icon: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                    }).then((result) => {
                    if (result.isConfirmed) {
                            $.ajax({
                                type: "POST",
                                url: `${base_url}index.php/agregar/deleteAlba`,
                                dataType: "json",
                                data: {  id_alba },
                                success: function(response) {
                           
                                    if(!response.error){
                                      Swal.fire("Correcto",response.respuesta, "success");
                                        setTimeout(() => {
                                            window.location.reload();
                                               
                                        }, 1500);
                                    }else{
                                     Swal.fire("error", response.respuesta, "error");
                                    }
                                   
                            
                                    },
                                    error: function() {
                                        Swal.fire("Error", "Error al cargar los detalles del curso.", "error");
                                    }
                                });
                    }
            });

        },
        modalActividad: function(event, id_usuario)
        {
            if(event){
              $("#modalActividad").modal("show");
              $("#id_usuario").val(id_usuario);
            }else{
               $("#modalActividad").modal("hide"); 
            }
         
        },
        getDenuncia: function(id_denuncia){
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Agregar/getDenuncia",
                data: {id_denuncia},
                dataType: "json",
                success: function (response) {
                     console.log(response);
                     $("#como_ocurrieron").val(response.como_ocurrieron);
                     $("#correo").val(response.correo);
                     $("#cuando_ocurrieron").val(response.cuando_ocurrieron);
                     $("#denunciando").val(response.denunciando);
                     $("#domicilio").val(response.domicilio);
                     $("#donde_ocurrieron").val(response.donde_ocurrieron);
                     $("#nombre").val(response.nombre);
                     $("#telefono").val(response.telefono);
                },
                complete: function(){
                   $("#modalDenuncia").modal('show');
                },
                error: function (response,jqXHR, textStatus, errorThrown) {          
                    Swal.fire("Error", '<p> '+ res.message + '</p>'); 
                    $('#btnAlba').prop('disabled', false).html('Guardar');
                }
            });

        },
        modalDenuncia: function(event)
        {
         (!event)?$("#modalDenuncia").modal('hide'):$("#modalDenuncia").modal('show');
        },
        deleteDenuncia: function(id_denuncia)
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Agregar/deleteDenuncia",
                data: {id_denuncia},
                dataType: "json",
                success: function (response) {
                    if(!response.error){
                        Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                        setTimeout(() => {
                           // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                            window.location.reload();
                        }, 1500);
                    }else{
                        Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                    }
                },
                error: function (response,jqXHR, textStatus, errorThrown) {          
                    Swal.fire("Error", '<p> '+ res.message + '</p>'); 
                    $('#btnAlba').prop('disabled', false).html('Guardar');
                }
            });

        },
       formInventario: function() {
            let formData = new FormData();

            // Agregar el archivo usando el id del input
            let fileInput = document.getElementById("foto");
            if (fileInput.files.length > 0) {
                formData.append("foto", fileInput.files[0]);
            }

            // Agregar el resto de los campos del formulario
            $("#formInventario").serializeArray().forEach(function (campo) {
                formData.append(campo.name, campo.value);
            });

            $.ajax({
                type: "POST",
                url: base_url + "index.php/Agregar/formInventario",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                beforeSend: function () {
                    $('#btnInventario').prop('disabled', true)
                        .html('<i class="mdi mdi-content-save"></i>Guardando...');
                },
                success: function (response) {
                    if (!response.error) {
                        Swal.fire("Correcto", '<p>' + response.respuesta + '</p>', 'success');  
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        Swal.fire("Atención", '<p>' + response.respuesta + '</p>', 'info');  
                    }
                },
                complete: function () {
                    $("#btnInventario").prop('disabled', false)
                        .html('<i class="mdi mdi-content-save"></i>Guardar');
                },
                error: function (xhr, status, error) {          
                    Swal.fire("Error", '<p>' + error + '</p>'); 
                    $('#btnInventario').prop('disabled', false)
                        .html('<i class="mdi mdi-content-save"></i>Guardar');
                }
            });
        },
        getInventario: function(id_inventario)
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Agregar/getInventario",
                data: {id_inventario},
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $("#editar").val(1);
                    $("#activo_fijo").val(response.activo_fijo);
                    $("#denominacion_activo_fijo").val(response.denominacion_activo_fijo);
                    $("#color").val(response.color);
                    $("#fabricante").val(response.fabricante);
                    $("#estado").val(response.estado);
                    $("#id_inventario").val(id_inventario);
                    $("#marca").val(response.marca);
                    $("#material").val(response.material);
                    $("#modelo").val(response.modelo);
                    $("#usuario").val(response.no_empleado);
                    $("#no_serie").val(response.no_serie);
                    $("#observaciones").val(response.observaciones);
                    $("#prefijo_activo_fijo").val(response.prefijo_activo_fijo);
                    $("#ubicacion").val(response.ubicacion);
                    $("#valor").val(response.valor);
                    const fechaCompleta = response.fec_cap; // Ejemplo de fecha
                    const fechaFormateada = fechaCompleta.split('T')[0]; // Extrae "1983-10-10"
                    $('#fec_cap').val(fechaFormateada); // Asigna la fecha al campo
                   
                },
                complete: function(){
                    $("#modelInventarios").modal('show');

                },
                error: function (response,jqXHR, textStatus, errorThrown) {          
                    Swal.fire("Error", '<p> '+ res.message + '</p>'); 
                   
                }
            });
        },
        deleteInventario: function(id_inventario)
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Agregar/deleteInventario",
                data: {id_inventario},
                dataType: "json",
                success: function (response) {
                    if(!response.error){
                        Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                        setTimeout(() => {
                           // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                            window.location.reload();
                        }, 1500);
                    }else{
                        Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                    }
                },
                error: function (response,jqXHR, textStatus, errorThrown) {          
                    Swal.fire("Error", '<p> '+ res.message + '</p>'); 
                    $('#btnAlba').prop('disabled', false).html('Guardar');
                }
            });

        },
        descargaDirectorio: function() {
            // Crear un formulario temporal para la descarga
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = base_url + 'index.php/Usuario/descargaDirectorio';
            form.target = '_blank'; // Abrir en nueva pestaña para descarga
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            // Opcional: mostrar mensaje de que la descarga comenzó
            Swal.fire({
                icon: 'info',
                title: 'Descargando',
                text: 'La descarga del directorio comenzará en breve',
                timer: 2000,
                showConfirmButton: false
            });
        },
        deleteIn: function(id_inventario)
        {
            Swal.fire({
                title: "¿Está seguro?",
                text: "¿Desea eliminar el registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Eliminar",
            }).then((result) => {
                if (result.isConfirmed) {
                   ini.inicio.deleteInventario(id_inventario);
                }
            });
            
        },
        deleteDe: function(id_denuncia)
        {
            Swal.fire({
                title: "¿Está seguro?",
                text: "¿Desea eliminar el registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Eliminar",
            }).then((result) => {
                if (result.isConfirmed) {
                   ini.inicio.deleteDenuncia(id_denuncia);
                }
            });
            
        },
        altaAlba: function(){
        $("#formAgregarAlba").submit(function (e) {
            e.preventDefault(); 
            var formData = new FormData(this); // Usar FormData en lugar de serialize
            var foto = $('#foto')[0].files[0];
            var protocolo = $('#protocolo')[0].files[0];
            let id_alba = $('#id_alba').val();
            if(id_alba == 0){
                if(!foto) {
                    Swal.fire("Atención", 'La <strong>Foto</strong> es requerida', 'info'); 
                    return;
                }
                if(!protocolo) {
                     Swal.fire("Atención", 'La <strong>protocolo</strong> es requerida', 'info'); 
                    return;
                }

            }    

                $.ajax({
                type: "POST",
                url: base_url + "index.php/Agregar/albaAlta",
                data: formData,
                processData: false,  // Importante para FormData
                contentType: false,  // Importante para FormData
                dataType: "json",
                success: function (response) {
                    if(!response.error){
                        Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                        setTimeout(() => {
                           // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                            window.location.reload();
                        }, 1500);
                    }else{
                        Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                    }
                },
                beforeSend: function (info){
                     $('#btnAlba').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                },
                complete: function (info){
                    $('#btnAlba').prop('disabled', false).html('Guardar');
                },
                error: function (response,jqXHR, textStatus, errorThrown) {
                   
                    Swal.fire("Error", '<p> '+ res.message + '</p>'); 
                    $('#btnAlba').prop('disabled', false).html('Guardar');
                }
            });
          })
        },
        busquedaProveedorTI: function() {
            const palabra = $("#buscar_proveedor_ti").val().trim(); // Elimina espacios al inicio/final
            
            // Solo busca si hay 3+ caracteres o el campo está vacío (para resetear)
            if (palabra.length >= 3 || palabra.length === 0) {
                if ($.fn.DataTable.isDataTable('#datatableProveedor')) {
                    $('#datatableProveedor').DataTable().destroy();
                }

                $.ajax({
                    url: base_url + "index.php/Principal/buscarProveedor",
                    method: 'POST',
                    data: { 
                        termino: palabra.replace(/\s+/g, ' ') // Elimina espacios múltiples
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.error && response.data) {
                            const tbody = $('#datatableProveedor tbody');
                            tbody.empty();
                            
                              /*<a href="<?php echo base_url(); ?>index.php/Principal/PagoFic/${p.id_proveedor}"  data-toggle="tooltip" data-placement="left" data-original-title="Pagos FIC"
                                                class="btn btn-gradient-dark px-4">FIC</i>
                                            </a>*/
                            response.data.forEach(p => {
                                tbody.append(`
                                    <tr>
                                        <td class="text-center">${p.id_proveedor}</td>
                                        <td class="text-center">${p.razon_social}</td>
                                        <td class="text-center">${p.rfc}</td>
                                        <td class="text-center">
                                           ${p.no_proveedor}
                                        </td>
                                        <td class="text-center">
                                           <a style="color:white" onclick="ini.inicio.editarProveedor(${p.id_proveedor});" title="Editar"
                                                class="btn btn-gradient-success px-4"><i
                                                    class="mdi mdi-border-color font-21"></i>
                                            </a>
                                            <a style="color:white" onclick="ini.inicio.eliminarProveedor(${p.id_proveedor});" title="Eliminar"
                                                 class="btn btn-gradient-danger px-4"><i
                                                    class="mdi mdi-trash-can-outline font-21"></i>
                                            </a>
                                          

                                        </td>
                                    </tr>
                                `);
                            });

                            // Re-inicializa DataTable
                            $('#datatableProveedor').DataTable({
                                   language: {
                                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
                                    },
                                    destroy: true,
                                    searching: true,
                            });
                        }
                    },
                    beforeSend: function() {
                    $("#icono_spinner").show();
                    $("#icono_buscar").hide();
                    },
                    complete: function() {
                    $("#icono_spinner").hide();
                    $("#icono_buscar").show();
                    },
                    error: function() {
                        console.error("Error en la búsqueda");
                    }
                });
            }
        }, 
        busquedaProveedor: function() {
            const palabra = $("#buscar_proveedor").val().trim(); // Elimina espacios al inicio/final
            
            // Solo busca si hay 3+ caracteres o el campo está vacío (para resetear)
            if (palabra.length >= 3 || palabra.length === 0) {
                if ($.fn.DataTable.isDataTable('#datatableProveedores')) {
                    $('#datatableProveedores').DataTable().destroy();
                }

                $.ajax({
                    url: base_url + "index.php/Principal/buscarProveedor",
                    method: 'POST',
                    data: { 
                        termino: palabra.replace(/\s+/g, ' ') // Elimina espacios múltiples
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.error && response.data) {
                            const tbody = $('#datatableProveedores tbody');
                            tbody.empty();
                            
                            response.data.forEach(p => {
                                let btn = `
                                 <a style="color:white;" onclick="ini.inicio.reserva(${p.id_proveedor});" 
                                            class="btn btn-gradient-success px-4">
                                            <i class="mdi mdi-arrow-collapse-right font-21"></i>
                                 </a>
                                `;
                                if(p.fic == 1){
                                    btn +=  `<a href="${base_url}index.php/Principal/PagoFic/${p.id_proveedor}"  data-toggle="tooltip" data-placement="left" data-original-title="Pagos FIC"
                                                    class="btn btn-gradient-dark px-4">FIC</i>
                                            </a>`;
                                }
                                tbody.append(`
                                    <tr>
                                        <td class="text-center">${p.id_proveedor}</td>
                                        <td class="text-center">${p.razon_social}</td>
                                        <td class="text-center">${p.rfc}</td>
                                        <td class="text-center">${p.no_proveedor}</td>
                                     
                                        <td class="text-center">
                                            ${btn}
                                        </td>
                                    </tr>
                                `);
                            });

                            // Re-inicializa DataTable
                            $('#datatableCategorias,#datatableProveedores').DataTable({
                                   language: {
                                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
                                    },
                                    destroy: true,
                                    searching: true,
                            });
                        }
                    },
                     beforeSend: function() {
                    $("#icono_spinner").show();
                    $("#icono_buscar").hide();
                    },
                    complete: function() {
                    $("#icono_spinner").hide();
                    $("#icono_buscar").show();
                    },
                    error: function() {
                        console.error("Error en la búsqueda");
                    }
                });
            }
        }, 
        ocultarInstrumento: function(checkbox)
        {
            console.log(checkbox.checked)
            if (checkbox.checked) {
                $("#id_instrumento").hide();
                $("#id_convenio").hide();
            } else {
                $("#id_instrumento").show();
                $("#id_convenio").show();
            }
        },
        // Función global para validar
        formIncidencia: function(){
            $("#editarAsistencia").submit(function (e) {
             e.preventDefault(); 
                var formData = new FormData(this); // Usar FormData en lugar de serialize
                console.log(formData);
                    $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/formIncidencia",
                    data: formData,
                    processData: false,  // Importante para FormData
                    contentType: false,  // Importante para FormData
                    dataType: "json",
                    success: function (response) {
                        if(!response.error){
                            Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                            setTimeout(() => {
                               // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                                window.location.reload();
                            }, 1500);
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    beforeSend: function (info){
                         $('#btn_asistencia').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (info){
                        $('#btn_asistencia').prop('disabled', false).html('Guardar');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>'); 
                        $('#btn_asistencia').prop('disabled', false).html('Guardar'); 
                    }
                });
          })
        },
        activarPeriodo: function(id_periodo, id)
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/activarPeriodo",
                dataType: "json",
                data:{id_periodo, id},
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire("Éxito", "Se guardo correctamente.", "success")
                       
                    } else {
                        Swal.fire("Error", "Error al guardar comentario.", "error");
                    }
                  //  ini.inicio.obtenerCategorias(); 
                  ini.inicio.getPeriodos(); 
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });
        },
        getSelectPeriodos: function(){
            $.ajax({
                type: "GET",
                url: base_url + "index.php/Usuario/getSelectPeriodos",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#periodos').empty();
                    $.each(data.periodo, function(index, p) {
                        $('#periodos').append(
                            $('<option>', {
                                value: p.id_periodo_sac,
                                text: p.dia_inicio + ' AL ' + p.dia_fin + ' DE ' + ini.inicio.obtenerNombreMes(p.mes) + ' P' + p.periodo
                            })
                        );
                    });
                    $('#periodos').trigger('change.select2');
                    $('#categoria').empty();
                    $.each(data.categoria, function(index, p) {
                        $('#categoria').append(
                            $('<option>', {
                                value: p.id_categoria_sac,
                                text: p.dsc_categoria_sac
                            })
                        );
                    });
                    $('#categoria').trigger('change.select2');
                },
                error: function() {
                    Swal.fire("Error", "Error al guardar comentario.", "error")
                }
            });
        },
            showConfirmation: function(opcion) {
            const chat = document.getElementById('chat');
            const randomTicket = Math.floor(1000 + Math.random() * 9000);
                $.ajax({
                type: "POST",
                url: `${base_url}index.php/Usuario/guardarTiket`,
                dataType: "json",
                data: { opcion, randomTicket },
                success: function(response) {
                 console.log(response);
                 Swal.fire({
                    title: "¡Ticket creado con éxito!",
                    text:  `En seguida lo atendemos`,
                    icon: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                    }).then((result) => {
                    if (result.isConfirmed) {
                            /*===============*/
                            $.ajax({
                                type: "POST",
                                url: `${base_url}index.php/EmailController/sendEmail`,
                                dataType: "json",
                                data: { opcion, randomTicket },
                                success: function(response) {
                                    console.log('entro')
                                    console.log(response);
                                    if(!response.error){
                                    Swal.fire({
                                        title: `#${randomTicket}`,
                                        text: "Número de tiket",
                                        icon: "success"
                                        });
                                        setTimeout(() => {
                                            //window.location.reload();
                                                window.location.href = base_url + "index.php/Inicio/ListaTiket";
                                        }, 1500);
                                    }else{
                                        Swal.fire({
                                        title: `#${randomTicket}`,
                                        text: response.respuesta,
                                        icon: "error"
                                        });
                                    }
                                   
                            
                                    },
                                    error: function() {
                                        Swal.fire("Error", "Error al cargar los detalles del curso.", "error");
                                    }
                                });

                        
                        }
                    });
             
                    },
                    error: function() {
                        Swal.fire("Error", "Error al cargar los detalles del curso.", "error");
                    }
                });
          

        },
        verDetalle: function(id) {
            $.ajax({
                type: "POST",
                url: `${base_url}index.php/Usuario/verDetalle`,
                dataType: "json",
                data: { id_curso: id },
                success: function(response) {
                    const datos = response.data;
                    console.log(datos);
                    const generarLista = (items, textoVacio, callback) => {
                        if (!items || items.length === 0) {
                            return `<li class="list-group-item">${textoVacio}</li>`;
                        }
                        return items.map(item => `<li class="list-group-item">${callback(item)}</li>`).join('');
                    };
                    const htmlCategorias = generarLista(datos.categoria, "No hay categorías disponibles", 
                        cat => `<strong>${cat.dsc_categoria_sac}</strong>`
                    );
        
                    // Generar HTML para períodos
                    const htmlPeriodos = generarLista(datos.periodo, "No hay períodos disponibles", 
                        per => `<strong>${per.dia_inicio} DE ${ini.inicio.obtenerNombreMes(per.mes)} AL ${per.dia_fin} PERIODO ${per.periodo}</strong>`
                    );
        
                    // Generar HTML para detalles del curso
                    const htmlCurso = datos.curso && datos.curso.length > 0 
                        ? `
                            <strong>Autogestivo:</strong> ${datos.curso[0].autogestivo} <br>
                            <strong>Curso de línea:</strong> ${datos.curso[0].curso_linea} <br>
                            <strong>Duración:</strong> ${datos.curso[0].duracion} <br>
                            <strong>Dirigido:</strong> ${datos.curso[0].dirigido} <br>
                            <strong>Horas:</strong> ${datos.curso[0].horas} <br>
                        `
                        : `<span class="list-group-item">No hay cursos disponibles</span>`;
        
                    // Agregar el contenido al modal
                    $('#detalleCurso').html(`<ul>${htmlCategorias}</ul>`);
                    $('#detallePeriodo').html(`<ul>${htmlPeriodos}</ul>`);
                    $('#detalles').html(`<div>${htmlCurso}</div>`);
        
                    // Mostrar el modal
                    $('#verDetalleCurso').modal('show');
                },
                error: function() {
                    Swal.fire("Error", "Error al cargar los detalles del curso.", "error");
                }
            });
        },      
        getCursos: function()
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getCursos",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    let html = ''; // Variable para almacenar el HTML de todas las filas
        
                    // Verifica si data es un array y itera sobre él
                    if (Array.isArray(data)) {
                        data.forEach(function(e) {
                            let IdMoodle = '';
                            let boton = '';
                            let icon = '';
                            if(e.id_moodle != null){
                                IdMoodle = e.id_moodle ;
                            }  
                            if (e.activo == 1) {
                                icon += `<i class="mdi mdi-eye text-success font-18"></i>`;
                            } else if (e.activo == 0) {
                                icon += `<i class="mdi mdi-eye-off text-danger font-18"></i>`;
                            }                   
                            // Define los botones según el valor de "visible"
                            boton += `
                               <button title="Ver detalle"
                                   onclick="ini.inicio.verDetalle(${e.id_cursos_sac})"
                                   class="btn btn-gradient-info px-4"><i
                                       class="mdi mdi-file-document-box font-21"></i>
                               </button>
                              <button title="editar"
                                  onclick="ini.inicio.editarCursoSac(${e.id_cursos_sac})"
                                  class="btn btn-gradient-warning px-4"><i
                                      class="dripicons-pencil font-21"></i>
                              </button>`;
                            if (e.activo == 1) {
                                boton += `<button title="Desactivar"
                                               onclick="ini.inicio.activarCursoSac(${e.id_cursos_sac},3)"
                                               class="btn btn-gradient-success px-4 "><i
                                                   class="mdi mdi-eye font-21"></i>
                                           </button>`;                            
                            } 
                            if (e.activo == 0) {
                                boton += `<button title="Desactivar"
                                               onclick="ini.inicio.activarCursoSac(${e.id_cursos_sac},4)"
                                               class="btn btn-gradient-success px-4 "><i
                                                   class="mdi mdi-eye font-21"></i>
                                           </button>`;
                            }
                            boton += `<button title="eliminar"
                                          onclick="ini.inicio.eliminarCursoSac(<?= $e->id_cursos_sac?>)"
                                          class="btn btn-gradient-danger px-4 "><i
                                              class="dripicons-trash font-21"></i>
                                      </button>`;
        
                            // Construye la fila
                            html += `
                                <tr>
                                    <td class="text-center">P${e.id_cursos_sac}</td>
                                    <td class="text-center">${IdMoodle}</td>
                                    <td class="text-center">${e.dsc_curso}</td>
                                    <td class="text-center">${icon}</td>
                                    <td class="text-center">${boton}</td>
                                </tr>`;
                        });
                    } else {
                        console.error("Error: Los datos no son un array.");
                    }
        
                    // Reemplaza el contenido del tbody con el nuevo HTML
                    $('#datatableCursos tbody').html(html);
                },
                error: function() {
                    Swal.fire("Error", "Error al obtener las categorías.", "error");
                }
            });

        },
      
        getPeriodos: function()
        {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getPeriodos",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    let html = ''; // Variable para almacenar el HTML de todas las filas
        
                    // Verifica si data es un array y itera sobre él
                    if (Array.isArray(data)) {
                        data.forEach(function(e) {
                            let icon = '';
                            let boton = '';
                            // Define el ícono según el valor de "visible"
                            if (e.activo == 1) {
                                icon += `<i class="mdi mdi-eye text-success font-18"></i>`;
                            } else if (e.activo == 0) {
                                icon += `<i class="mdi mdi-eye-off text-danger font-18"></i>`;
                            }
                            // Define los botones según el valor de "visible"
                            boton += `
                            <button title="editar" onclick="ini.inicio.editarPeriodo(${e.id_periodo_sac})" class="btn btn-gradient-warning px-4">
                                <i class="dripicons-pencil font-21"></i>
                            </button>`;
                            if (e.activo == 1) {
                                boton += `<button title="Desactivar"
                                              onclick="ini.inicio.activarPeriodo(${e.id_periodo_sac}, 1)"
                                              class="btn btn-gradient-success px-4 "><i
                                                  class="mdi mdi-eye font-21"></i>
                                         </button>`;                            
                            } 
                            if (e.activo == 0) {
                                boton += `<button title="Activar" onclick="ini.inicio.activarPeriodo(${e.id_periodo_sac},2)" class="btn btn-gradient-danger px-4">
                                        <i class="mdi mdi-eye-off font-21"></i>
                                    </button>`;
                            }
                            boton += ` <button title="eliminar" onclick="ini.inicio.eliminarPeriodo(${e.id_periodo_sac})" class="btn btn-gradient-danger px-4">
                                        <i class="dripicons-trash font-21"></i>
                                    </button>`;
        
                            // Construye la fila
                            html += `
                                <tr>
                                    <td class="text-center">P${e.periodo}</td>
                                    <td class="text-center">${ini.inicio.obtenerNombreMes(e.mes)}</td>
                                    <td class="text-center">${e.dia_inicio}</td>
                                    <td class="text-center">${e.dia_fin}</td>
                                    <td class="text-center">${icon}</td>
                                    <td class="text-center">${boton}</td>
                                </tr>`;
                        });
                    } else {
                        console.error("Error: Los datos no son un array.");
                    }
        
                    // Reemplaza el contenido del tbody con el nuevo HTML
                    $('#datatablePeriodos tbody').html(html);
                },
                error: function() {
                    Swal.fire("Error", "Error al obtener las categorías.", "error");
                }
            });
        },
        agregarCategoria: function()
        {
            $('#modalAgregarCategoria').modal('show');
            Swal.fire({
                title: "<strong>NOMBRE DEL curs</strong>",
                icon: "info",
                html: `<textarea id="comentarioInput" class="form-control" placeholder="Escriba la Categoria"></textarea>`,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    const comentario = document.getElementById("comentarioInput").value.trim();       
                    if (comentario === "") {
                        Swal.fire("Error", "El campo no puede estar vacío.", "error");
                        return;
                    }
                    const data = {comentario };
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Usuario/guardarCategoria",
                        dataType: "json",
                        data:data,
                        success: function(data) {
                            console.log(data);
                            if (data) {
                                Swal.fire("Éxito", "Se guardo correctamente.", "success")
                               
                            } else {
                                Swal.fire("Error", "Error al guardar comentario.", "error");
                            }
                            ini.inicio.obtenerCategorias(); 
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });
                }
            });
        },
        agregarArea: function()
        {
            Swal.fire({
                title: "<strong>NOMBRE DEL ÁREA</strong>",
                icon: "info",
                html: `<textarea id="comentarioInput" class="form-control" placeholder="Escriba el Área"></textarea>`,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    const comentario = document.getElementById("comentarioInput").value.trim();       
                    if (comentario === "") {
                        Swal.fire("Error", "El campo no puede estar vacío.", "error");
                        return;
                    }
                    const data = {comentario, editar:0 };
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Usuario/guardarArea",
                        dataType: "json",
                        data:data,
                        success: function(data) {
                            console.log(data);
                            if (!data.error) {
                                Swal.fire("Éxito", "Se guardo correctamente.", "success")
                               
                            } else {
                                Swal.fire("Error", "Error al guardar comentario.", "error");
                            }
                     
                        },
                        complete: function(){
                         window.location.reload();
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });
                }
            });
        },
        agregarPerfil: function()
        {
            Swal.fire({
                title: "<strong>NOMBRE DEL PERFIL</strong>",
                icon: "info",
                html: `<textarea id="comentarioInput" class="form-control" placeholder="Escriba el Perfil"></textarea>`,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    const comentario = document.getElementById("comentarioInput").value.trim();       
                    if (comentario === "") {
                        Swal.fire("Error", "El campo no puede estar vacío.", "error");
                        return;
                    }
                    const data = {comentario, editar:0 };
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Usuario/guardarPerfil",
                        dataType: "json",
                        data:data,
                        success: function(data) {
                            console.log(data);
                            if (data) {
                                Swal.fire("Éxito", "Se guardo correctamente.", "success")
                               
                            } else {
                                Swal.fire("Error", "Error al guardar comentario.", "error");
                            }
                     
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });
                }
            });
        },
        abrirModalFoto: function(){
        $("#modalFoto").modal('show');
        },
        cerrarModalFoto: function(){
        $("#modalFoto").modal('hide');
        },
       
        agregarPuesto: function()
        {
            Swal.fire({
                title: "<strong>NOMBRE DEL PUESTO</strong>",
                icon: "info",
                html: `<textarea id="comentarioInput" class="form-control" placeholder="Escriba el Puesto"></textarea>`,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    const comentario = document.getElementById("comentarioInput").value.trim();       
                    if (comentario === "") {
                        Swal.fire("Error", "El campo no puede estar vacío.", "error");
                        return;
                    }
                    const data = {comentario, editar:0 };
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Usuario/guardarPuesto",
                        dataType: "json",
                        data:data,
                        success: function(data) {
                            console.log(data);
                            if (data) {
                                Swal.fire("Éxito", "Se guardo correctamente.", "success")
                               
                            } else {
                                Swal.fire("Error", "Error al guardar comentario.", "error");
                            }
                     
                        },
                        error: function() {
                            Swal.fire("Error", "Error al guardar comentario.", "error")
                        }
                    });
                }
            });
        },
       formViatico: function(){
         $("#form_viatico").submit(function (e) {
             e.preventDefault(); 
                var formData = new FormData(this); // Usar FormData en lugar de serialize
                console.log(formData);
                    $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/formViatico",
                    data: formData,
                    processData: false,  // Importante para FormData
                    contentType: false,  // Importante para FormData
                    dataType: "json",
                    success: function (response) {
                        if(!response.error){
                            Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                            setTimeout(() => {
                               // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                                window.location.href = base_url + "index.php/Inicio/ListaViaticos";
                            }, 1500);
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    beforeSend: function (info){
                         $('#btnGuardarViatico').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (info){
                        $('#btnGuardarViatico').prop('disabled', false).html('Guardar');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                    }
                });
          })
       },
      formPT: function(){
        $("#form_proveedor").submit(function (e) {
                e.preventDefault(); 
                
                // Remover completamente el atributo disabled en lugar de solo cambiar la propiedad
                $('select[name="partida[]"]').removeAttr('disabled');
                $('select[name="proyecto[]"]').removeAttr('disabled');

                // 1. Deshabilitar campos de partidas marcadas ANTES de validar y enviar
                $('.toggle-factura-section:checked').each(function() {
                    let i = $(this).attr('id').replace('checkbox_', '');
                    $('#encabezado_' + i).prop('disabled', true);
                    $('input[name="importe[]"]').eq(i).prop('disabled', true);
                    $('#factura_pdf_input_' + i).prop('disabled', true);
                    $('#factura_xml_input_' + i).prop('disabled', true);
                    $('#partida_' + i).prop('disabled', true);
                    $('#proyecto_' + i).prop('disabled', true);
                });
            
                let valido = true;
                let mensajes = [];
                let editar = $("#editar").val();
                
                // Validar solo campos NO deshabilitados
                $("[id^=encabezado_]:not(:disabled)").each(function(){
                    if($(this).val().trim() === ""){
                        valido = false;
                        let index = $(this).attr('id').replace('encabezado_', '');
                        mensajes.push("El campo Encabezado en la partida " + (parseInt(index) + 1) + " es obligatorio.");
                    }
                });

                // Validar archivos PDF solo en campos NO deshabilitados
                if(editar != 1){
                    $("[id^=factura_pdf_input_]:not(:disabled)").each(function(){
                        let files = this.files;
                        if(files.length === 0){
                            valido = false;
                            let index = $(this).attr('id').replace('factura_pdf_input_', '');
                            mensajes.push("Debe subir un archivo PDF en la partida " + (parseInt(index) + 1) + ".");
                        }
                    });
                }

                // Validar archivos XML solo en campos NO deshabilitados
                if(editar != 1){
                    $("[id^=factura_xml_input_]:not(:disabled)").each(function(){
                        let files = this.files;
                        if(files.length === 0){
                            valido = false;
                            let index = $(this).attr('id').replace('factura_xml_input_', '');
                            mensajes.push("Debe subir un archivo XML en la partida " + (parseInt(index) + 1) + ".");
                        }
                    });
                }
            
                if(!valido){
                    Swal.fire("Atención", "<p>"+mensajes.join("<br>")+"</p>", "warning");
                    // Re-habilitar todos los campos antes de retornar
                    $('select[name="partida[]"]').prop('disabled', true);
                    $('select[name="proyecto[]"]').prop('disabled', true);
                    $('[id^=encabezado_]').prop('disabled', false);
                    $('input[name="importe[]"]').prop('disabled', false);
                    $('[id^=factura_pdf_input_]').prop('disabled', false);
                    $('[id^=factura_xml_input_]').prop('disabled', false);
                    return;
                }

                var formData = new FormData(this);
                
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaPT",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(!response.error){
                            Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                            setTimeout(() => {
                                window.location.href = base_url + "index.php/Principal/tablaArchivos/"+response.idRegistro+'/PT';
                            }, 1500);
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    beforeSend: function (info){
                        $('#btnGuardatPT').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (info){
                        $('#btnGuardatPT').prop('disabled', false).html('Guardar');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                        // Re-habilitar todos los campos en caso de error
                        $('select[name="partida[]"]').prop('disabled', true);
                        $('select[name="proyecto[]"]').prop('disabled', true);
                        $('[id^=encabezado_]').prop('disabled', false);
                        $('input[name="importe[]"]').prop('disabled', false);
                        $('[id^=factura_pdf_input_]').prop('disabled', false);
                        $('[id^=factura_xml_input_]').prop('disabled', false);
                    }
                });
            });
        },
        nextFormPT: function(){
            $("#form_next_pt").submit(function (e) {
                e.preventDefault(); 
                $('select[name="partida[]"]').prop('disabled', false);
                $('select[name="proyecto[]"]').prop('disabled', false);

                
                // 1. Deshabilitar campos de partidas marcadas ANTES de validar y enviar
                $('.toggle-factura-section:checked').each(function() {
                    let i = $(this).attr('id').replace('checkbox_', '');
                    $('#encabezado_' + i).prop('disabled', true);
                    $('input[name="importe[]"]').eq(i).prop('disabled', true);
                    $('#factura_pdf_input_' + i).prop('disabled', true);
                    $('#factura_xml_input_' + i).prop('disabled', true);
                     $('#partida_' + i).prop('disabled', true);
                     $('#proyecto_' + i).prop('disabled', true);
                });

                // 2. Validación normal (solo campos habilitados)
                let valido = true;
                let mensajes = [];
                
                $("[id^=encabezado_]:not(:disabled)").each(function(index){
                    if($(this).val().trim() === ""){
                        valido = false;
                        mensajes.push("El campo Encabezado es obligatorio en la partida " + (index + 1));
                    }
                });

                $("[id^=factura_pdf_input_]:not(:disabled)").each(function(index){
                    if(this.files.length === 0){
                        valido = false;
                        mensajes.push("Debe subir al menos un archivo PDF en la partida " + (index + 1));
                    }
                });

                $("[id^=factura_xml_input_]:not(:disabled)").each(function(index){
                    if(this.files.length === 0){
                        valido = false;
                        mensajes.push("Debe subir al menos un archivo XML en la partida " + (index + 1));
                    }
                });

               if(!valido){
                    $('input, select, textarea').prop('disabled', false);
                    // Volver a dejar los selects de partida como disabled
                    $('select[name="partida[]"]').prop('disabled', true);
                    Swal.fire("Atención", "<p>"+mensajes.join("<br>")+"</p>", "warning");
                    return;
                }

                // 3. Enviar formulario
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaPT2",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(!response.error){
                            Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                            setTimeout(() => {
                                window.location.href = base_url + "index.php/Principal/tablaArchivos/"+response.idRegistro+'/PT';
                            }, 1500);
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    beforeSend: function (info){
                        $('#btnGuardatPT').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (info){
                        $('#btnGuardatPT').prop('disabled', false).html('Guardar');
                        // Rehabilitar todos los campos después del envío
                        $('input, select, textarea').prop('disabled', false);
                         $('select[name="partida[]"]').prop('disabled', true);
                         $('select[name="proyecto[]"]').prop('disabled', true);
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                        // Rehabilitar todos los campos en caso de error
                        $('input, select, textarea').prop('disabled', false);
                    }
                });
            });
        },
        formatBytes :function(bytes) {
        const units = ['B','KB','MB','GB'];
        let i = 0, num = bytes;
        while (num >= 1024 && i < units.length - 1) { num /= 1024; i++; }
        return `${num.toFixed(1)} ${units[i]}`;
        },
        formEditarFIC: function(){
            $("#form_fic_editar").submit(function (e) {
                e.preventDefault(); 
                   let valido = true;
                    let mensajes = [];
                    const MAX_BYTES = 100 * 1024 * 1024; 

                // Validar solo si se seleccionó algún archivo
                $("[id^=factura_pdf_fic]").each(function(){
                    let files = this.files;
                    if(files.length > 0){ // Solo validar si hay archivos
                        for (const f of files) {
                            if (f.size > MAX_BYTES) {
                                valido = false;
                                mensajes.push(`"${f.name}" pesa ${ini.inicio.formatBytes(f.size)}; el límite es 500 KB por archivo.`);
                            }
                        }
                    }

                });

                $("[id^=factura_xml_fic]").each(function(){
                    let files = this.files;
                    if(files.length > 0){ // Solo validar si hay archivos
                        // Aquí puedes agregar validaciones específicas para XML si necesitas
                        for (const f of files) {
                            if (f.size > MAX_BYTES) {
                                valido = false;
                                mensajes.push(`"${f.name}" pesa ${ini.inicio.formatBytes(f.size)}; el límite es 500 KB por archivo.`);
                            }
                        }
                    }
                });

                if(!valido){
                    Swal.fire("Atención", "<p>"+mensajes.join("<br>")+"</p>", "warning");
                    return;
                }

                var formData = new FormData(this); // Usar FormData en lugar de serialize

               
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaEditarFIC",
                    data: formData,
                    processData: false,  // Importante para FormData
                    contentType: false,  // Importante para FormData
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(!response.error){
                            Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                            setTimeout(() => {
                               // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                                window.location.href = base_url + "index.php/Principal/tablaArchivos/"+response.idReserva+'/FIC';
                            }, 1500);
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    beforeSend: function (info){
                         $('#btnGuardaFIC').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (info){
                        $('#btnGuardaFIC').prop('disabled', false).html('Guardar');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                         $('#btnGuardaFIC').prop('disabled', false).html('Guardar');
                    }
                });
            });
        },
        formFIC: function(){
            $("#form_fic").submit(function (e) {
                e.preventDefault(); 
                   let valido = true;
                    let mensajes = [];
                    const MAX_BYTES = 100 * 1024 * 1024; 
                    // Validar archivos PDF
                    $("[id^=factura_pdf_fic]").each(function(){
                        let files = this.files;
                        if(files.length === 0){
                            valido = false;
                            mensajes.push("Debe subir al menos un archivo PDF.");
                        }
                         for (const f of files) {
                            if (f.size > MAX_BYTES) {
                                valido = false;
                                mensajes.push(`"${f.name}" pesa ${ini.inicio.formatBytes(f.size)}; el límite es 500 KB por archivo.`);
                            }
                        }
                    });

                    $("[id^=factura_xml_fic]").each(function(){
                        let files = this.files;
                        if(files.length === 0){
                            valido = false;
                            mensajes.push("Debe subir al menos un archivo XML.");
                        }
                    });

                    if(!valido){
                        Swal.fire("Atención", "<p>"+mensajes.join("<br>")+"</p>", "warning");
                        return;
                    }

                var formData = new FormData(this); // Usar FormData en lugar de serialize

               
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaFIC",
                    data: formData,
                    processData: false,  // Importante para FormData
                    contentType: false,  // Importante para FormData
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(!response.error){
                            Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                            setTimeout(() => {
                               // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                                window.location.href = base_url + "index.php/Principal/tablaArchivos/"+response.idReserva+'/FIC';
                            }, 1500);
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    beforeSend: function (info){
                         $('#btnGuardaFIC').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (info){
                        $('#btnGuardaFIC').prop('disabled', false).html('Guardar');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                         $('#btnGuardaFIC').prop('disabled', false).html('Guardar');
                    }
                });
            });
        },
       formGo: function(){
            $("#form_go").submit(function (e) {
                e.preventDefault(); 

                
                var formData = new FormData(this); // Usar FormData en lugar de serialize
                     let valido = true;
                    let mensajes = [];


                    // Validar archivos PDF
                    $("[id^=factura_pdf_input_go_]").each(function(){
                        let files = this.files;
                        if(files.length === 0){
                            valido = false;
                            mensajes.push("Debe subir al menos un archivo PDF.");
                        }
                    });

                    

                    if(!valido){
                        Swal.fire("Atención", "<p>"+mensajes.join("<br>")+"</p>", "warning");
                        return;
                    }
               
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaGO",
                    data: formData,
                    processData: false,  // Importante para FormData
                    contentType: false,  // Importante para FormData
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(!response.error){
                            Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                            setTimeout(() => {
                               // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                                window.location.href = base_url + "index.php/Principal/tablaArchivos/"+response.idRegistro+'/GO';
                            }, 1500);
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    beforeSend: function (info){
                         $('#btnGuardaGo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (info){
                        $('#btnGuardaGo').prop('disabled', false).html('Guardar');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                    }
                });
            });
        },
        generarZipV: function(id_registro_pt) {
            var formData = new FormData();
            formData.append('id_registro_pt', id_registro_pt);
            
            // Agregar archivos si existen
            var archivo05 = $('#archivo05')[0].files[0];
            if (archivo05) formData.append('archivo05', archivo05);
            
            var archivo06 = $('#archivo06')[0].files[0];
            if (archivo06) formData.append('archivo06', archivo06);
            
            var archivo08 = $('#archivo08')[0].files[0];
            if (archivo08) formData.append('archivo08', archivo08);
            

            $.ajax({
                type: "POST",
                url: base_url + "index.php/Principal/generarZipV",
                data: formData,
                processData: false,
                contentType: false,
                xhrFields: {
                    responseType: 'blob' // Para manejar la respuesta como archivo
                },
                beforeSend: function() {
                    $('#btnZip').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                },
                  success: function(response, status, xhr) {
                    var filename = "documentos_" + id_registro_pt + ".zip";

                    // Crear Blob y enlace de descarga
                    var blob = new Blob([response], { type: 'application/zip' });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.fire("Éxito", "ZIP generado y descargado correctamente", "success");
                },
                complete: function() {
                    $('#btnZip').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Generar Zip');
                  $('#archivo05').val('');
                  $('#archivo06').val('');
                  $('#archivo08').val('');
                  $('#archivo09').val('');
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseText || "Error al generar el archivo ZIP";
                    Swal.fire("Error", errorMessage, "error");
                }
            });
        },
        generarZip: function(id_registro_pt) {
            var formData = new FormData();
            formData.append('id_registro_pt', id_registro_pt);
            
            // Agregar archivos si existen
            var archivo05 = $('#archivo05')[0].files[0];
            if (archivo05) formData.append('archivo05', archivo05);
            
            var archivo06 = $('#archivo06')[0].files[0];
            if (archivo06) formData.append('archivo06', archivo06);
            
            var archivo08 = $('#archivo08')[0].files[0];
            if (archivo08) formData.append('archivo08', archivo08);
            
            var archivo09 = $('#archivo09')[0].files[0];
            if (archivo09) formData.append('archivo09', archivo09);
            
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Principal/generarZip",
                data: formData,
                processData: false,
                contentType: false,
                xhrFields: {
                    responseType: 'blob' // Para manejar la respuesta como archivo
                },
                beforeSend: function() {
                    $('#btnZip').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                },
                  success: function(response, status, xhr) {
                    var filename = "documentos_" + id_registro_pt + ".zip";

                    // Crear Blob y enlace de descarga
                    var blob = new Blob([response], { type: 'application/zip' });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.fire("Éxito", "ZIP generado y descargado correctamente", "success");
                },
                complete: function() {
                    $('#btnZip').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Generar Zip');
                  $('#archivo05').val('');
                  $('#archivo06').val('');
                  $('#archivo08').val('');
                  $('#archivo09').val('');
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseText || "Error al generar el archivo ZIP";
                    Swal.fire("Error", errorMessage, "error");
                }
            });
        },
        generarZipFIC: function(id_registro_pt) {
            var formData = new FormData();
            formData.append('id_registro_pt', id_registro_pt);
            

            var archivo06 = $('#archivo06')[0].files[0];
            if (archivo06) formData.append('archivo06', archivo06);
            
            var archivo08 = $('#archivo08')[0].files[0];
            if (archivo08) formData.append('archivo08', archivo08);
            
            var archivo09 = $('#archivo09')[0].files[0];
            if (archivo09) formData.append('archivo09', archivo09);
            
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Principal/generarZipFIC",
                data: formData,
                processData: false,
                contentType: false,
                xhrFields: {
                    responseType: 'blob' // Para manejar la respuesta como archivo
                },
                beforeSend: function() {
                    $('#btnZip').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                },
                  success: function(response, status, xhr) {
                    var filename = "documentos_" + id_registro_pt + ".zip";

                    // Crear Blob y enlace de descarga
                    var blob = new Blob([response], { type: 'application/zip' });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.fire("Éxito", "ZIP generado y descargado correctamente", "success");
                },
                complete: function() {
                    $('#btnZip').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Generar Zip');
                  $('#archivo05').val('');
                  $('#archivo06').val('');
                  $('#archivo08').val('');
                  $('#archivo09').val('');
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseText || "Error al generar el archivo ZIP";
                    Swal.fire("Error", errorMessage, "error");
                }
            });
        },
         generarZipGO: function(id_registro_go) {
            var formData = new FormData();
            formData.append('id_registro_go', id_registro_go);
            
            // Agregar archivos si existen
            var archivo05 = $('#archivo05')[0].files[0];
            if (archivo05) formData.append('archivo05', archivo05);
            
            var archivo09 = $('#archivo09')[0].files[0];
            if (archivo09) formData.append('archivo09', archivo09);
            
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Principal/generarZipGO",
                data: formData,
                processData: false,
                contentType: false,
                xhrFields: {
                    responseType: 'blob' // Para manejar la respuesta como archivo
                },
                beforeSend: function() {
                    $('#btnZip').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                },
                  success: function(response, status, xhr) {
                    var filename = "documentos_" + id_registro_go + ".zip";

                    // Crear Blob y enlace de descarga
                    var blob = new Blob([response], { type: 'application/zip' });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.fire("Éxito", "ZIP generado y descargado correctamente", "success");
                },
                complete: function() {
                    $('#btnZip').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Generar Zip');
                  $('#archivo05').val('');
                  $('#archivo09').val('');
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseText || "Error al generar el archivo ZIP";
                    Swal.fire("Error", errorMessage, "error");
                }
            });
        },
     
        generarReporteExcel: function()
        {
        let periodoInicio = $('#periodoInicio').val();  
            $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Usuario/validarReporteExcel2",
                    data:{periodoInicio},
                    dataType: "json",
                    beforeSend: function(){
                      $('#btnReporteExcel').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                    },
                    success: function (response) {
                        console.log(response);
                      if(response.error){
                          Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                      }else{
                        const url = base_url + "index.php/Usuario/reporteIncidenciaExcel2/" + periodoInicio;
                        window.open(url, '_blank');
                      } 
                           
                    },
                    complete: function(){
                     $('#btnReporteExcel').prop('disabled', false).html('Excel');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                         var res= JSON.parse (response.responseText);
                         console.log(res.message);
                         //Swal.fire("Error", '<p> '+ res.message + '</p>');  
                    }
            });
        },
        generarReporteIndividual: function()
        {
        let periodoInicio = $('#periodoInicio').val();
        let periodoFin    = $('#periodoFin').val();
        let usuario       = $('#usuarioIncidencia').val();
            $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/validarReporte",
                    data:{periodoInicio,periodoFin, usuario },
                    dataType: "json",
                    beforeSend: function(){
                      $('#btnReporte').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                    },
                    success: function (response) {
                        if(response.error){
                           Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }else{
                            setTimeout(() => {
                                const url = base_url + "index.php/Agregar/ReporteUsuario/" + periodoInicio + '/' + periodoFin + '/' + usuario;
                                window.open(url, '_blank');
                            }, 500);    
                        }
                    },
                    complete: function(){
                     $('#btnReporte').prop('disabled', false).html('PDF');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                         var res= JSON.parse (response.responseText);
                        //  console.log(res.message);
                         Swal.fire("Error", '<p> '+ res.message + '</p>');  
                    }
            });
        },
        agregarCategoria: function(){
            $("#formAgregarCurso").submit(function (e) {
                e.preventDefault(); 
                var formData = $("#formAgregarCurso").serialize();
                console.log(formData);
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaCategoria",
                    data:formData,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(response.respuesta.error){
                            Swal.fire("error", "Solicite apoyo al area de sistemas","error" );
                        }
                        Swal.fire("success", "Se guardo con exito", "success");
                        $("#formAgregarCurso")[0].reset();
                        $('#categoryTree').jstree(true).refresh();
                        //window.location.href = base_url + "index.php/Agregar/Curso";
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                         var res= JSON.parse (response.responseText);
                        //  console.log(res.message);
                         Swal.fire("Error", '<p> '+ res.message + '</p>');  
                    }
                });
            });
        },
        formEditCurso: function(){
            $('#formEditCurso').submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();
                console.log(formData);   
                $.ajax({
                    url: base_url + "index.php/Usuario/UpdateCurso",
                    type: "post",
                    dataType: "json",
                    data: formData,
                    beforeSend: function () {
                        // element.disabled = true;
                        $('#btnGuardar').prop('disabled', true);
                    },
                    complete: function () {
                        // element.disabled = false;
                        $('#btnGuardar').prop('disabled', false);
                    },
                    success: function (response, textStatus, jqXHR) {
                        if (response.error) {
                            Swal.fire("Atención", response.respuesta, "warning");
                            return false;
                        }
                        Swal.fire("Correcto", "Registro exitoso", "success");
                        window.location.href = `${base_url}index.php/Agregar/Curso`;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("error(s):" + jqXHR);
                    },
                });

            });
       },  
        deletePT: function(id_pt){
                Swal.fire({
                    title: "Estas seguro de eliminar el registro",
                    text: "El registro se eliminará de la base de datos",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "SI, eliminar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                             $.ajax({
                                url: base_url + "index.php/Principal/deletePT",
                                type: "post",
                                dataType: "json",
                                data: {id_registro_pt:id_pt},
                              
                                success: function (response, textStatus, jqXHR) {
                                    if (response.error) {
                                        Swal.fire("Atención", response.respuesta, "warning");
                                        return false;
                                    }
                                    Swal.fire("Correcto", "Se elimino  correctamente", "success");
                                      setTimeout(() => {
                                                window.location.reload();
                                        }, 1500);
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log("error(s):" + jqXHR);
                                },
                            });
                        }
                    });
          
               

         
       },  
        deletePTVe: function(id_pt){
                Swal.fire({
                    title: "Estas seguro de eliminar el registro",
                    text: "El registro se eliminará de la base de datos",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "SI, eliminar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                             $.ajax({
                                url: base_url + "index.php/Principal/deletePTVe",
                                type: "post",
                                dataType: "json",
                                data: {id_registro_pt:id_pt},
                              
                                success: function (response, textStatus, jqXHR) {
                                    if (response.error) {
                                        Swal.fire("Atención", response.respuesta, "warning");
                                        return false;
                                    }
                                    Swal.fire("Correcto", "Se elimino  correctamente", "success");
                                      setTimeout(() => {
                                                window.location.reload();
                                        }, 1500);
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log("error(s):" + jqXHR);
                                },
                            });
                        }
                    });
       },  
       servicio: function(id_servicio, elemento) {

            // Ubica la fila (tr) donde se dio clic
            const fila = elemento.closest('tr');

            // Obtén los valores de los inputs de esa fila
            const monto = fila.querySelector('input[name="monto"]').value;
            const folio = fila.querySelector('input[name="folio"]').value;
            const periodo = fila.querySelector('select[name="periodo"]').value;
            const folio_fac = fila.querySelector('input[name="folio_fac"]').value;

            if(!monto){
                Swal.fire("Atención", "El monto es requerido", "info");
                return;
            }
            if(!folio){
                Swal.fire("Atención", "El monto es requerido", "info");
                return;
            }
            if(!periodo){
                Swal.fire("Atención", "El monto es requerido", "info");
                return;
            }

             window.open(base_url + "index.php/Principal/servicio/" + id_servicio+'/'+monto+'/'+folio+'/'+periodo+'/'+folio_fac, "_blank");

            // Aquí puedes enviarlo por AJAX, fetch, o lo que necesites
            // ini.inicio.enviarDatos(id_servicio, monto, folio, periodo);
        },
        
    }
})();