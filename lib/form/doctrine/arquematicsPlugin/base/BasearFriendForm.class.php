<?php

/**
 * arFriend form base class.
 *
 * @method arFriend getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearFriendForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'profile_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserP'), 'add_empty' => true)),
      'friend_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserF'), 'add_empty' => true)),
      'request_id' => new sfWidgetFormInputText(),
      'is_accept'  => new sfWidgetFormInputCheckbox(),
      'is_ignore'  => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'profile_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserP'), 'required' => false)),
      'friend_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserF'), 'required' => false)),
      'request_id' => new sfValidatorInteger(array('required' => false)),
      'is_accept'  => new sfValidatorBoolean(array('required' => false)),
      'is_ignore'  => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ar_friend[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arFriend';
  }

}
