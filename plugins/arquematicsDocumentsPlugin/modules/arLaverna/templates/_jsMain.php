<?php $toggle = isset($toggle) ? $sf_data->getRaw('toggle') : false; ?>

<?php if ($toggle): ?>
<script type="text/javascript">
	window.PixelAdmin.start([], {mode: 'wall', toggle_cmd: true});
</script>
<?php else: ?>
<script type="text/javascript">
	window.PixelAdmin.start([], {mode: 'wall', toggle_cmd: false});
</script>
<?php endif ?>