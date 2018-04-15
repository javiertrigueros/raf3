<?php

/**
 * arLavernaDoc filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasearLavernaDocFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'         => new sfWidgetFormFilterInput(),
      'content'       => new sfWidgetFormFilterInput(),
      'data_image'    => new sfWidgetFormFilterInput(),
      'type'          => new sfWidgetFormFilterInput(),
      'task_all'      => new sfWidgetFormFilterInput(),
      'task_complete' => new sfWidgetFormFilterInput(),
      'guid'          => new sfWidgetFormFilterInput(),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'messages_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage')),
    ));

    $this->setValidators(array(
      'title'         => new sfValidatorPass(array('required' => false)),
      'content'       => new sfValidatorPass(array('required' => false)),
      'data_image'    => new sfValidatorPass(array('required' => false)),
      'type'          => new sfValidatorPass(array('required' => false)),
      'task_all'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'task_complete' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'guid'          => new sfValidatorPass(array('required' => false)),
      'user_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'messages_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_laverna_doc_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addMessagesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.arLavernaDocHasArWallMessage arLavernaDocHasArWallMessage')
      ->andWhereIn('arLavernaDocHasArWallMessage.wall_message_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'arLavernaDoc';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'title'         => 'Text',
      'content'       => 'Text',
      'data_image'    => 'Text',
      'type'          => 'Text',
      'task_all'      => 'Number',
      'task_complete' => 'Number',
      'guid'          => 'Text',
      'user_id'       => 'ForeignKey',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
      'messages_list' => 'ManyKey',
    );
  }
}
