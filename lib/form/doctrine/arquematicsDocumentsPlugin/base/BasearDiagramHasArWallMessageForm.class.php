<?php

/**
 * arDiagramHasArWallMessage form base class.
 *
 * @method arDiagramHasArWallMessage getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearDiagramHasArWallMessageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'diagram_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Diagram'), 'add_empty' => true)),
      'user_id'         => new sfWidgetFormInputText(),
      'wall_message_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'diagram_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Diagram'), 'required' => false)),
      'user_id'         => new sfValidatorInteger(array('required' => false)),
      'wall_message_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ar_diagram_has_ar_wall_message[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDiagramHasArWallMessage';
  }

}
