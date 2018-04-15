<?php if ((isset($error)) && (is_object($error))): ?>
<?php echo $error; ?>
<?php else: ?>
<?php readfile($pathAndFile); exit(0) ?>
<?php endif; ?>