<?php $data = isset($data) ? $sf_data->getRaw('data') : null; ?>
<script type="text/javascript">
     app.value('globals', <?php echo json_encode($data,JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>);
</script>