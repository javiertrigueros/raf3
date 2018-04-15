<?php foreach($message->Links as $link): ?>
 <?php include_partial("arLink/wallLink", array('link' => $link)) ?>
<?php endforeach; ?>