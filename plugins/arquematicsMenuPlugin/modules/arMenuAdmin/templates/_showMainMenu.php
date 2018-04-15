<?php $isCMSAdmin = isset($isCMSAdmin) ? $sf_data->getRaw('isCMSAdmin') : false; ?> 
<?php $aUserProfile = $sf_user->getGuardUser()->getProfile() ?>
    <?php if ($isCMSAdmin): ?>
        <?php include_partial('arMenuAdmin/sidrAdminMenu') ?>
    <?php endif; ?>

    <li class="dropdown">
       <a href="#" class="menu-content dropdown-toggle user-menu" data-toggle="dropdown">
            <?php include_partial('arProfile/imageSmall', 
                                                        array(
                                                            'image' => $aUserProfile->getProfileImage(),
                                                            'class' => 'app-menu'
                                                        )); ?> 
            <span>
              <span class="user-first-last-text"><?php echo $aUserProfile->getFirstLast() ?></span>
              <b class="caret"></b>
            </span>
       </a>
       <ul class="dropdown-menu">
           <?php include_partial('arMenuAdmin/mainMenu', array('isCMSAdmin' => $isCMSAdmin,'aUserProfile' => $aUserProfile)) ?>  
       </ul>
        
    </li>