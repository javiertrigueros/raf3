<script type="text/javascript">
$(document).ready(function()
{
            $("#<?php echo $nameId ?>").datetimepicker({
                autoclose: true,
                language: "<?php echo $culture ?>",
                initialDate: $("#<?php echo $nameId ?>").data('now'),
                pickerPosition: "bottom-right"});
});
</script>