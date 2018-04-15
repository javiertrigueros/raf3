<?php

/**
 * arLavernaFile form base class.
 *
 * @method arLavernaFile getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearLavernaFileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'src'        => new sfWidgetFormTextarea(),
      'type'       => new sfWidgetFormInputText(),
      'w'          => new sfWidgetFormInputText(),
      'h'          => new sfWidgetFormInputText(),
      'guid'       => new sfWidgetFormInputText(),
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'laverna_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LavernaDoc'), 'add_empty' => true)),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'src'        => new sfValidatorString(array('required' => false)),
      'type'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'w'          => new sfValidatorInteger(array('required' => false)),
      'h'          => new sfValidatorInteger(array('required' => false)),
      'guid'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'laverna_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('LavernaDoc'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'arLavernaFile', 'column' => array('guid')))
    );

    $this->widgetSchema->setNameFormat('ar_laverna_file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arLavernaFile';
  }

}
