<?php


class arBlogNewEventForm extends BaseFormDoctrine
{
    
  public function configure()
  {
    parent::configure();
    
   
     $this->setWidget('title', new sfWidgetFormInputText(array(), 
                array('class' => 'span12 no-borders',
                      'label' => __('Event title', array(), 'wall'),
                      'placeholder' => __('Event title', array(), 'wall'),
                      'autocomplete'=>'off')));
    $this->setValidator('title', new sfValidatorString(array('min_length' => 2, 'required' => true)));
    
    $this->widgetSchema['message'] = new sfWidgetFormTextarea(array(), 
                array('class' => 'span12 no-borders',
                      'label' => __('Abstract', array(), 'wall'),
                      'placeholder' => __('Abstract', array(), 'wall'),
                      'autocomplete'=>'off'));
        
        
    if (sfConfig::get('app_arquematics_encrypt'))
    {
       $this->validatorSchema['message'] = new sfValidatorEncryptContent(array('required' => true));    
    }
    else
    {
       $this->validatorSchema['message'] = new sfValidatorString(array('required' => true));  
    }
    $this->validatorSchema['message']
                ->setMessage('required', __('Required', array(), 'arquematics'));
    
    
    $userProfile = $this->getOption('aUserProfile');
       
    $options = array();
        
    if (isset($userProfile) && is_object($userProfile))
    {
        $options =  $userProfile->getAdminListChoices();
    }
        
    $this->widgetSchema['groups'] = new sfWidgetGroupChoice(array(
                    'choices' => $options
            ),array('style' => 'display:none','multiple'=> 'multiple'));
        
       
    $this->validatorSchema['groups'] = new sfValidatorChoiceGroupMultiple(
                            array(
                                    'required' => false,
                                    'max' => sfConfig::get('app_arquematics_plugin_max_list_items', 6),
                                    'choices' => array_keys($options)
                            ),
                            array());
    
    
    
    $this->widgetSchema->setNameFormat('a_new_event[%s]');
  }

  private function saveGroupLists($messageObj, Doctrine_Connection $conn)
  {
      $groupLists = $this->values['groups'];
      if ($groupLists && is_array($groupLists) && (count($groupLists) > 0))
      {
          foreach ($groupLists as $list)
          {
              $arWallMessageHasProfileList = new arWallMessageHasProfileList();
              $arWallMessageHasProfileList->setWallMessageId($messageObj->getId());
              $arWallMessageHasProfileList->setProfileListId($list);
              $arWallMessageHasProfileList->setComments(false);
              $arWallMessageHasProfileList->setAdds(false);
              $arWallMessageHasProfileList->save($conn);
          }
      }
  }

  protected function doSave($conn = null)
  {

      $userProfile = $this->getOption('aUserProfile');
      
      $messageObj = new arWallMessage();
      $messageObj->setMessage('');
      //aplazamos la publicacion hasta que este activo
      //el post o evento
      $messageObj->setIsPublish(false);
      $messageObj->setUserId($userProfile->getId());
      $messageObj->save($conn);

       $blogPost = $this->getObject();
       $blogPost->Author = $this->getOption('authUser');
       $blogPost->setTitle($this->getValue('title'));
       $blogPost->setExcerpt($this->getValue('message'));
       $blogPost->setPublishedAt($messageObj->getCreatedAt());
       $blogPost->save($conn);
                
       $arWallMessageHasBlogItem = new arWallMessageHasBlogItem();
       $arWallMessageHasBlogItem->setABlogItemId($blogPost->getId());
       $arWallMessageHasBlogItem->setWallMessageId($messageObj->getId());
       $arWallMessageHasBlogItem->save($conn);

       if (sfConfig::get('app_arquematics_encrypt'))
      {
            $contentEncrypt = $this->values['message'];
            $this->values['message'] = '';
            
            $contentEncryptArr = json_decode($contentEncrypt, true);
            
            if ($contentEncryptArr && count($contentEncryptArr))
            {
               foreach ($contentEncryptArr as $keyId => $data)
               {
                  $encContent = new arWallMessageEnc();
                  $encContent->setUserId($keyId);
                  $encContent->setWallMessageId($messageObj->getId());
                  $encContent->setContent($data);
                  $encContent->save($conn);
                  
                  
                  $encContent = new arExcerptEnc();
                  $encContent->setUserId($keyId);
                  $encContent->setABlogItemId($blogPost->getId());
                  $encContent->setContent($data);
                  $encContent->save($conn);
                  
                  
                  if ($userProfile->getId() == $keyId)
                  {
                    $this->EncContent = $encContent; 
                  }
               }
            }
      }

      $this->saveGroupLists($messageObj, $conn);

  }
  
  
  public function updateObject($values = null)
  {
    $object = $this->getObject();
    
    if (is_null($values))
    {
      $values = $this->getValues();
    }

    return parent::updateObject($values);
  }

  public function getModelName() {
        return 'aEvent';
  }
}
