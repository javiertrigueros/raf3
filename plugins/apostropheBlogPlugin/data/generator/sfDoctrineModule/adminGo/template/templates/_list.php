<div class="a-admin-list">
  [?php if (!$pager->getNbResults()): ?]
    <p>[?php echo __('No result', array(), 'apostrophe') ?]</p>
  [?php else: ?]
    <table cellspacing="0" class="a-admin-list-table">
      <thead>
        <tr>
					<?php if ($this->configuration->getValue('list.batch_actions')): ?>
          	<th id="a-admin-list-batch-actions"><input id="a-admin-list-batch-checkbox-toggle" class="a-admin-list-batch-checkbox-toggle a-checkbox" type="checkbox"/></th>
					<?php endif; ?>
          	[?php include_partial('<?php echo $this->getModuleName() ?>/list_th_<?php echo $this->configuration->getValue('list.layout') ?>', array('sort' => $sort, 'form' => $form, 'filters' => $form)) ?]
					<?php if ($this->configuration->getValue('list.object_actions')): ?>
          	<th id="a-admin-list-th-actions">[?php echo __('Actions', array(), 'apostrophe') ?]</th>
					<?php endif; ?>
        </tr>
      </thead>
      [?php if ($pager->haveToPaginate()): ?]
      <tfoot>
        <tr>
          <th colspan="<?php echo count($this->configuration->getValue('list.display')) + ($this->configuration->getValue('list.object_actions') ? 1 : 0) + ($this->configuration->getValue('list.batch_actions') ? 1 : 0) ?>">
						<h6 class="a-admin-list-results">
	            [?php //echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'a-admin') ?]
	            [?php if ($pager->haveToPaginate()): ?]
	              [?php // echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'apostrophe') ?]
	            [?php endif; ?]
						</h6>
            
              [?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager)) ?]
            	
          </th>
        </tr>
      </tfoot>
      [?php endif; ?]
      <tbody>
        [?php $n=1; $total = count($pager->getResults()); foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]
          <tr class="a-admin-row [?php echo $odd ?] [?php echo ($n == $total)? 'last':'' ?] [?php echo ($n == 1)? 'first':'' ?]">
						<?php if ($this->configuration->getValue('list.batch_actions')): ?>
            	[?php include_partial('<?php echo $this->getModuleName() ?>/list_td_batch_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
						<?php endif; ?>
            	[?php include_partial('<?php echo $this->getModuleName() ?>/list_td_<?php echo $this->configuration->getValue('list.layout') ?>', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>)) ?]
						<?php if ($this->configuration->getValue('list.object_actions')): ?>
            	[?php include_partial('<?php echo $this->getModuleName() ?>/list_td_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
						<?php endif; ?>
          </tr>
        [?php $n++; endforeach; ?]
      </tbody>
    </table>
  [?php endif; ?]
</div>