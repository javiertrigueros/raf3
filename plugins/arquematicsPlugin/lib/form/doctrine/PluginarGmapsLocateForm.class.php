<?php

/**
 * PluginarGmapsLocate form.
 *
 * @package    arquematicsPlugin
 * @subpackage form
 * @author     Javier Trigueros Martinez de los Huertos, Arquematics
 * @version    0.1
 */
abstract class PluginarGmapsLocateForm extends BasearGmapsLocateForm
{
    public function setup()
    {
       parent::setup();
        
        sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
        
       unset(
            $this['id'],
            $this['created_at'],
            $this['updated_at']);
       
       $showFormatedAddress = $this->getOption('showFormatedAddress');
       
       if (!$showFormatedAddress)
       {
         $this->widgetSchema['formated_address'] = new sfWidgetFormInputHidden();  
       }
       else {
          $this->widgetSchema['formated_address'] = new sfWidgetFormInputText();   
       }
       
       $this->widgetSchema['hash'] = new sfWidgetFormInputHidden();
    
       if (sfConfig::get('app_arquematics_encrypt'))
       {
        $this->validatorSchema['formated_address'] = new sfValidatorEncryptContent(array('required' => true));
        $this->validatorSchema['hash'] = new sfValidatorString(array('required' => true));
       }
       else
       {
        $this->validatorSchema['formated_address'] = new sfValidatorString(array('required' => true));
        $this->validatorSchema['hash'] = new sfValidatorString(array('required' => true));
       }
    
       //truco para quitar la validación setPostValidator
       $this->validatorSchema->setPostValidator(new sfValidatorPass());
    
       $this->widgetSchema->setNameFormat('locate[%s]');

       $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
   }
   
   protected function doSaveRelatedProfile($con = null)
   {
       $profileRelated = $this->getOption('profileRelated');
     
       if ($profileRelated && is_object($profileRelated))
       {
           $arGmapsLocate = $this->getObject();
           
           $arUsersGmapsLocate = new arUsersGmapsLocate();
           
           $arUsersGmapsLocate->setProfileId($profileRelated->getId());
           $arUsersGmapsLocate->setLocateId($arGmapsLocate->getId());
                   
           $arUsersGmapsLocate->save($con);
       }
   }
   
   protected function doSave($con = null)
   {
       
        if (sfConfig::get('app_arquematics_encrypt'))
        {
           //try
           //{ 
      
            $commentsEnc = $this->values['formated_address'];
            
            $this->values['formated_address'] = '';
            
            $object = $this->getObject();
            
            //una vez guardado no se modifica nunca
            if ($object->isNew())
            {
              parent::doSave($con);  
            }
            
            
            
            $contentEncryptArr = json_decode($commentsEnc, true);
            
            if ($contentEncryptArr && (count($contentEncryptArr) > 0))
            {
                $encodedIds = ($object && is_object($object))?$object->getUserEncodedIds():array();
                //arreglo cuando solo es un elemento
                if ($encodedIds && is_numeric($encodedIds)) 
                {
                  $encodedIds = array($encodedIds); 
                }
                
                foreach ($contentEncryptArr as $key => $data)
                {
                    if (!in_array($key, $encodedIds))
                    {
                       $arGmapsLocateEnc = new arGmapsLocateEnc();
            
                        $arGmapsLocateEnc->setUserId($key);                 
                        $arGmapsLocateEnc->setLocateId($object->getId());
                        $arGmapsLocateEnc->setContent($data);
            
                        $arGmapsLocateEnc->save($con); 
                    }
                      
                }
               
            }
            
            $this->doSaveRelatedProfile($con);
             
            return $object;
           //}
           //catch (Exception $e){return null;}
     }
    else 
    {
      $object = $this->getObject();

      //una vez guardado no se modifica nunca
      if ($object->isNew()){
        parent::doSave($con);  
      }
 
      $this->doSaveRelatedProfile($con);
      
      return $object;
    }
    
  }
}