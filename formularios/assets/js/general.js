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

}));