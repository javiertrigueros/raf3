<?php

/**
 * arDropFileChunkPreview filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasearDropFileChunkPreviewFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'chunkData'            => new sfWidgetFormFilterInput(),
      'pos'                  => new sfWidgetFormFilterInput(),
      'drop_file_preview_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DropFilePreview'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'chunkData'            => new sfValidatorPass(array('required' => false)),
      'pos'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'drop_file_preview_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DropFilePreview'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ar_drop_file_chunk_preview_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDropFileChunkPreview';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'chunkData'            => 'Text',
      'pos'                  => 'Number',
      'drop_file_preview_id' => 'ForeignKey',
    );
  }
}
