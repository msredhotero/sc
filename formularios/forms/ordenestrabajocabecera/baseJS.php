<?php


?>
    $('#lgmNuevo #fecha').val('<?php echo date('Y-m-d H:i'); ?>', $('#refsolicitudesvisitas').val());

    function traerSV(idsv) {
        $.ajax({
            url: '../../api/solicitudesvisitas/buscar.php',
            type: 'POST',
            // Form data
            //datos del formulario
            data: {id: idsv},
            //mientras enviamos el archivo
            beforeSend: function(){
                $('.contSV').html('');
            },
            //una vez finalizado correctamente
            success: function(data){

                if (data.error) {
                    $('.contSV').html('');
                } else {

                    $('.lstUsuarios').html(data.datos);
                    $('.contSV').append('<div class="col-3">ACTIVIDAD: <b>' + data.datos.actividad + '</b></div>');
                    $('.contSV').append('<div class="col-3">NRO AVISO: <b>' + data.datos.nroaviso + '</b></div>');
                    $('.contSV').append('<div class="col-3">CLASE DE AVISO: <b>' + data.datos.claseaviso + '</b></div>');
                    $('.contSV').append('<div class="col-3">AUTOR DE AVISO: <b>' + data.datos.autoraviso + '</b></div>');
                    $('.contSV').append('<div class="col-3">DESCRIPCIÓN: <b>' + data.datos.descripcion + '</b></div>');
                    


                }
            },
            //si ha ocurrido un error
            error: function(){
                $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                $('.contSV').html('');
            }
        });
    }

    function traerUsuariosDisponibles(fecha,idsv) {
        $.ajax({
            url: '../../api/ordenestrabajocabecera/buscarusuariosdisponibles.php',
            type: 'POST',
            // Form data
            //datos del formulario
            data: {fecha: fecha, idsv: idsv},
            //mientras enviamos el archivo
            beforeSend: function(){
                $('.lstUsuarios').html('');
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

                    $('.lstUsuarios').html(data.datos);
                    

                }
            },
            //si ha ocurrido un error
            error: function(){
                $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                $("#load").html('');
            }
        });
    }

    traerUsuariosDisponibles($('#lgmNuevo #fecha').val(), $('#refsolicitudesvisitas').val());
    traerSV($('#refsolicitudesvisitas').val());

    $('#lgmNuevo #fecha').change(function() {
        traerUsuariosDisponibles($(this).val(), $('#refsolicitudesvisitas').val());
    });

    $('#lgmNuevo #refsolicitudesvisitas').change(function() {
        traerUsuariosDisponibles($(this).val(), $('#refsolicitudesvisitas').val());
        traerSV($(this).val());
    });

            function frmAjaxModificar(id) {
                $.ajax({
                    url: '../../api/<?php echo $Ordenestrabajocabecera::RUTA; ?>/buscar.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: {tabla: '<?php echo $Ordenestrabajocabecera::TABLA; ?>', id: id},
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

                            $('.frmModificar #refsolicitudesvisitas').val(data.datos.refsolicitudesvisitas);
                            $('.frmModificar #refsemaforo').val(data.datos.refsemaforo);
                            $('.frmModificar #fecha').val(data.datos.fecha);
                            $('.frmModificar #fechafin').val(data.datos.fechafin);
                            $('.frmModificar #refestados').val(data.datos.refestados);

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
                    url: '../../api/<?php echo $Ordenestrabajocabecera::RUTA; ?>/eliminar.php',
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
                            location.reload();

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

            $("#example").on("click",'.btnVer', function(){
                idTable =  $(this).attr("id");
                $(location).attr('href','ver.php?id=' + idTable);
                
            });

            $("#example").on("click",'.btnCuadrilla', function(){
                idTable =  $(this).attr("id");
                $(location).attr('href','cuadrilla.php?id=' + idTable);
                
            });


            $('.frmNuevo').submit(function(e){
                e.preventDefault();
                if ($('div.checkbox-group.required .reftareas:checked').length > 0) {
                    
                    if ($('#sign_in')[0].checkValidity()) {
                        //información del formulario
                        var formData = new FormData($(".formulario")[0]);
                        var message = "";
                        //hacemos la petición ajax
                        $.ajax({
                            url: '../../api/<?php echo $Ordenestrabajocabecera::RUTA; ?>/insertar.php',
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
                                    $('#lgmNuevo #fechafin').val('');
                                    
                                    table.ajax.reload();
                                    location.reload();


                                }
                            },
                            //si ha ocurrido un error
                            error: function(){
                                $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                                $("#load").html('');
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        title: "Error",
                        text: 'Debe seleccionar alguna tarea',
                        icon: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
                
            });


            $('.frmModificar').submit(function(e){

                e.preventDefault();
                if ($('.frmModificar')[0].checkValidity()) {

                    //información del formulario
                    var formData = new FormData($(".frmModificar")[0]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $Ordenestrabajocabecera::RUTA; ?>/modificar.php',
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