<?php $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null; ?>
<td>
  <ul class="a-ui a-admin-td-actions">
    <?php echo linkToEditEvent($a_event) ?>
    <?php echo linkToDeleteEvent($a_event) ?>
  </ul>
</td>
