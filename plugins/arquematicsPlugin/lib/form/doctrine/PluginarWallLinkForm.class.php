<?php

/**
 * PluginarWallLink form.
 *
 * @package    ArquematicsPlugin
 * @subpackage form
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarWallLinkForm extends BasearWallLinkForm
{
  public function setup()
  {
     
      if (sfConfig::get('app_arquematics_encrypt'))
      {
          $this->validatorSchema['pass'] = new sfValidatorEncryptContent(array('required' => true));    
          $this->widgetSchema['pass'] = new sfWidgetFormInputHidden();
      }

  
      $this->validatorSchema['description'] = new sfValidatorString(array('required' => true));    
      $this->widgetSchema['description'] = new sfWidgetFormInputHidden();

      
      $this->validatorSchema['oembed'] = new sfValidatorString(array('required' => true));    
      $this->widgetSchema['oembed'] = new sfWidgetFormInputHidden();

      $this->validatorSchema['title'] = new sfValidatorString(array('required' => true));    
      $this->widgetSchema['title'] = new sfWidgetFormInputHidden();

      $this->validatorSchema['thumb'] = new sfValidatorString(array('required' => true));    
      $this->widgetSchema['thumb'] = new sfWidgetFormInputHidden();
      
      $this->validatorSchema['description'] = new sfValidatorString(array('required' => true));    
      $this->widgetSchema['description'] = new sfWidgetFormInputHidden();

      $this->validatorSchema['provider'] = new sfValidatorString(array('required' => true));    
      $this->widgetSchema['provider'] = new sfWidgetFormInputHidden();
      
      $this->validatorSchema['url'] = new sfValidatorString(array('required' => true));    
      $this->widgetSchema['url'] = new sfWidgetFormInputHidden();
      
      $this->validatorSchema['oembedtype'] = new sfValidatorString(array('required' => true));    
      $this->widgetSchema['oembedtype'] = new sfWidgetFormInputHidden();
      

      $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
      );
      
      $this->widgetSchema->setNameFormat('wallLink[%s]');
  }
  
  public function checkCallback($validator, $values)
  {
      $this->checkOmbedtype($validator, $values);
      return $values;
  }


  /**
   * mira si el campo oembedtype es valido
   * 
   * @param array $validator
   * @param array $values
   * @throws sfValidatorError
   */
  public function checkOmbedtype($validator, $values)
  {
    /*
    photo This is a static viewable image.
    video This is a playable video.
    rich  This is rich HTML that may contain images and videos.
    link   This is a general embed that may not contain HTML.
    */
    if (isset($values['oembedtype'])
        && !(($values['oembedtype'] === 'photo')
            || ($values['oembedtype'] === 'video')
            || ($values['oembedtype'] === 'rich')
            || ($values['oembedtype'] === 'link')))
    {
      throw new sfValidatorError($validator, 'Error checkOmbedtype'.$values['oembedtype']);
    } 
  }

  protected function doSave($con = null)
  {
    if (sfConfig::get('app_arquematics_encrypt'))
    {
      try
        { 
          $linkEnc = $this->values['pass'];

          $object = $this->getObject();
          //pone el usuario que ha creado el enlace
          $userProfile = $this->getOption('aUserProfile'); 
          $object->setUserId( $userProfile->getId());

          parent::doSave($con);
            
          $contentEncryptArr = json_decode($linkEnc, true);
            
          foreach ($contentEncryptArr as $key => $data)
          {
                
                $linkEncContent = new arWallLinkEnc();
            
                $linkEncContent->setUserId($key);                 
                $linkEncContent->setWallLinkId($object->getId());
                $linkEncContent->setContent($data);
            
                $linkEncContent->save($con);
                
                //contenido encriptado para el usuario activo
                if ($userProfile->getId() == $key)
                {
                    $object->EncContent = $linkEncContent;
                }
          }
            return $object;
          }
          catch (Exception $e)
          {
            return null;
          }
        }
        else {
          $userProfile = $this->getOption('aUserProfile');
          $object = $this->getObject();
          //pone el usuario que ha creado el enlace 
          $object->setUserId( $userProfile->getId());

          parent::doSave($con);

          return $object;
        }

          
     }
}
