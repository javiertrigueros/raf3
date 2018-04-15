<?php $a_category = isset($a_category) ? $sf_data->getRaw('a_category') : null; ?>
<td>
  <ul class="a-ui a-admin-td-actions">
    <?php echo linkToEditCat($a_category) ?>
    <?php echo linkToDeleteCat($a_category) ?>
  </ul>
</td>
