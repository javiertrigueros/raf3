<?php

/**
 * PluginarLavernaFile form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarLavernaFileForm extends BasearLavernaFileForm
{
  public function setup()
  {
      parent::setup();
      
      unset(
        $this['user_id'],
        $this['laverna_id'],
        $this['created_at'],
        $this['updated_at']
      );
      
      $this->widgetSchema['id']      = new sfWidgetFormInputHidden();
      $this->validatorSchema['id']   = new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false));
      
      $this->widgetSchema['type']     = new sfWidgetFormInputHidden();
      $this->validatorSchema['type']  = new sfValidatorString(array('required' => true));
      
      $this->widgetSchema['src']     = new sfWidgetFormInputHidden();
      $this->validatorSchema['src']  = new sfValidatorString(array('required' => true));

      $this->widgetSchema['h']     = new sfWidgetFormInputHidden();
      $this->validatorSchema['h']  = new sfValidatorString(array('required' => true));

      $this->widgetSchema['w']     = new sfWidgetFormInputHidden();
      $this->validatorSchema['w']  = new sfValidatorString(array('required' => true));

      
      $this->widgetSchema['guid']          = new sfWidgetFormInputHidden();
      $this->validatorSchema['guid']       = new sfValidatorString(array('required' => true));
      
      $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
      );
     
      $this->widgetSchema->setNameFormat('file[%s]');
     
  }
  
  public function checkCallback($validator, $values)
  {
      $this->checkGuid($validator, $values);
  
      return $values;
  }
  
  /**
   * no tiene que existir la guid de fichero
   * 
   * @param type $validator
   * @param type $values
   * @throws sfValidatorError
   */
  public function checkGuid($validator, $values)
  {
    $aNote = $this->getOption('aNote');
    
    if ($aNote && is_object($aNote) && Doctrine_Core::getTable('arLavernaFile')
                ->hasDocFile($aNote->getId(), $values['guid']))
    {
       throw new sfValidatorError($validator, 'Error guid note already created'); 
    }
    else if (!$aNote && Doctrine_Core::getTable('arLavernaFile')
                            ->hasFileGui($values['guid']))
    {
      throw new sfValidatorError($validator, 'Error guid note already created');
    }
    
  }
  
  protected function doSave($con = null)
  {
       $aUserProfile =  $this->getOption('aUserProfile');
       $aNote = $this->getOption('aNote');
       
       parent::doSave($con); 
       
       $object = $this->getObject();
       
       $object->setUserId($aUserProfile->getId());
       
       if ($aNote && is_object($aNote))
       {
            $object->setLavernaId($aNote->getId());
       }
       
       $object->save($con);
       
       return $object;
   }
  
}
