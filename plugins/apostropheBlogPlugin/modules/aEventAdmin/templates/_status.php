<?php

  // Compatible with sf_escaping_strategy: true

  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;

  $status = $a_event->getStatus(); 

?>

<?php echo __($status,null,'blog'); ?>
