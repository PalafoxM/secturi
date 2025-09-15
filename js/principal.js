var saeg = window.ssa || {};

saeg.principal = (function () {
    return {
        cargar_documento: function () {
            $("#frmDocumento").submit(function (event) {
                //disable the default form submission                
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: base_url + '/index.php/Principal/SubiendoDocumento',
                    type: "post",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //data: $("#frmAsuntoEntradaNuevo").serialize(),
                    success: function (response, textStatus, jqXHR) {
                        //console.log(response);
                        if(response == 'correcto'){
                            Swal.fire("", "Se agregó correctamente el logotipo", "success");
                            location.reload();
                        }else{
                            Swal.fire("Error", response, "warning");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error');
                        console.log('error:' + textStatus, errorThrown);
                    }
                });
                event.preventDefault();
                event.stopImmediatePropagation();
            });
        },
        aceptarIncidencia: function(id_incidencia, id_aceptar, id_usuario) {
    // Primero mostrar el modal para capturar observaciones
            Swal.fire({
                title: 'Agregar Observaciones',
                input: 'textarea',
                inputLabel: 'Observaciones (opcional)',
                inputPlaceholder: 'Escribe tus observaciones aquí...',
                inputAttributes: {
                    'aria-label': 'Escribe tus observaciones aquí'
                },
                showCancelButton: true,
                confirmButtonText: 'Aceptar Incidencia',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: (observaciones) => {
                    // Validar si es necesario (opcional)
                    if (observaciones && observaciones.length > 500) {
                        Swal.showValidationMessage('Máximo 500 caracteres permitidos');
                        return false;
                    }
                    return observaciones;
                },
                customClass: {
                    popup: 'swal-wide'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si confirma, enviar al servidor con las observaciones
                    var observaciones = result.value || ''; // Si no escribió nada, enviar string vacío
                    
                    $.ajax({
                        url: base_url + "index.php/Agregar/aceptarIncidencia",
                        type: 'POST',
                        data: { 
                            id_incidencia: id_incidencia, 
                            id_aceptar: id_aceptar, 
                            id_usuario: id_usuario,
                            observaciones: observaciones // ← Nuevo campo
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (!response.error) {
                                Swal.fire("Correcto", response.respuesta, "success");
                            } else {
                                Swal.fire("Error", response.respuesta, "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire("Error", "Ocurrió un error en la solicitud: " + error, "error");
                        },
                        complete: function() {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    });
                }
            });
        },
        detalleIncidencia: function(id_incidencia)
        {
          $('#detalleIncidencia').modal('show');
           $.ajax({
                    url:  base_url + "index.php/Agregar/detalleIncidencia",
                    type: 'POST',
                    data: { id_incidencia },
                    dataType: 'json',
                    success: function(response) {
                    let resul = response.data;
                    if(resul){
                        $("#nombre").val(resul.nombre_completo);
                        $("#tipo_incidencia").val(resul.dsc_incidencia);
                        $("#detalles").val(resul.detalles);
                        $("#comentario").val(resul.comentario);
                        $("#hora_fin").val(resul.hora_fin);
                        $("#hora_inicio").val(resul.hora_inicio);
                    }
                     
                    },
                    error: function(xhr, status, error) {
                        Swal.fire("Error", "Ocurrió un error en la solicitud: " + error, "error");
                    }
                });

        },
        eliminarIncidencia: function(id_incidencia)
        {
           $.ajax({
                    url:  base_url + "index.php/Agregar/eliminarIncidencia",
                    type: 'POST',
                    data: { id_incidencia },
                    dataType: 'json',
                    success: function(resul) {
                        if(!resul.error){
                            Swal.fire("Correcto", resul.respuesta, "success");
                        }else{
                            Swal.fire("Error", resul.respuesta, "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire("Error", "Ocurrió un error en la solicitud: " + error, "error");
                    },
                    complete: function()
                    {
                          setTimeout(() => {
                                 window.location.reload();
                        }, 1500);
                    }
                });

        },
         login: function() {
                let usuario = $('#usuario').val();              
                let contrasenia = $('#contrasenia').val(); 
        
                if (!usuario || !contrasenia) {
                    Swal.fire("¡Atención!", "Es requerido el usuario y contraseña", "error");
                    return;
                }

                $('#btn_login').hide();           
                $('#btn_load').show(); 

   
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Login/validar_usuario",
                        data: {
                            usuario,
                            contrasenia
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            if (!response.error) {
                                Swal.fire("Bienvenido!", "Ingresando...", "success");
                             /*    if(response.asistencia){
                                    setTimeout(() => {
                                        Swal.fire({
                                            position: "top-end",
                                            icon: "success",
                                            title: "Asistencia registrada automáticamente",
                                            showConfirmButton: false,
                                            timer: 1000
                                        });
                                    }, 1000);
                                }else{
                                    setTimeout(() => {
                                        Swal.fire({
                                            position: "top-end",
                                            icon: "error",
                                            title: "Ubicación fuera de rango, asistencia no registrada",
                                            showConfirmButton: false,
                                            timer: 2500
                                        });
                                    }, 1000);
                                }*/

                                setTimeout(() => {
                                    window.location.href = base_url + "index.php/Inicio";
                                }, 1000); 
                            } else {
                                Swal.fire("Usuario incorrecto", "Favor de verificar sus credenciales", "error");
                            }
                        },
                        complete: function () {
                            $('#btn_login').show();
                            $('#btn_load').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire("Error en la conexión", textStatus, "error");
                            console.error('Error:', textStatus, errorThrown);
                        }
                    });
            },
        login2: function() {
                let usuario = $('#usuario').val();              
                let contrasenia = $('#contrasenia').val(); 
        
                if (!usuario || !contrasenia) {
                    Swal.fire("¡Atención!", "Es requerido el usuario y contraseña", "error");
                    return;
                }

                $('#btn_login').hide();           
                $('#btn_load').show(); 

                // Obtener geolocalización primero
                navigator.geolocation.getCurrentPosition(function(position) {
                    let latitud = position.coords.latitude;
                    let longitud = position.coords.longitude;
                    // Hacer la solicitud AJAX dentro del callback
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Login/validar_usuario",
                        data: {
                            usuario,
                            contrasenia,
                            latitud,
                            longitud
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            if (!response.error) {
                                Swal.fire("Bienvenido!", "Ingresando...", "success");
                                if(response.asistencia){
                                    setTimeout(() => {
                                        Swal.fire({
                                            position: "top-end",
                                            icon: "success",
                                            title: "Asistencia registrada automáticamente",
                                            showConfirmButton: false,
                                            timer: 1000
                                        });
                                    }, 1000);
                                }else{
                                    setTimeout(() => {
                                        Swal.fire({
                                            position: "top-end",
                                            icon: "error",
                                            title: "Ubicación fuera de rango, asistencia no registrada",
                                            showConfirmButton: false,
                                            timer: 2500
                                        });
                                    }, 1000);
                                }

                                setTimeout(() => {
                                    window.location.href = base_url + "index.php/Inicio";
                                }, 3000);
                            } else {
                                Swal.fire("Usuario incorrecto", "Favor de verificar sus credenciales", "error");
                            }
                        },
                        complete: function () {
                            $('#btn_login').show();
                            $('#btn_load').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire("Error en la conexión", textStatus, "error");
                            console.error('Error:', textStatus, errorThrown);
                        }
                    });

                }, function(error) {
                    // Error al obtener ubicación
                    $('#btn_login').show();
                    $('#btn_load').hide();
                    
                    Swal.fire({
                    title: "Ubicación requerida",
                    text: "SUSI pide su ubicacion para la asistencia",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Seguir sin ubicacion"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        saeg.principal.loginSinUbicacion();
                    }
                    });

                  
                });
            },
            loginSinUbicacion: function() {
                let usuario = $('#usuario').val();              
                let contrasenia = $('#contrasenia').val(); 
        
                if (!usuario || !contrasenia) {
                    Swal.fire("¡Atención!", "Es requerido el usuario y contraseña", "error");
                    return;
                }
                $('#btn_login').hide();           
                $('#btn_load').show(); 
                    $.ajax({
                        type: "POST",
                        url: base_url + "index.php/Login/validar_usuario",
                        data: {
                            usuario,
                            contrasenia
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            if (!response.error) {
                                Swal.fire("Bienvenido!", "Ingresando...", "success");
                                if(response.asistencia){
                                    setTimeout(() => {
                                        Swal.fire({
                                            position: "top-end",
                                            icon: "success",
                                            title: "Asistencia registrada automáticamente",
                                            showConfirmButton: false,
                                            timer: 1000
                                        });
                                    }, 1000);
                                }else{
                                    setTimeout(() => {
                                        Swal.fire({
                                            position: "top-end",
                                            icon: "error",
                                            title: "Ubicación fuera de rango, asistencia no registrada",
                                            showConfirmButton: false,
                                            timer: 2500
                                        });
                                    }, 1000);
                                }

                                setTimeout(() => {
                                    window.location.href = base_url + "index.php/Inicio";
                                }, 3000);
                            } else {
                                Swal.fire("Usuario incorrecto", "Favor de verificar sus credenciales", "error");
                            }
                        },
                        complete: function () {
                            $('#btn_login').show();
                            $('#btn_load').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire("Error en la conexión", textStatus, "error");
                            console.error('Error:', textStatus, errorThrown);
                        }
                    });

           
            },
        
    }
})();