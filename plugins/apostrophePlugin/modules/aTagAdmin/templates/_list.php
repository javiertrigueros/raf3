<div class="a-admin-list">
  <?php if (!$pager->getNbResults()): ?>
    <h3 class="a-admin-title"><?php echo __('No results', array(), 'apostrophe') ?></h3>
  <?php else: ?>
    <table cellspacing="0" class="a-admin-list-table">
      <thead>
        <tr>
					          	<th id="a-admin-list-batch-actions"><input id="a-admin-list-batch-checkbox-toggle" class="a-admin-list-batch-checkbox-toggle a-checkbox" type="checkbox"/></th>
					          	<?php include_partial('aTagAdmin/list_th_tabular', array('sort' => $sort, 'helper' => $helper)) ?>
					        </tr>
      </thead>
      <tfoot>
        <tr>
          <th colspan="6">
						<h6 class="a-admin-list-results">
	            <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'apostrophe') ?>
	            <?php if ($pager->haveToPaginate()): ?>
	              <?php // echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'apostrophe') ?>
	            <?php endif; ?>
						</h6>
            <?php if ($pager->haveToPaginate()): ?>
              <?php include_partial('aTagAdmin/pagination', array('pager' => $pager)) ?>
            <?php endif; ?>	
          </th>
        </tr>
      </tfoot>
      <tbody>
        <?php $n=1; $total = $pager->getNbResults(); foreach ($pager->getResults() as $i => $tag): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="a-admin-row <?php echo $odd ?> <?php echo ($n == $total)? 'last':'' ?> <?php echo ($n == 1)? 'first':'' ?>">
						            	<?php include_partial('aTagAdmin/list_td_batch_actions', array('tag' => $tag, 'helper' => $helper)) ?>
						            	<?php include_partial('aTagAdmin/list_td_tabular', array('tag' => $tag, 'helper' => $helper)) ?>
						          </tr>
        <?php $n++; endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php a_js_call('apostrophe.enhanceAdmin()') ?>