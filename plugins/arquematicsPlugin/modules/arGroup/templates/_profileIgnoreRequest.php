<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>
<?php $profile = isset($profile) ? $sf_data->getRaw('profile') : false; ?> 
<div class="row-fluid user">
      <div class="user-content">
        <a class="user-img-link" href="<?php echo url_for('@user_profile?username='.$profile->getUsername()) ?>">
            <?php //include_partial('arProfile/imageSmall', array('image' => $profile->getProfileImage(),'class' => "user-thumbnail img-extra")) ?>
            <?php include_partial('arProfile/imageNormal', 
                        array('image' => $profile->getProfileImage(),
                              'class' => "user-thumbnail")) ?>
        </a>
        <div class="user-content-text">
           <?php echo link_to($profile->getFirstLast(),'user_profile',$profile,array('class' => 'user-link fullname')) ?>
           <p class="bio "><?php echo $profile->getDescription(); ?></p>   
        </div>
          
        <?php if ($profile->canAddUser($aUserProfile->getId())): ?>
          <div class="user-content-buttons">
            <button data-loading-text="<?php echo __("Accepting",array(),'arquematics') ?>" data-friend-id="<?php echo $profile->getId(); ?>"  class="btn btn-primary btn-danger accept"><?php echo __("Accept request",array(),'profile') ?></button>    
          </div>
        <?php endif; ?>  
      </div>
</div>