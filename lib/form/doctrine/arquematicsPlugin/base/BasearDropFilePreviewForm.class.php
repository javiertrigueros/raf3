<?php

/**
 * arDropFilePreview form base class.
 *
 * @method arDropFilePreview getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearDropFilePreviewForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'drop_file_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DropFile'), 'add_empty' => true)),
      'src'          => new sfWidgetFormTextarea(),
      'type'         => new sfWidgetFormInputText(),
      'guid'         => new sfWidgetFormInputText(),
      'size_style'   => new sfWidgetFormInputText(),
      'persistence'  => new sfWidgetFormChoice(array('choices' => array('small' => 'small', 'med' => 'med', 'big' => 'big'))),
      'size'         => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'slug'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'drop_file_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DropFile'), 'required' => false)),
      'src'          => new sfValidatorString(array('required' => false)),
      'type'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'guid'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'size_style'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'persistence'  => new sfValidatorChoice(array('choices' => array(0 => 'small', 1 => 'med', 2 => 'big'), 'required' => false)),
      'size'         => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
      'slug'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'arDropFilePreview', 'column' => array('guid'))),
        new sfValidatorDoctrineUnique(array('model' => 'arDropFilePreview', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('ar_drop_file_preview[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDropFilePreview';
  }

}
