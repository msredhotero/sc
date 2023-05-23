<?php


spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});

$Usuarios = new Usuarios($_SESSION['user']->GetEmail(),'');

$camposTablas = explode(',',str_replace('password,','', str_replace('refroles','Rol' ,str_replace('validoemail','valido email',$Usuarios::CAMPOS)))) ;



?>


<div class="col-lg-12 col-md-6 mb-md-0 mb-4">
  <div class="card">
    <div class="card-header pb-0">
      <div class="row">
        <div class="col-lg-6 col-7">
          <h6>Usuarios Cargados</h6>
        </div>
        
      </div>
    </div>
    <div class="card-body px-2 pb-4">
      <div class="table-responsive">
        
        <table id="example" class="table align-items-center mb-0 display " style="width:100%">
          <thead>
            <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nombre</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Apellido</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Activo</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rol</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cargo</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Zona</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
            </tr>
          </thead>
            
        </table>
        
        <div style="margin-bottom: 140px;"></div>
        
      </div>
    </div>
  </div>
</div>


      