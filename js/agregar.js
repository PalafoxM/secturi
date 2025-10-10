var st = window.ssa || {};

st.agregar = (function () {
    return {
        sha256: function (str) {
            var buffer = new TextEncoder("utf-8").encode(str);
            return crypto.subtle.digest("SHA-256", buffer).then(function (hash) {
                return Array.prototype.map.call(new Uint8Array(hash), function (x) {
                    return ('00' + x.toString(16)).slice(-2);
                }).join('');
            });
        },
        crearEvento: function () {
            $.ajax({
                url: base_url + "index.php/Principal/crearEvento",
                type: 'POST',
                data: formData,
                success: function (response) {

                    Swal.fire("Éxito", "Datos enviados correctamente", "success");
                    form[0].reset(); // Limpiar el formulario después del envío
                },
                error: function (xhr, status, error) {
                    alert('Error al enviar los datos: ' + error);
                }
            });
        },
        editarRegistro: function (id_incidencia) {
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getIncidencia",
                dataType: "json",
                data: { id_incidencia },
                success: function (data) {
                    console.log(data);

                    // Convertir ISO a YYYY-MM-DD
                    const formatoFecha = (fecha) => {
                        return new Date(fecha).toISOString().split('T')[0];
                    };

                    // Llenar los campos del formulario
                    $("#fecha_inicio_asistencia").val(formatoFecha(data.fecha_inicio));
                    $("#fecha_fin_asistencia").val(formatoFecha(data.fecha_fin));
                    $("#hora_inicio_asistencia").val(data.hora_inicio);
                    $("#hora_fin_asistencia").val(data.hora_fin);
                    $("#comentario_asistencia").val(data.comentario);
                    $("#detalle_asistencia").val(data.detalles);
                    $("#id_incidencia").val(data.id_incidencia);

                    // Inicializar Select2 SIEMPRE antes de asignar el valor
                    // $("#tipo_incidencia_editar").select2();

                    // Asignar valor a Select2
                    $("#tipo_incidencia_editar").val(data.cat_id_incidencia).change();

                    // Mostrar el modal DESPUÉS de llenar los datos
                    $("#modalAsistencia").modal('show');
                },
                error: function () {
                    Swal.fire("Error", "Error al obtener los datos de la incidencia.", "error");
                }
            });
        },
        modalSala: function (id) {
            $("#verSala").modal('show');
            let img = "";
            let sala = "";
            switch (id) {
                case 1:
                    img = `<img src="${base_url + 'assets/images/fotos/alba/salas/salaA.jpg'}" class="img-fluid rounded"/>`;
                    sala = "SALA DE JUNSTAS <strong>A</strong>";
                    break;
                case 2:
                    img = `<img src="${base_url + 'assets/images/fotos/alba/salas/salaB.jpg'}" class="img-fluid rounded"/>`;
                    sala = "SALA DE JUNSTAS <strong>B</strong>";
                    break;
                case 3:
                    img = `<img src="${base_url + 'assets/images/fotos/alba/salas/salaAB.jpg'}" class="img-fluid rounded"/>`;
                    sala = "SALA DE JUNSTAS <strong>AB</strong>";
                    break;
                case 4:
                    img = `<img src="${base_url + 'assets/images/fotos/alba/salas/salaTI.jpg'}" class="img-fluid rounded"/>`;
                    sala = "SALA DE JUNSTAS <strong>TI</strong>";
                    break;

            }
            $(".met-profile-main-pic3").html(`<p>${sala}</p>${img}`);
        },
        cerrarSala: function () {
            $("#verSala").modal('hide');
        },
        validacionIncapacidad: function () {
            let incidencia = $('#tipo_incidencia').val();
            console.log(incidencia);
            if (incidencia == 5 || incidencia == 4) {
                let texto = (incidencia == 5) ? 'VACACIONES' : 'LICENCIA MÉDICA';
                $('#timepicker_inicio').val('8:30').prop('disabled', true)
                $('#timepicker_fin').val('16:00').prop('disabled', true)
                $('#detalles').val(texto).prop('disabled', true)
                $('#comentario').val(texto).prop('disabled', true)
            } else {
                $('#timepicker_inicio').val('').prop('disabled', false)
                $('#timepicker_fin').val('').prop('disabled', false)
                $('#detalles').val('').prop('disabled', false)
                $('#comentario').val('').prop('disabled', false)
            }
        },
        validacionIncapacidadS: function () {
            let incidencia = $('#tipo_incidencia_semana').val();
            console.log(incidencia);
            if (incidencia == 5 || incidencia == 4) {
                let texto = (incidencia == 5) ? 'VACACIONES' : 'LICENCIA MÉDICA';

                $('#detalles_semana').val(texto).prop('disabled', true)
                $('#comentario_semana').val(texto).prop('disabled', true)
            } else {
                $('#detalles_semana').val('').prop('disabled', false)
                $('#comentario_semana').val('').prop('disabled', false)
            }
        },
        agregarUsuario: function () {
            $("#formAgregarUsuarioTsi").submit(function (e) {
                e.preventDefault();
                $("#id_perfil").prop("disabled", false);
                var formData = new FormData(this);
                $("#id_perfil").prop("disabled", true);
                $("#btn_save").hide();
                $("#btn_load").show();
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaUsuarioSti",
                    data: formData,
                    processData: false,  // evita que jQuery convierta el FormData en string
                    contentType: false,  // evita que jQuery ponga content-type incorrecto
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if (response.error) {
                            Swal.fire("error", response.respuesta, "error");
                        } else {
                            Swal.fire("success", "Se guardo con exito", 'success');
                            $("#formAgregarUsuarioTsi")[0].reset();
                            $("#btn_save").show();
                            $("#btn_load").hide();
                            window.location.href = base_url + "index.php/Inicio/usuarios";
                        }

                    },
                    error: function (response, jqXHR, textStatus, errorThrown) {
                        var res = JSON.parse(response.responseText);
                        //  console.log(res.message);
                        Swal.fire("Error", '<p> ' + res.message + '</p>', 'error');
                        $("#btn_save").show();
                        $("#btn_load").hide();
                    }
                });
            });
        },
        registroSala: function () {
            $("#registroSala").submit(function (e) {
                e.preventDefault();

                var formData = $("#registroSala").serialize();

                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/registroSala",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if (!response.error) {
                            Swal.fire("Correcto", '<p> ' + response.respuesta + '</p>', 'success');

                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            Swal.fire("Error", '<p> ' + response.respuesta + '</p>', 'error');
                        }

                        // window.location.href = base_url + "index.php/Inicio/usuarios";


                    },
                    error: function (response, jqXHR, textStatus, errorThrown) {
                        var res = JSON.parse(response.responseText);
                        //  console.log(res.message);
                        Swal.fire("Error", '<p> ' + res.message + '</p>', 'error');
                        $("#btn_save").show();
                        $("#btn_load").hide();
                    }
                });
            });
        },

        validarCURP: function () {
            const btnBuscar = document.getElementById('icono');
            const inputCurp = document.getElementById('curp');

            const curp = inputCurp.value.trim().toUpperCase();
            inputCurp.value = curp; // Convertir a mayúsculas

            if (curp.length >= 18) {
                st.agregar.toggleButtonState('check');
                inputCurp.style.color = "black";
                st.agregar.consultarCURP();
            } else if (curp.length === 0) {
                st.agregar.toggleButtonState('search');
            } else {
                // Estado de "cargando" mientras se escribe
                btnBuscar.classList.remove('dripicons-loading');
                st.agregar.toggleButtonState('loading');
                inputCurp.style.color = "red";
            }
        },
        btnEliminar: function (id) {
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
                    $.ajax({
                        url: base_url + "index.php/Usuario/estudianteCurso",
                        type: "post",
                        dataType: "json", //expect return data as html from server
                        data: { id_estudiante_curso: id },
                        success: function (response, textStatus, jqXHR) {
                            if (response.error) {
                                Swal.fire("Atención", response.respuesta, "warning");
                                return false;
                            }
                            Swal.fire("Éxito", response.respuesta, "success");
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
        programar: function (id) {
            const radioSeleccionado = document.querySelector('input[name="periodo"]:checked');
            if (!radioSeleccionado) {
                Swal.fire('Atención', 'Por favor, selecciona un período antes de guardar.', 'info'); // Mensaje de error
                return;
            }
            const periodoSeleccionado = radioSeleccionado.value;
            $('#guardar_programa').hide();
            $('#load_programar_curso').show();
            let editar = $("#editar_detalle").val();
            let id_periodo_editar = $("#id_periodo_editar").val();
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Agregar/guardarCursoPrograma",
                dataType: "json",
                data: {
                    id_curso_sac: id,
                    periodo: periodoSeleccionado, // Enviar el valor del período seleccionado
                    editar,
                    id_periodo_editar
                },
                success: function (data) {
                    console.log(data);
                    if (!data.error) {
                        Swal.fire("Éxito", data.respuesta, "success");
                        window.location.href = base_url + 'index.php/Agregar/ProgramarCurso';
                    } else {
                        Swal.fire("Error", data.respuesta, "error");
                    }
                },
                complete: function () {
                    $('#guardar_programa').show();
                    $('#load_programar_curso').hide();
                },
                error: function () {
                    Swal.fire("Error", "Error al guardar comentario.", "error");
                }
            });
        },
        toggleButtonState: function (state) {
            const spinner = document.getElementById('spinner');
            const btnBuscar = document.getElementById('icono');
            spinner.style.display = state === 'loading' ? "block" : "none";
            btnBuscar.classList.remove('dripicons-search', 'dripicons-checkmark', 'dripicons-loading');

            if (state === 'check') btnBuscar.classList.add('dripicons-checkmark');
            //else if (state === 'loading') btnBuscar.classList.add('dripicons-loading');
            //else btnBuscar.classList.add('dripicons-search');
        },
        consultarCURP: function () {
            const inputCurp = document.getElementById('curp');
            const curp = inputCurp.value;

            if (curp.length !== 18) {
                Swal.fire("Error", 'Ingresa una CURP válida.', "error");
                $("#formParticipante")[0].reset();
                return;
            }

            $.ajax({
                url: api,
                type: 'POST',
                dataType: 'json',
                data: {
                    curp: curp,
                    script: 'Bitacora->Script:001/15',
                    id_clues: '0780',
                    id_usuario: 7
                },
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                success: function (result) {
                    console.log(result)
                    if (result.datos) {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Validado por RENAPO",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        inputCurp.style.color = "green";
                        st.agregar.toggleButtonState('check');
                        st.agregar.mostrarCamposDatos(result.datos);
                    }
                    if (result.error) {
                        inputCurp.style.color = "red";
                        st.agregar.toggleButtonState('search');
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: result.respuesta,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        inputCurp.style.color = "red";
                        st.agregar.toggleButtonState('check');
                        st.agregar.mostrarCamposDatos(result.datos);
                    }


                },
                error: function (xhr) {
                    console.log("Error:", xhr.responseText);
                    inputCurp.style.color = "red";
                }
            });
        },
        mostrarCamposDatos: function (datos) {
            // Rellenar los campos con los datos obtenidos
            document.getElementById('nombre').value = `${datos.nombre}`;
            document.getElementById('primer_apellido').value = `${datos.primerApellido}`;
            document.getElementById('segundo_apellido').value = `${datos.segundoApellido}`;
            document.getElementById('id_sexo').value = datos.sexo;
            document.getElementById('fec_nac').value = datos.fechaNacimiento;
            document.getElementById('rfc').value = datos.CURP.substring(0, 10);
            document.getElementById('usuario').value = datos.CURP;
            document.getElementById('contrasenia').value = datos.CURP;
            document.getElementById('confirmar_contrasenia').value = datos.CURP;
        },
        cancelarTurno: function () {
            Swal.fire({
                title: "¿Está seguro de que desea cancelar?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Si",

            }).then((result) => {
                if (result.isConfirmed) {
                    $("#formAgregarTurno")[0].reset();
                    window.location.href = base_url + "index.php/Inicio";
                } else if (result.isDenied) {
                    Swal.fire("Ok", "", "info");
                }
            });


        },
        saveTempNombreTurno: function () {
            $('#nombre_turno').on('change', function () {
                // Obtener los valores y textos de las opciones seleccionadas
                var selectedValues = $(this).val();
                var selectedTexts = $('#nombre_turno option:selected').map(function () {
                    return $(this).text();
                }).get();
                updateTable(selectedValues, selectedTexts);
            });

            // Función para actualizar la tabla
            function updateTable(values, texts) {
                // Limpiar la tabla
                $('#selectedValuesNombreTurno tbody').empty();
                $('#selectedValuesNombreTurno1 tbody').empty();

                // Mostrar los valores y textos seleccionados en la tabla
                if (values && values.length > 0) {
                    for (var i = 0; i < values.length; i++) {
                        $('#selectedValuesNombreTurno tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                        $('#selectedValuesNombreTurno1 tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                    }
                } else {
                    $('#selectedValuesNombreTurno tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                    $('#selectedValuesNombreTurno1 tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                }
            }
        },

        saveTempccp: function () {

            $('#cpp').on('change', function () {
                // Obtener los valores y textos de las opciones seleccionadas
                var selectedValues = $(this).val();
                var selectedTexts = $('#cpp option:selected').map(function () {
                    return $(this).text();
                }).get();

                // Actualizar la tabla
                updateTable(selectedValues, selectedTexts);
            });

            // Función para actualizar la tabla
            function updateTable(values, texts) {
                // Limpiar la tabla
                $('#selectedValuesTable tbody').empty();
                $('#selectedValuesTable1 tbody').empty();

                // Mostrar los valores y textos seleccionados en la tabla
                if (values && values.length > 0) {
                    for (var i = 0; i < values.length; i++) {
                        $('#selectedValuesTable tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                        $('#selectedValuesTable1 tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                    }
                } else {
                    $('#selectedValuesTable tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                    $('#selectedValuesTable1 tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                }
            }
        },
        saveTempIndicacion: function () {
            $('#indicacion').on('change', function () {
                // Obtener los valores y textos de las opciones seleccionadas
                var selectedValues = $(this).val();
                var selectedTexts = $('#indicacion option:selected').map(function () {
                    return $(this).text();
                }).get();
                updateTable(selectedValues, selectedTexts);
            });

            // Función para actualizar la tabla
            function updateTable(values, texts) {
                // Limpiar la tabla
                $('#selectedValuesIndicacion tbody').empty();
                $('#selectedValuesIndicacion1 tbody').empty();

                // Mostrar los valores y textos seleccionados en la tabla
                if (values && values.length > 0) {
                    for (var i = 0; i < values.length; i++) {
                        $('#selectedValuesIndicacion tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                        $('#selectedValuesIndicacion1 tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                    }
                } else {
                    $('#selectedValuesIndicacion tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                    $('#selectedValuesIndicacion1 tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                }
            }
        },
        validarEntrada: function (input) {
            var resumen = input.val();
            var regex = /^[a-zA-Z0-9\s.,!?()-]+$/;
            $pattern = "/^([a-zA-ZáéíóúüñÁÉÍÓÚÜÑ 0-9]+)$/";
            if (resumen.length > 0 && resumen.length <= 600 && regex.test(resumen)) {
                input.removeClass("invalid-input");
                return true;
            } else {
                input.addClass("invalid-input");
                return false;

            }
        },
        // convioerte todo los de los inputs a mayusculas
        toUpperCase: function (element) {
            element.value = element.value.toUpperCase();
        },
        formConfigurarCurso: function () {
            $('#btn_guardar_conf').on('click', function () {
                $("#btn_guardar_conf").hide();
                $("#btn_guardar_load").show();
                let tableData = [];

                // Itera sobre cada fila en el cuerpo de la tabla
                $('tbody tr').each(function () {
                    let rowData = {
                        name: $(this).find('td:first').text(),  // Nombre del curso
                        id_curso: $(this).find('input[name^="id_curso"]').val(), // Fecha de inicio
                        timeopen: $(this).find('input[name^="timeopen"]').val(), // Fecha de inicio
                        timeclose: $(this).find('input[name^="timeclose"]').val(), // Fecha de fin
                        // timelimit: $(this).find('td:nth-child(4)').text(), // Límite de tiempo
                        // visible: $(this).find('input[type="checkbox"]').is(':checked') ? 1 : 0 // Si está visible
                    };

                    tableData.push(rowData);
                });
                let id_curso = $("#id_curso").val();
                let fec_inicio = $("#fec_inicio").val();
                let fec_fin = $("#fec_fin").val();
                if (fec_inicio > fec_fin) {
                    Swal.fire("Error", "Fecha inicio debe ser mayor a Fecha fin", "error");
                    $("#btn_guardar_conf").show();
                    $("#btn_guardar_load").hide();
                    return
                }
                $.ajax({
                    url: base_url + "index.php/Agregar/formConfigurarCurso",
                    type: 'POST',
                    data: { tableData: tableData, id_curso: id_curso, fec_inicio, fec_fin },
                    dataType: 'json',
                    success: function (response) {
                        if (!response.error) {
                            Swal.fire("Éxito", "Datos guardados correctamente.", "success");
                            //window.location.reload();
                            window.location.href = base_url + "index.php/Principal/Matricular/";
                        } else {
                            Swal.fire("Error", "No se pudo guardar la configuración.", "error");
                        }
                        $("#btn_guardar_conf").show();
                        $("#btn_guardar_load").hide();
                    },
                    error: function (xhr, status, error) {
                        Swal.fire("Error", "Ocurrió un error en la solicitud: " + error, "error");
                    }
                });


            });

        },
        formParticipante: function () {
            $("#formParticipante").submit(function (e) {
                e.preventDefault();
                $("#btn_guardar_detenido").hide();
                $("#btn_load_detenido").show();
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Principal/guardarParticipantes",
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        console.log(response.error);
                        console.log(response.respuesta);
                        if (response.error == false) {
                            Swal.fire("Exitó", response.respuesta, "success");
                            $('#formParticipante')[0].reset();
                            $('#modalDetenidos').modal('hide');
                            window.location.reload();

                        } else {
                            Swal.fire("Error", response.respuesta, "error");
                            //$("#formParticipante")[0].reset();                         
                            return false;
                        }
                    },
                    complete: function () {
                        $("#btn_guardar_detenido").show();
                        $("#btn_load_detenido").hide();
                    },
                    error: function (response, jqXHR, textStatus, errorThrown) {
                        var res = JSON.parse(response.responseText);
                        //  console.log(res.message);
                        Swal.fire("Error", '<p> ' + res.message + '</p>');
                    }
                });
            });
        },
        calendarModal: function () {
            $('#calendarModal').modal('show');
        },
        sala: function (sala, dia) {
            $('#calendarModal').modal('hide');
            setTimeout(() => {
                $('#calendarModal2').modal('show');
                $('#chosenRoom').text(sala);
                $('#sala').val(sala);
                $('#fecha').val(dia.split('T')[0]);
            }, 300); // Espera a que termine la animación de cierre
        },
        justificarFalta: function (fecha) {
            try {
                // Validar que el parámetro no sea undefined o null
                if (fecha === undefined || fecha === null) {
                    throw new Error('Fecha no proporcionada');
                }

                // Convertir a string si es necesario
                let fechaStr;
                if (typeof fecha === 'string') {
                    fechaStr = fecha;
                } else if (fecha instanceof Date) {
                    fechaStr = fecha.toISOString().split('T')[0];
                } else {
                    // Intentar convertir otros tipos (números, objetos)
                    fechaStr = String(fecha);
                }

                // Validar formato esperado (YYYY-MM-DD)
                const regex = /^\d{4}-\d{2}-\d{2}$/;
                if (!regex.test(fechaStr)) {
                    throw new Error('Formato de fecha inválido. Se esperaba YYYY-MM-DD, se recibió: ' + fechaStr);
                }

                // Dividir la fecha en partes
                const partes = fechaStr.split('-');

                // Validar que tenga exactamente 3 partes
                if (partes.length !== 3) {
                    throw new Error('La fecha no tiene el formato correcto: ' + fechaStr);
                }

                // Validar que cada parte sean números válidos
                const anio = parseInt(partes[0]);
                const mes = parseInt(partes[1]);
                const dia = parseInt(partes[2]);

                if (isNaN(anio) || isNaN(mes) || isNaN(dia)) {
                    throw new Error('La fecha contiene valores no numéricos: ' + fechaStr);
                }

                // Validar rangos razonables
                if (anio < 2000 || anio > 2100) {
                    throw new Error('Año fuera de rango válido: ' + anio);
                }
                if (mes < 1 || mes > 12) {
                    throw new Error('Mes fuera de rango válido: ' + mes);
                }
                if (dia < 1 || dia > 31) {
                    throw new Error('Día fuera de rango válido: ' + dia);
                }

                // Formatear la fecha (DD-MM-YYYY)
                const fechaFormateada = `${partes[2]}-${partes[1]}-${partes[0]}`;

                // Mostrar el modal y establecer valores
                $("#modalJustificar").modal('show');
                $("#fecha_incidencia").html('<center><strong>' + fechaFormateada + '</strong></center>');
                $("#fecha").val(fechaStr);

            } catch (error) {
                console.error('Error en justificarFalta:', error);

                // Mostrar mensaje de error al usuario
                alert('Error: ' + error.message + '\nPor favor, contacte al administrador.');

                // Opcional: puedes mostrar el modal igual pero con un mensaje de error
                $("#modalJustificar").modal('show');
                $("#fecha_incidencia").html('<center><strong style="color: red;">Error en fecha</strong></center>');
                $("#fecha").val('invalid-date');
            }
        },
        existeIncidencia: function (fecha, callback) {
            $.ajax({
                url: base_url + "index.php/Principal/existeIncidencia",
                type: "post",
                dataType: "json",
                data: { fecha },
                success: function (response, textStatus, jqXHR) {
                    callback(response.error);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire("Atención", textStatus, 'info');
                    callback(false); // O manejar el error según necesites
                },
            });
        },
        guardarIncidencia: function () {
            let hora_inicio = $('#hora_inicio').val();
            let hora_fin = $('#hora_fin').val();
            let tipo_incidencia = $('#tipo_incidencia').val();
            let comentario = $('#comentario').val();
            let detalles = $('#detalles').val();
            let fecha = $('#fecha').val();

            if (!hora_inicio) {
                Swal.fire("Atención", 'Es requerido la <strong>hora de inicio</strong>', 'info');
                return
            }
            if (!hora_fin) {
                Swal.fire("Atención", 'Es requerido la <strong>hora de fin</strong>', 'info');
                return
            }
            if (!tipo_incidencia) {
                Swal.fire("Atención", 'Es requerido la <strong>tipo incidencia</strong>', 'info');
                return
            }
            if (!detalles) {
                Swal.fire("Atención", 'Es requerido la <strong>detalles</strong>', 'info');
                return
            }
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Principal/guardarIncidencia",
                data: { hora_inicio, hora_fin, tipo_incidencia, comentario, detalles, fecha },
                dataType: 'json',
                beforeSend: function () {
                    $('#btn_incidencia').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                },
                success: function (response) {
                    console.log(response);
                    if (!response.error) {
                        Swal.fire("Exitó", response.respuesta, "success");
                        window.location.reload();

                    } else {
                        Swal.fire("Error", response.respuesta, "error");
                    }
                },

                complete: function () {
                    $('#btn_incidencia').prop('disabled', false).html('Guardar');
                },
                error: function (response, jqXHR, textStatus, errorThrown) {
                    var res = JSON.parse(response.responseText);
                    //  console.log(res.message);
                    Swal.fire("Error", '<p> ' + res.message + '</p>');
                }
            });

        },
        guardarIncidenciaS: function () {

            let tipo_incidencia = $('#tipo_incidencia_semana').val();
            let comentario = $('#comentario_semana').val();
            let detalles = $('#detalles_semana').val();
            let datetimes = $('#datetimes').val();

            if (!datetimes) {
                Swal.fire("Atención", 'Es requerido la <strong>Semana/strong>', 'info');
                return
            }

            if (!tipo_incidencia) {
                Swal.fire("Atención", 'Es requerido la <strong>tipo incidencia</strong>', 'info');
                return
            }
            if (!detalles) {
                Swal.fire("Atención", 'Es requerido la <strong>detalles</strong>', 'info');
                return
            }
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Principal/guardarSemana",
                data: { datetimes, comentario, detalles, tipo_incidencia },
                dataType: 'json',
                beforeSend: function () {
                    $('#btn_semana').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                },
                success: function (response) {
                    console.log(response);
                    if (!response.error) {
                        Swal.fire("Exitó", response.respuesta, "success");
                        window.location.reload();

                    } else {
                        Swal.fire("Error", response.respuesta, "error");
                    }
                },

                complete: function () {
                    $('#btn_semana').prop('disabled', false).html('Guardar');
                },
                error: function (response, jqXHR, textStatus, errorThrown) {
                    var res = JSON.parse(response.responseText);
                    //  console.log(res.message);
                    Swal.fire("Error", '<p> ' + res.message + '</p>');
                    $('#btn_semana').prop('disabled', false).html('Guardar');
                }
            });

        },
        guardarIncidenciaM: function () {

            let tipo_incidencia = $('#tipo_incidencia_mes').val();
            let comentario = $('#comentario_mes').val();
            let detalles = $('#detalles_mes').val();
            let fecha_inicio = $('#mdate_inicio').val();
            let fecha_fin = $('#mdate_inicio').val();


            if (!fecha_inicio) {
                Swal.fire("Atención", 'Es requerido la <strong>fecha_inicio/strong>', 'info');
                return
            }
            if (!fecha_fin) {
                Swal.fire("Atención", 'Es requerido la <strong>fecha_fin/strong>', 'info');
                return
            }

            if (!tipo_incidencia) {
                Swal.fire("Atención", 'Es requerido la <strong>tipo incidencia</strong>', 'info');
                return
            }
            if (!detalles) {
                Swal.fire("Atención", 'Es requerido la <strong>detalles</strong>', 'info');
                return
            }
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Principal/guardarMes",
                data: { fecha_fin, fecha_inicio, detalles, comentario, tipo_incidencia },
                dataType: 'json',
                beforeSend: function () {
                    $('#btn_semana').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                },
                success: function (response) {
                    console.log(response);
                    if (!response.error) {
                        Swal.fire("Exitó", response.respuesta, "success");
                        window.location.reload();

                    } else {
                        Swal.fire("Error", response.respuesta, "error");
                    }
                },

                complete: function () {
                    $('#btn_semana').prop('disabled', false).html('Guardar');
                },
                error: function (response, jqXHR, textStatus, errorThrown) {
                    var res = JSON.parse(response.responseText);
                    //  console.log(res.message);
                    Swal.fire("Error", '<p> ' + res.message + '</p>');
                    $('#btn_semana').prop('disabled', false).html('Guardar');
                }
            });
        },


    }
})();