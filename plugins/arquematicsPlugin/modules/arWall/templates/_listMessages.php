<?php $has_messages = isset($has_messages) ? $sf_data->getRaw('has_messages') : false; ?>
<?php $initPage = isset($initPage) ? $sf_data->getRaw('initPage') : false; ?> 
<?php $pager = isset($pager) ? $sf_data->getRaw('pager') : false; ?>
<?php $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : false; ?>
<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>
<?php $hasProfileFilterAccepted = isset($hasProfileFilterAccepted)? $sf_data->getRaw('hasProfileFilterAccepted') : false; ?>
<?php $currentPage = isset($currentPage)? $sf_data->getRaw('currentPage') : 1; ?>
<?php if ($has_messages): ?>
    <div class="page">
    <?php foreach($pager->getResults() as $m): ?>
        <?php include_partial("arWall/message", 
                                array('message' => $m,
                                    'aUserProfileFilter' => $aUserProfileFilter,
                                    'authUser' => $authUser)) ?>
    <?php endforeach; ?>
    </div>
<?php elseif ($hasProfileFilterAccepted &&  ($currentPage == 1)): ?>
    <div class="alert alert-info alert-dark">
	<strong><?php echo __('The user %user% has not posted content!', array('%user%' => $aUserProfileFilter->getFirstLast()), 'wall') ?></strong> 
    </div>
<?php elseif (($currentPage == 1) && $aUserProfileFilter): ?>
    <div class="alert alert-dark">
        <strong><?php echo __('The user %user% is not a subscriber!', array('%user%' => $aUserProfileFilter->getFirstLast()), 'wall') ?></strong> <?php echo __("Sorry, you don't have permission to read %user% messages.", array('%user%' => $aUserProfileFilter->getFirstLast()), 'wall'); ?>
    </div>
<?php endif ?>
