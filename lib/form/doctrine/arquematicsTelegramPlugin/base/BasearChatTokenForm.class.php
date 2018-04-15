<?php

/**
 * arChatToken form base class.
 *
 * @method arChatToken getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearChatTokenForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'session_history_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('arSessionHistory'), 'add_empty' => true)),
      'is_active'          => new sfWidgetFormInputCheckbox(),
      'token'              => new sfWidgetFormInputText(),
      'expires_at'         => new sfWidgetFormDateTime(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'session_history_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('arSessionHistory'), 'required' => false)),
      'is_active'          => new sfValidatorBoolean(array('required' => false)),
      'token'              => new sfValidatorString(array('max_length' => 128)),
      'expires_at'         => new sfValidatorDateTime(),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'arChatToken', 'column' => array('token')))
    );

    $this->widgetSchema->setNameFormat('ar_chat_token[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arChatToken';
  }

}
