<?php

/**
 * arDropFile form base class.
 *
 * @method arDropFile getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearDropFileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'src'             => new sfWidgetFormTextarea(),
      'name'            => new sfWidgetFormTextarea(),
      'type'            => new sfWidgetFormInputText(),
      'guid'            => new sfWidgetFormInputText(),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'wall_message_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'add_empty' => true)),
      'ready'           => new sfWidgetFormInputText(),
      'persistence'     => new sfWidgetFormChoice(array('choices' => array('small' => 'small', 'med' => 'med', 'big' => 'big'))),
      'size'            => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'slug'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'src'             => new sfValidatorString(array('required' => false)),
      'name'            => new sfValidatorString(array('required' => false)),
      'type'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'guid'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'wall_message_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'required' => false)),
      'ready'           => new sfValidatorInteger(array('required' => false)),
      'persistence'     => new sfValidatorChoice(array('choices' => array(0 => 'small', 1 => 'med', 2 => 'big'), 'required' => false)),
      'size'            => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'arDropFile', 'column' => array('guid'))),
        new sfValidatorDoctrineUnique(array('model' => 'arDropFile', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('ar_drop_file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDropFile';
  }

}
