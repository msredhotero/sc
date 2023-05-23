(function(factory) {
    if (typeof define === 'function' && define.amd) {
      // AMD. Register as an anonymous module.
      define(['jquery'], factory);
    } else if (typeof exports === 'object') {
      // Node/CommonJS
      factory(require('jquery'));
    } else {
      // Browser globals
      factory(jQuery);
    }
  }(function($) {

    $("#lgmNuevo").on('shown.bs.modal', function(){
        $('#lgmNuevo #sign_in :input:first').focus();
    });

    $('#btnBuscarPatente').keypress(function(e) {

      var altura = $('#altura').val();
   
      var alturaURL = '';
      if (altura == '') {
         altura = '../';
         alturaURL = '';
      } else {
         altura = '../../';
         alturaURL = '../';
      }
   
      var code = (e.keyCode ? e.keyCode : e.which);
      if(code==13){
   
         $(location).attr('href', alturaURL + 'busqueda/index.php?busqueda='+$(this).val());
      }
   });

}));