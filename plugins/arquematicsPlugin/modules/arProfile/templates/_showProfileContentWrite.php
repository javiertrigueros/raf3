<div class="profile-card-inner">
    
    <div class="profile-write-content">
                
    <h1 class="profile-name">
        <?php echo $aRouteUserProfile->getFirstLast(); ?>
    </h1>
                     
    <ul id="profile-info-items" class="profile-info-items">
        <li class="description write-item">
            <?php echo $aRouteUserProfile->getDescription() ?>
        </li>

        <?php /*include_component('arMap', 'viewEdit', 
                                        array(
                                            'aUser' => $aUser,
                                            'aLocate' => $aUserProfile->getCurrentLocate(),
                                              'tag' => 'li',
                                              'class' => 'write-item geolocation-icon'))*/ ?>

        <?php /*include_partial('arProfile/showGroups', array(
                                    'aUser' => $aRouteUserProfile,
                                    'class' => 'write-item groups')); */ ?>
    </ul>          
   </div>

            
    <div class="profile-read-content">
          <?php /*include_partial('arProfile/showProfileContent', 
                            array('downloadPrivateKey' => $downloadPrivateKey,
                                  'aUserProfile' => $aRouteUserProfile));*/ ?>       
    </div>
 </div>