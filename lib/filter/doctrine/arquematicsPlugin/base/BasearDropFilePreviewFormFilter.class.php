<?php

/**
 * arDropFilePreview filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasearDropFilePreviewFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'drop_file_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DropFile'), 'add_empty' => true)),
      'src'          => new sfWidgetFormFilterInput(),
      'type'         => new sfWidgetFormFilterInput(),
      'guid'         => new sfWidgetFormFilterInput(),
      'size_style'   => new sfWidgetFormFilterInput(),
      'persistence'  => new sfWidgetFormChoice(array('choices' => array('' => '', 'small' => 'small', 'med' => 'med', 'big' => 'big'))),
      'size'         => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'drop_file_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DropFile'), 'column' => 'id')),
      'src'          => new sfValidatorPass(array('required' => false)),
      'type'         => new sfValidatorPass(array('required' => false)),
      'guid'         => new sfValidatorPass(array('required' => false)),
      'size_style'   => new sfValidatorPass(array('required' => false)),
      'persistence'  => new sfValidatorChoice(array('required' => false, 'choices' => array('small' => 'small', 'med' => 'med', 'big' => 'big'))),
      'size'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'         => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_drop_file_preview_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDropFilePreview';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'drop_file_id' => 'ForeignKey',
      'src'          => 'Text',
      'type'         => 'Text',
      'guid'         => 'Text',
      'size_style'   => 'Text',
      'persistence'  => 'Enum',
      'size'         => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
      'slug'         => 'Text',
    );
  }
}
