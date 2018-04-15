
<script type="text/javascript">

$(document).ready(function()
 {
     
    //arreglo para que el modal bootstrap salga centrado
    $('body').on('show', '.modal', function(){
        $(this).css({'margin-top':($(window).height()-$(this).height())/2,'top':'0'});
    });
    
 });
</script>