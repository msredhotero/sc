<?php

?>


<div class="modal fade modal2" id="lgmEliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Eliminar Revisiones Tecnicas</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario" role="form" id="sign_in">
                <div class="row">
                    <p>¿Esta seguro que desea eliminar el registro?</p>
					<small>* Si este registro esta relacionado con algun otro dato no se podría eliminar.</small>
                    
                    <input type="hidden" name="ideliminar" id="ideliminar" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn bg-gradient-danger eliminar">Eliminar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
