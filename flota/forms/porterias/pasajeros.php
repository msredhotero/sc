<div class="modal fade" id="lgmPasajeros" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg2 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Seleccione los Pasajeros</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <?php foreach ($lstConductores as $row) { ?>
                        
                    <div class="col-6">
                        <div class="form-check3" style="float: left;">
                            <input class="form-check-input3 refpasajeros" type="checkbox" name="refpasajeros[]" value="<?php echo $row['id']; ?>" id="refpasajeros<?php echo $row['id']; ?>" >
                            <label class="custom-control-label3" for="customCheck<?php echo $row['id']; ?>"><?php echo $row['primerapellido'].' '.$row['segundoapellido'].' '.$row['nombres']; ?></label>
                        </div>
                    </div>
                        
                    <?php } ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>