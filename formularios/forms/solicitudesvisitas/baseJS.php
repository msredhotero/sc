<?php



?>
    
            function frmAjaxModificar(id) {
                $.ajax({
                    url: '../../api/<?php echo $Solicitudesvisitas::RUTA; ?>/buscar.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: {tabla: '<?php echo $Solicitudesvisitas::TABLA; ?>', id: id},
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
                            if (data.datos.refestados == 2) {
                                Swal.fire({
                                    title: "Error",
                                    text: 'La solicitud ya fue asignada, no se podra modificar',
                                    icon: 'error',
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            } else {
                                var myModal = new bootstrap.Modal(document.getElementById('lgmModificar'), options);
                                myModal.show();
                            }
                            

                            $('.frmModificar #refclientes').val(data.datos.refclientes);
                            traerSucursales(1,data.datos.refclientes,'frmModificar',data.datos.refsucursales);
                            $('.frmModificar #refsucursales').val(data.datos.refsucursales);
                            $('.frmModificar #fecha').val(data.datos.fecha);
                            $('.frmModificar #refsemaforo').val(data.datos.refsemaforo);
                            $('.frmModificar #descripcion').val(data.datos.descripcion);
                            $('.frmModificar #refestados').val(data.datos.refestados);
                            $('.frmModificar #reftipoactividades').val(data.datos.reftipoactividades);
                            $('.frmModificar #refzonas').val(data.datos.refzonas);
                            $('.frmModificar #nroaviso').val(data.datos.nroaviso);
                            $('.frmModificar #claseaviso').val(data.datos.claseaviso);
                            $('.frmModificar #autoraviso').val(data.datos.autoraviso);

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

            function traerSucursales(reftabla,idreferencia,contenedor,idselect=0) {
                $.ajax({
                    url: '../../api/sucursales/buscarportabla.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: {reftabla: reftabla, idreferencia: idreferencia},
                    //mientras enviamos el archivo
                    beforeSend: function(){
                        $('.'+contenedor+' #refsucursales').html('');
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

                            $('.'+contenedor+' #refsucursales').html(data.datos);
                            $('.'+contenedor+' #refsucursales').prepend('<option vaue="0">-- Seleccionar --</option>');
                            if (idselect != 0) {
                                $('.'+contenedor+' #refsucursales').val(idselect);
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

            traerSucursales(1,$('#refclientes').val(),'lgmNuevoModal');

            $('.lgmNuevoModal #refclientes').change(function() {
                traerSucursales(1,$(this).val(),'lgmNuevoModal');
            });


            function frmAjaxEliminar(id) {
                $.ajax({
                    url: '../../api/<?php echo $Solicitudesvisitas::RUTA; ?>/eliminar.php',
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

            $("#example").on("click",'.btnTareas', function(){
                idTable =  $(this).attr("id");
                $(location).attr('href','tareas.php?id=' + idTable);
                
            });


            $('.frmNuevo').submit(function(e){

                e.preventDefault();
                if ($('#sign_in')[0].checkValidity()) {
                    //información del formulario
                    var formData = new FormData($(".formulario")[0]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $Solicitudesvisitas::RUTA; ?>/insertar.php',
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

                                $('#lgmNuevo #fecha').val('');
                                $('#lgmNuevo #descripcion').val('');
                                $('#lgmNuevo #nroaviso').val('');
                                $('#lgmNuevo #claseaviso').val('');
                                $('#lgmNuevo #autoraviso').val('');
                                
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
                        url: '../../api/<?php echo $Solicitudesvisitas::RUTA; ?>/modificar.php',
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