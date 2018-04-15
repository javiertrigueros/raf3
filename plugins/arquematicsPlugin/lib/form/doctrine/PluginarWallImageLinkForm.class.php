<?php

/**
 * PluginarWallImageLink form.
 *
 * @package    arquematicsPlugin
 * @subpackage form
 * @author     Arquematics 2012 Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
abstract class PluginarWallImageLinkForm extends BasearWallImageLinkForm
{
  public function setup()
  {
      $this->widgetSchema['type'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['type'] = new sfValidatorChoice(
              array(
                    'required' => true,
                    'choices' => array('photo','video','rich','link')
                  ));
      
      
      $this->widgetSchema['title'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['title'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
      
      $this->widgetSchema['thumb'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['thumb'] = new sfValidatorDomainExtra(array('required' => false,'validated_file_class' => 'sfValidatedLinkFile'));
      
      $this->widgetSchema['oembed_html'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['oembed_html'] = new sfValidatorString(array('required' => false));
      
      $this->widgetSchema['description'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['description'] = new sfValidatorString(array('required' => false));
      
      $this->widgetSchema['provider'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['provider'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
      
      $this->widgetSchema['url'] = new sfWidgetFormInput();
      $this->validatorSchema['url']= new sfValidatorString(array('required' => true));
      
      $this->widgetSchema->setNameFormat('wallLink[%s]');
  }
  
  
}
