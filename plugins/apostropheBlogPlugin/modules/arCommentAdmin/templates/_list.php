<div class="a-admin-list">
  <?php if (!$pager->getNbResults()): ?>
    <h3 class="a-admin-title"><?php echo __('No results', array(), 'apostrophe') ?></h3>
  <?php else: ?>
    <table cellspacing="0" class="a-admin-list-table">
      <thead>
        <tr>
            <th id="a-admin-list-batch-actions">
                <input id="a-admin-list-batch-checkbox-toggle" class="a-admin-list-batch-checkbox-toggle a-checkbox" type="checkbox"/></th>
                <?php include_partial('arCommentAdmin/list_th_tabular', array('sort' => $sort)) ?>
                <th id="a-admin-list-th-actions"><?php echo __('Actions', array(), 'apostrophe') ?></</tr>
      </thead>
      <?php if ($pager->haveToPaginate()): ?>
      <tfoot>
        <tr>
          <th colspan="7">
              <?php include_partial('arCommentAdmin/pagination', array('pager' => $pager)) ?>
          </th>
        </tr>
      </tfoot>
      <?php endif; ?>
      <tbody>
        <?php $n=1; $total = count($pager->getResults()); foreach ($pager->getResults() as $i => $ar_comment): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="a-admin-row <?php echo $odd ?> <?php echo ($n == $total)? 'last':'' ?> <?php echo ($n == 1)? 'first':'' ?>">
                <?php include_partial('arCommentAdmin/list_td_batch_actions', array('ar_comment' => $ar_comment, 'helper' => $helper)) ?>
		<?php include_partial('arCommentAdmin/list_td_tabular', array('ar_comment' => $ar_comment)) ?>
		<?php include_partial('arCommentAdmin/list_td_actions', array('ar_comment' => $ar_comment, 'helper' => $helper)) ?>
          </tr>
        <?php $n++; endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>