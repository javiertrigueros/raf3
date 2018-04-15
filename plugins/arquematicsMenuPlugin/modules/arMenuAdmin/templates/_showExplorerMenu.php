<?php $isCMSAdmin = isset($isCMSAdmin) ? $sf_data->getRaw('isCMSAdmin') : false; ?> 
<?php $documentsTypeEnabled = isset($documentsTypeEnabled) ? $sf_data->getRaw('documentsTypeEnabled') : array(); ?> 
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?php echo __('Documents', null, 'arquematics') ?>
    </a>
    <ul class="dropdown-menu">
         <?php foreach ($documentsTypeEnabled as $documentType): ?>
         <li>
            <a title="<?php echo __($documentType['name'], null, 'documents') ?>" href="<?php echo url_for('@laverna_doc').'#/'.$documentType['name'] ?>" >
                <i class="<?php echo $documentType['classInverse'] ?>"></i>
                <span class="mm-text"><?php echo __($documentType['name'], null, 'documents') ?></span>
            </a>
         </li>    
         <?php endforeach; ?>
    </ul>
</li>