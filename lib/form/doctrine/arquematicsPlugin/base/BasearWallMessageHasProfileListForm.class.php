<?php

/**
 * arWallMessageHasProfileList form base class.
 *
 * @method arWallMessageHasProfileList getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearWallMessageHasProfileListForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'wall_message_id' => new sfWidgetFormInputHidden(),
      'profile_list_id' => new sfWidgetFormInputHidden(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'wall_message_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('wall_message_id')), 'empty_value' => $this->getObject()->get('wall_message_id'), 'required' => false)),
      'profile_list_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_list_id')), 'empty_value' => $this->getObject()->get('profile_list_id'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ar_wall_message_has_profile_list[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arWallMessageHasProfileList';
  }

}
