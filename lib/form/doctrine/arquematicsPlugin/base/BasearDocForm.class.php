<?php

/**
 * arDoc form base class.
 *
 * @method arDoc getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearDocForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'title'       => new sfWidgetFormInputText(),
      'group_id'    => new sfWidgetFormInputText(),
      'pad_write'   => new sfWidgetFormInputText(),
      'pad_read'    => new sfWidgetFormInputText(),
      'pass'        => new sfWidgetFormInputText(),
      'revision'    => new sfWidgetFormInputText(),
      'user_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Docs'), 'add_empty' => true)),
      'doc_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DocType'), 'add_empty' => true)),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'slug'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'group_id'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pad_write'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pad_read'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pass'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'revision'    => new sfValidatorInteger(array('required' => false)),
      'user_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Docs'), 'required' => false)),
      'doc_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DocType'), 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
      'slug'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'arDoc', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('ar_doc[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDoc';
  }

}
