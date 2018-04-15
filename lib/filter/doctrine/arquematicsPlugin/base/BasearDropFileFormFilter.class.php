<?php

/**
 * arDropFile filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasearDropFileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'src'             => new sfWidgetFormFilterInput(),
      'name'            => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(),
      'guid'            => new sfWidgetFormFilterInput(),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'wall_message_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Message'), 'add_empty' => true)),
      'ready'           => new sfWidgetFormFilterInput(),
      'persistence'     => new sfWidgetFormChoice(array('choices' => array('' => '', 'small' => 'small', 'med' => 'med', 'big' => 'big'))),
      'size'            => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'src'             => new sfValidatorPass(array('required' => false)),
      'name'            => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorPass(array('required' => false)),
      'guid'            => new sfValidatorPass(array('required' => false)),
      'user_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'wall_message_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Message'), 'column' => 'id')),
      'ready'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'persistence'     => new sfValidatorChoice(array('required' => false, 'choices' => array('small' => 'small', 'med' => 'med', 'big' => 'big'))),
      'size'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_drop_file_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDropFile';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'src'             => 'Text',
      'name'            => 'Text',
      'type'            => 'Text',
      'guid'            => 'Text',
      'user_id'         => 'ForeignKey',
      'wall_message_id' => 'ForeignKey',
      'ready'           => 'Number',
      'persistence'     => 'Enum',
      'size'            => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'slug'            => 'Text',
    );
  }
}
