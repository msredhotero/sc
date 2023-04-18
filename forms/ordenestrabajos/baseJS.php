<?php



?>
    
            function frmAjaxModificar(id) {
                $.ajax({
                    url: '../../api/<?php echo $Ordenestrabajos::RUTA; ?>/buscar.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: {tabla: '<?php echo $Ordenestrabajos::TABLA; ?>', id: id},
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
                            if (data.datos.esreparacion == '1') {
                                var myModal = new bootstrap.Modal(document.getElementById('lgmModificarReparacion'), options);
                            } else {
                                var myModal = new bootstrap.Modal(document.getElementById('lgmModificar'), options);
                            }
                            
                            myModal.show();

                            <?php 
                            // **roles
                            // si es jefe de mecanicos y mecanico
                            if (($_SESSION['user']->getRefroles()==6)||($_SESSION['user']->getRefroles()==5)) { 
                            ?>
                            traerEstadosEspecificos(data.datos.refestados);
                            <?php
                            }
                            ?>


                            if (data.datos.esreparacion == '1') {
                                $('.frmModificarReparacion #refcamiones').val(data.datos.refcamiones);
                                $('.frmModificarReparacion #reftareas').val(data.datos.reftareas);
                                $('.frmModificarReparacion #refestados').val(data.datos.refestados);
                                $('.frmModificarReparacion #fechainicio').val(data.datos.fechainicio);
                                $('.frmModificarReparacion #fechafin').val(data.datos.fechafin);
                                $('.frmModificarReparacion #fecharealfinalizacion').val(data.datos.fecharealfinalizacion);
                                $('.frmModificarReparacion #observacion').val(data.datos.observacion);
                                

                                $('.frmModificarReparacion #idmodificar').val(id);
                            } else {
                                $('.frmModificar #refcamiones').val(data.datos.refcamiones);
                                $('.frmModificar #reftareas').val(data.datos.reftareas);
                                $('.frmModificar #refestados').val(data.datos.refestados);
                                $('.frmModificar #fechainicio').val(data.datos.fechainicio);
                                $('.frmModificar #fechafin').val(data.datos.fechafin);
                                $('.frmModificar #fecharealfinalizacion').val(data.datos.fecharealfinalizacion);
                                $('.frmModificar #observacion').val(data.datos.observacion);
                                

                                $('.frmModificar #idmodificar').val(id);
                            }
                            

                            
                            
                        }
                    },
                    //si ha ocurrido un error
                    error: function(){
                        $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                        $("#load").html('');
                    }
                });

            }

            function traerEstadosEspecificos(idestado) {
                $.ajax({
                    url: '../../api/estados/buscarAll.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: { id: idestado},
                    //mientras enviamos el archivo
                    beforeSend: function(){
                        $('.frmModificar #refestados').html('');
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
                            for(let i = 0; i < data.datos.length; i++) {
                                $('.frmModificar #refestados').append('<option value="'+data.datos[i].id+'">'+data.datos[i].estado+'</option>');
                                $('.frmModificarReparacion #refestados').append('<option value="'+data.datos[i].id+'">'+data.datos[i].estado+'</option>');
                            }
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
                    url: '../../api/<?php echo $Ordenestrabajos::RUTA; ?>/eliminar.php',
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

            $("#example").on("click",'.btnDocumentos', function(){
                idTable =  $(this).attr("id");
			    $(location).attr('href','documentos.php?id=' + idTable + '&ref=2');
            });

            $("#example").on("click",'.btnArchivo', function(){
                idTable =  $(this).attr("id");
			    $(location).attr('href','archivo.php?id=' + idTable);
            });


            $('.frmNuevo').submit(function(e){

                e.preventDefault();
                if ($('#sign_in')[0].checkValidity()) {
                    //información del formulario
                    var formData = new FormData($(".formulario")[0]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $Ordenestrabajos::RUTA; ?>/insertar.php',
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

                                $('#lgmNuevo #fechainicio').val('');
                                $('#lgmNuevo #fechafin').val('');
                                $('#lgmNuevo #fecharealfinalizacion').val('');
                                
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
                        url: '../../api/<?php echo $Ordenestrabajos::RUTA; ?>/modificar.php',
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


            $('.frmNuevoReparacion').submit(function(e){

                e.preventDefault();
                if ($('.formularioReparacion')[0].checkValidity()) {
                    //información del formulario
                    var formData = new FormData($(".formularioReparacion")[0]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $Ordenestrabajos::RUTA; ?>/insertar.php',
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

                                $('#lgmNuevoReparacion').modal('hide');

                                $('#lgmNuevoReparacion #fechainicio').val('');
                                $('#lgmNuevoReparacion #fechafin').val('');
                                $('#lgmNuevoReparacion #fecharealfinalizacion').val('');
                                
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


                $('.frmModificarReparacion').submit(function(e){

                e.preventDefault();
                if ($('.frmModificarReparacion')[0].checkValidity()) {

                    //información del formulario
                    var formData = new FormData($(".frmModificarReparacion")[0]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $Ordenestrabajos::RUTA; ?>/modificar.php',
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

                                $('#lgmModificarReparacion').modal('hide');
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