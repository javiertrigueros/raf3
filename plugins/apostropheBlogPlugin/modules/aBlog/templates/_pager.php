<?php $pager = $sf_data->getRaw('pager') ?>
<?php $pagerUrl = $sf_data->getRaw('pagerUrl') ?>

<?php if ($pager->haveToPaginate()): ?>
<div class="pagination clearfix">
 <?php include_partial('aPager/next', array('pager' => $pager, 'pagerUrl' => $pagerUrl)) ?>   
</div>
<?php endif ?>


