<?php $urlBack = isset($urlBack) ? $sf_data->getRaw('urlBack') : false; ?>
<?php $textBack = isset($textBack) ? $sf_data->getRaw('textBack') : ''; ?>

<?php if ($urlBack): ?>
<a id="takeBack" class="navbar-brand" href="<?php echo $urlBack ?>">
    <?php echo $textBack ?>
</a>
<?php endif; ?>