/* 
 * @description funciones del panel de administración
 * 
 * @autor Javier Trigueros Martinez de los Huertos
 * 
 * @copyright Arquematics Nov 2012 GPL
*/
(function ($) {

  $.fn.panel = function() {
  
    // there's no need to do $(this) because
    // "this" is already a jquery object

    // $(this) would be the same as $($('#element'));
        
    $("#open").click(function(){
                $("#admin-panel").slideDown("slow");
            });

    // Collapse Panel
    $("#close").click(function(){
         $("#admin-panel").slideUp("slow");  
     });
    // Switch buttons from "Log In | Register" to "Close Panel" on click
     $("#open-admin-panel a").click(function () {
                $("#open-admin-panel a").toggle();
    }); 

    return this;
  };
  
})(jQuery);