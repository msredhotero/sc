<?php

spl_autoload_register(function($clase){
    include_once "" .$clase. ".php";        
  });



$Constructor = new Construir('tbtipovehiculo');

echo $Constructor->variablesTodas();
echo '<br>';
echo $Constructor->methods();
echo '<br>';
echo $Constructor->GT();

/*
$empresas = new EmpresaAfiliada();

$empresas->buscarPorId(1);

var_dump($empresas->devolverArray());
*/
?>