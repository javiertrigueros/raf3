<?php

/**
 * arWallLink form base class.
 *
 * @method arWallLink getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearWallLinkForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'has_thumb'       => new sfWidgetFormInputCheckbox(),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'wall_message_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'add_empty' => true)),
      'oembedtype'      => new sfWidgetFormInputText(),
      'oembed'          => new sfWidgetFormTextarea(),
      'title'           => new sfWidgetFormTextarea(),
      'thumb'           => new sfWidgetFormTextarea(),
      'description'     => new sfWidgetFormTextarea(),
      'provider'        => new sfWidgetFormTextarea(),
      'url'             => new sfWidgetFormTextarea(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'has_thumb'       => new sfValidatorBoolean(array('required' => false)),
      'user_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'wall_message_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'required' => false)),
      'oembedtype'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'oembed'          => new sfValidatorString(array('required' => false)),
      'title'           => new sfValidatorString(array('required' => false)),
      'thumb'           => new sfValidatorString(array('required' => false)),
      'description'     => new sfValidatorString(array('required' => false)),
      'provider'        => new sfValidatorString(array('required' => false)),
      'url'             => new sfValidatorString(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ar_wall_link[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arWallLink';
  }

}
