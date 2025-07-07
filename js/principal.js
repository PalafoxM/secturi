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
    
     login: function() {
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
                        if (!response.error) {
                            Swal.fire("Bienvenido!", "Ingresando...", "success");

                            setTimeout(() => {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: "Asistencia registrada automáticamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }, 1000);

                            setTimeout(() => {
                                window.location.href = base_url + "index.php/Inicio";
                            }, 2000);
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

                Swal.fire("Ubicación requerida", "Debe permitir el acceso a su ubicación para continuar", "error");
            });
        },
     

    }
})();