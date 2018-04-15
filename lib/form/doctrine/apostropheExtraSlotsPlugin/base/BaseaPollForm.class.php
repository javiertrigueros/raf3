<?php

/**
 * aPoll form base class.
 *
 * @method aPoll getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseaPollForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'media_item_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MediaItem'), 'add_empty' => true)),
      'question'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'media_item_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MediaItem'), 'required' => false)),
      'question'      => new sfValidatorString(array('max_length' => 255)),
    ));

    $this->widgetSchema->setNameFormat('a_poll[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'aPoll';
  }

}