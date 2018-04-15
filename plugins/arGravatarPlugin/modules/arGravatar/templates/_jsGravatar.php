<?php $id = isset($id) ? $sf_data->getRaw('id') : false; ?>
<?php $defaultQueryImg = isset($defaultQueryImg) ? $sf_data->getRaw('defaultQueryImg') : false; ?>
<script type="text/javascript">
$(document).ready(function(){
$('#<?php echo $id; ?>').error(function(){
    $(this).attr('src', '<?php echo $defaultQueryImg; ?>');
  });
});
</script>