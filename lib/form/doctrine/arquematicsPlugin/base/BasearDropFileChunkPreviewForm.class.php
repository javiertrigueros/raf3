<?php

/**
 * arDropFileChunkPreview form base class.
 *
 * @method arDropFileChunkPreview getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearDropFileChunkPreviewForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'chunkData'            => new sfWidgetFormTextarea(),
      'pos'                  => new sfWidgetFormInputText(),
      'drop_file_preview_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DropFilePreview'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'chunkData'            => new sfValidatorString(array('required' => false)),
      'pos'                  => new sfValidatorInteger(array('required' => false)),
      'drop_file_preview_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DropFilePreview'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_drop_file_chunk_preview[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDropFileChunkPreview';
  }

}
