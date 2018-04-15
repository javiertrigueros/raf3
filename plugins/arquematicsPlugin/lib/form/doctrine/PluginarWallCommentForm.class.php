<?php

/**
 * PluginarWallComment form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarWallCommentForm extends BasearWallCommentForm
{
    public function setup()
     {
        sfProjectConfiguration::getActive()
                ->loadHelpers(array('I18N'));
        
        $this->widgetSchema['comment'] = new sfWidgetFormTextarea(array(),array('autocomplete'=>'off'));
        
        if (sfConfig::get('app_arquematics_encrypt'))
        {
          $this->validatorSchema['comment'] = new sfValidatorEncryptContent(array('required' => true));    
        }
        else
        {
          $this->validatorSchema['comment'] = new sfValidatorString(array('required' => true));  
        }
        
        $this->validatorSchema['comment']
                ->setMessage('required', __('Required', array(), 'arquematics'));
        
        $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
        $this->validatorSchema['user_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => true));
        $this->validatorSchema['user_id']
                ->setMessage('required', __('Required', array(), 'arquematics'));
        
        $this->widgetSchema['wall_message_id'] = new sfWidgetFormInputHidden();
        $this->validatorSchema['wall_message_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Messages'), 'required' => false));
        $this->validatorSchema['wall_message_id']
                ->setMessage('required', __('Required', array(), 'arquematics'));
                
        $this->widgetSchema->setNameFormat('wallComment[%s]');
     }
     
     protected function doSave($con = null)
     {
        
        if (sfConfig::get('app_arquematics_encrypt'))
        {
           try
           { 
      
            $commentsEnc = $this->values['comment'];
            
            $this->values['comment'] = '';

            parent::doSave($con);
            
            $object = $this->getObject();
            
            $contentEncryptArr = json_decode($commentsEnc, true);
            
            foreach ($contentEncryptArr as $key => $data)
            {
                
                $commentEncContent = new arWallCommentEnc();
            
                $commentEncContent->setUserId($key);                 
                $commentEncContent->setWallCommentId($object->getId());
                $commentEncContent->setContent($data);
            
                $commentEncContent->save($con);
                
                //contenido encriptado para el usuario activo
                if ($this->values['user_id'] == $key)
                {
                    $object->EncContent = $commentEncContent;
                }
               
             }
             
             return $object;
           }
           catch (Exception $e)
           {
                return null;
           }
        }
        else return parent::doSave($con);
     }
}
