<?php


?>


<form action="subir.php" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
    <div class="dz-message">
        <div class="drag-icon-cph">
            <h1><i class="ni ni-active-40"></i></h1>
        </div>
        <h3>Arrastre y suelte una imagen O PDF aqui o haga click y busque una imagen en su ordenador.</h3>
    </div>
    <div class="fallback">
        <input name="file" type="file" id="archivos" />
        <input type="hidden" id="idreferencia" name="idreferencia" value="<?php echo $idreferencia; ?>" />
        <input type="hidden" id="reftabla" name="reftabla" value="<?php echo $reftabla; ?>" />
    </div>
</form>