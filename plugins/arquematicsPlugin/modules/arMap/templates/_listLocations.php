<?php  $message = isset($message) ? $sf_data->getRaw('message') : false; ?>
<?php  $locations = ($message)? $message->Gmaps: array(); ?>

<?php if (count($locations)): ?>
<div class='locations-container'>
<?php foreach($locations as $locate): ?>
 <?php include_partial("arMap/locate", array('locate' => $locate)) ?>
<?php endforeach; ?>
</div>
<?php endif ?>