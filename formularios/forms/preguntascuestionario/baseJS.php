<?php



?>

            function traerColumnas(id) {
                $.ajax({
                    url: '../../api/tablas/buscar.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: {id: id},
                    //mientras enviamos el archivo
                    beforeSend: function(){
                        $('.contSV').html('');
                    },
                    //una vez finalizado correctamente
                    success: function(data){

                        if (data.error) {
                            $('#columna').html('');
                        } else {

                            $('.frmNuevo #columna').html(data.datos);
                            $('.frmModificar #columna').html(data.datos);

                        }
                    },
                    //si ha ocurrido un error
                    error: function(){
                        $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                        $('.contSV').html('');
                    }
                });
            }

            traerColumnas($('#reftabladatos').val());

            $('#reftabladatos').change(function() {
                traerColumnas($(this).val());
            });

            $('.contTablas').hide();
            $('.contDatos').hide();

            $('.frmModificar .contTablas').hide();
            $('.frmModificar .contDatos').hide();

            $('.frmNuevo #reftiporespuesta').change(function() {
                if ($(this).val() == 8) {
                    $('.contTablas').show();
                    $('.contDatos').show();
                } else {
                    $('.contTablas').hide();
                    $('.contDatos').hide();
                }
            });

            $('.frmModificar #reftiporespuesta').change(function() {
                if ($(this).val() == 8) {
                    $('.frmModificar .contTablas').show();
                    $('.frmModificar .contDatos').show();
                } else {
                    $('.frmModificar .contTablas').hide();
                    $('.frmModificar .contDatos').hide();
                }
            });
    
            function frmAjaxModificar(id) {
                $.ajax({
                    url: '../../api/<?php echo $Preguntascuestionario::RUTA; ?>/buscar.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: {tabla: '<?php echo $Preguntascuestionario::TABLA; ?>', id: id},
                    //mientras enviamos el archivo
                    beforeSend: function(){
                        $('.frmAjaxModificar').html('');
                    },
                    //una vez finalizado correctamente
                    success: function(data){

                        if (data.error) {
                            Swal.fire({
                                title: "Error",
                                text: data.mensaje,
                                icon: 'error',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        } else {
                            //$('#lgmModificar').show();
                            var myModal = new bootstrap.Modal(document.getElementById('lgmModificar'), options);
                            myModal.show();

                            $('.frmModificar #refformularios').val(data.datos.refformularios);
                            $('.frmModificar #reftiporespuesta').val(data.datos.reftiporespuesta);
                            $('.frmModificar #pregunta').val(data.datos.pregunta);
                            $('.frmModificar #orden').val(data.datos.orden);
                            $('.frmModificar #leyenda').val(data.datos.leyenda);

                            if (data.datos.activo == '1') {
                                $('.frmModificar #activo').prop('checked',true);
                            } else {
                                $('.frmModificar #activo').prop('checked',false);
                            }
                            if (data.datos.obligatoria == '1') {
                                $('.frmModificar #obligatoria').prop('checked',true);
                            } else {
                                $('.frmModificar #obligatoria').prop('checked',false);
                            }

                            if (data.datos.reftiporespuesta==8) {
                                $('.frmModificar .contTablas').show();
                                $('.frmModificar .contDatos').show();
                            }
                            $('.frmModificar #columna').val(data.datos.columna);

                            $('.frmModificar #idmodificar').val(id);

                            
                            
                        }
                    },
                    //si ha ocurrido un error
                    error: function(){
                        $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                        $("#load").html('');
                    }
                });

            }


            function frmAjaxEliminar(id) {
                $.ajax({
                    url: '../../api/<?php echo $Preguntascuestionario::RUTA; ?>/eliminar.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: { id: id},
                    //mientras enviamos el archivo
                    beforeSend: function(){

                    },
                    //una vez finalizado correctamente
                    success: function(data){

                        if (data.error) {
                            Swal.fire({
                                title: "Error",
                                text: data.mensaje,
                                icon: 'error',
                                timer: 2500,
                                showConfirmButton: false
                            });
                            
                        } else {
                            Swal.fire({
                                title: "Correcto",
                                text: data.mensaje,
                                icon: 'success',
                                timer: 2500,
                                showConfirmButton: false
                            });

                            $('#lgmEliminar').modal('toggle');
                            table.ajax.reload();

                        }
                    },
                    //si ha ocurrido un error
                    error: function(){
                        Swal.fire({
                            title: "Error",
                            text: 'actualice la pagina',
                            icon: 'error',
                            timer: 2500,
                            showConfirmButton: false
                        });

                    }
                });

            }

            $("#example").on("click",'.btnEliminar', function(){
                
                idTable =  $(this).attr("id");
                $('#ideliminar').val(idTable);
                
                var myModalEliminar = new bootstrap.Modal(document.getElementById('lgmEliminar'), options);
                myModalEliminar.show();
            });//fin del boton eliminar

            $('.eliminar').click(function() {
                frmAjaxEliminar($('#ideliminar').val());
            });

            $("#example").on("click",'.btnModificar', function(){
                idTable =  $(this).attr("id");
                frmAjaxModificar(idTable);
                
            });//fin del boton modificar

            $("#example").on("click",'.btnRespuestas', function(){
                idTable =  $(this).attr("id");
                $(location).attr('href','respuestas.php?id=' + idTable);
                
            });//fin del boton modificar


            $('.frmNuevo').submit(function(e){

                e.preventDefault();
                if ($('#sign_in')[0].checkValidity()) {
                    //información del formulario
                    var formData = new FormData($(".formulario")[0]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $Preguntascuestionario::RUTA; ?>/insertar.php',
                        type: 'POST',
                        // Form data
                        //datos del formulario
                        data: formData,
                        //necesario para subir archivos via ajax
                        cache: false,
                        contentType: false,
                        processData: false,
                        //mientras enviamos el archivo
                        beforeSend: function(){

                        },
                        //una vez finalizado correctamente
                        success: function(data){

                            if (data.error) {
                                Swal.fire({
                                    title: "Error",
                                    text: data.mensaje,
                                    icon: 'error',
                                    timer: 2500,
                                    showConfirmButton: false
                                });

                                
                            } else {
                                Swal.fire({
                                    title: "Correcto",
                                    text: data.mensaje,
                                    icon: 'success',
                                    timer: 2500,
                                    showConfirmButton: false
                                });

                                $('#lgmNuevo').modal('hide');

                                $('#lgmNuevo #pregunta').val('');
                                $('#lgmNuevo #leyenda').val('');
                                
                                table.ajax.reload();


                            }
                        },
                        //si ha ocurrido un error
                        error: function(){
                            $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                            $("#load").html('');
                        }
                    });
                }
            });


            $('.frmModificar').submit(function(e){

                e.preventDefault();
                if ($('.frmModificar')[0].checkValidity()) {

                    //información del formulario
                    var formData = new FormData($(".formulario")[1]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $Preguntascuestionario::RUTA; ?>/modificar.php',
                        type: 'POST',
                        // Form data
                        //datos del formulario
                        data: formData,
                        //necesario para subir archivos via ajax
                        cache: false,
                        contentType: false,
                        processData: false,
                        //mientras enviamos el archivo
                        beforeSend: function(){

                        },
                        //una vez finalizado correctamente
                        success: function(data){

                            if (data.error) {
                                Swal.fire({
                                    title: "Error",
                                    text: data.mensaje,
                                    icon: 'error',
                                    timer: 2500,
                                    showConfirmButton: false
                                });

                                
                            } else {
                                Swal.fire({
                                    title: "Correcto",
                                    text: data.mensaje,
                                    icon: 'success',
                                    timer: 2500,
                                    showConfirmButton: false
                                });

                                $('#lgmModificar').modal('hide');
                                table.ajax.reload();


                            }
                        },
                        //si ha ocurrido un error
                        error: function(){
                            $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                            $("#load").html('');
                        }
                    });
                }
            });