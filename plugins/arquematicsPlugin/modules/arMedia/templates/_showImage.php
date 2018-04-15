<?php $embedImage = isset($embedImage) ? $sf_data->getRaw('embedImage') : false; ?>

<?php if ($embedImage): ?>
<?php echo $embedImage; ?>
<?php endif; ?>

