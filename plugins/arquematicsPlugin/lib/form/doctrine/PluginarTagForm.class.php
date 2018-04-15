<?php

/**
 * PluginarTag form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarTagForm extends BasearTagForm
{
    
  public function setup()
  {
    parent::setup();
    
    unset(
      $this['created_at'],
      $this['updated_at']
    );
    
    sfProjectConfiguration::getActive()->loadHelpers(array('I18N'));
        
    $this->widgetSchema['name'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['hash'] = new sfWidgetFormInputHidden();
    
    if (sfConfig::get('app_arquematics_encrypt'))
    {
        $this->validatorSchema['name'] = new sfValidatorEncryptContent(array('required' => true));
        $this->validatorSchema['hash'] = new sfValidatorString(array('required' => true));
    }
    else
    {
        $this->validatorSchema['name'] = new sfValidatorString(array('required' => true));
        $this->validatorSchema['hash'] = new sfValidatorString(array('required' => true));
    }
    
    //truco para quitar la validación setPostValidator
    $this->validatorSchema->setPostValidator(new sfValidatorPass());
    
    $this->widgetSchema->setNameFormat('ar_tag[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
  
   protected function doSave($con = null)
   {
        if (sfConfig::get('app_arquematics_encrypt'))
        {
           try
           { 
      
            $commentsEnc = $this->values['name'];
            
            $this->values['name'] = '';

            $ret = parent::doSave($con);
            
            $object = $this->getObject();
            
            $contentEncryptArr = json_decode($commentsEnc, true);
            
            foreach ($contentEncryptArr as $key => $data)
            {
                
                $arTagEnc = new arTagEnc();
            
                $arTagEnc->setUserId($key);                 
                $arTagEnc->setTagId($object->getId());
                $arTagEnc->setContent($data);
            
                $arTagEnc->save($con);  
               
             }
             
             return $ret;
            
           }
           catch (Exception $e){return null;}
     }
    else 
    {
      return parent::doSave($con); 
    }
    
  }
}
