<?php

spl_autoload_register(function($clase){
    include_once "../../includes/" .$clase. ".php";        
});

$entity = new Cuadrillas();

?>
    Swal.fire({
        title: "Cuadrillas Cargadas",
        text: '',
        icon: 'info',
        timer: 1000,
        showConfirmButton: false
    });
    
            function frmAjaxModificar(id) {
                $.ajax({
                    url: '../../api/<?php echo $entity::RUTA; ?>/buscar.php',
                    type: 'POST',
                    // Form data
                    //datos del formulario
                    data: { id: id},
                    //mientras enviamos el archivo
                    beforeSend: function(){
                        $('.frmAjaxModificar').html('');
                        $('.frmModificar #refpadre option').prop('disabled', false);
                        $('.frmModificar .labelUsuario').html('');
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

                            $('.frmModificar #refordenestrabajocabecera').val(data.datos.refordenestrabajocabecera);
                            
                            $('.frmModificar #refusuarios').val(data.datos.refusuarios);

                            $('.frmModificar .labelUsuario').html(data.datos.usuario.apellido + ' ' + data.datos.usuario.nombre);

                            if (data.datos.asignado == '1') {
                                $('.frmModificar #asignado').prop('checked',true);
                            } else {
                                $('.frmModificar #asignado').prop('checked',false);
                            }

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
                    url: '../../api/<?php echo $entity::RUTA; ?>/eliminar.php',
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

            $("#example").on("click",'.btnFormularios', function(){
                idTable =  $(this).attr("id");
                $(location).attr('href','formulariosconector.php?id=' + idTable);
                
            });


            $('.frmNuevo').submit(function(e){

                e.preventDefault();
                if ($('#sign_in')[0].checkValidity()) {
                    //información del formulario
                    var formData = new FormData($(".formulario")[0]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $entity::RUTA; ?>/insertar.php',
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
            });


            $('.frmModificar').submit(function(e){

                e.preventDefault();
                if ($('.frmModificar')[0].checkValidity()) {

                    //información del formulario
                    var formData = new FormData($(".formulario")[1]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $entity::RUTA; ?>/modificar.php',
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