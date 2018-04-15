<?php

/**
 * arDiagram filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasearDiagramFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'guid'          => new sfWidgetFormFilterInput(),
      'title'         => new sfWidgetFormFilterInput(),
      'json'          => new sfWidgetFormFilterInput(),
      'data_image'    => new sfWidgetFormFilterInput(),
      'type'          => new sfWidgetFormFilterInput(),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'messages_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage')),
    ));

    $this->setValidators(array(
      'guid'          => new sfValidatorPass(array('required' => false)),
      'title'         => new sfValidatorPass(array('required' => false)),
      'json'          => new sfValidatorPass(array('required' => false)),
      'data_image'    => new sfValidatorPass(array('required' => false)),
      'type'          => new sfValidatorPass(array('required' => false)),
      'user_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'messages_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_diagram_filters[%s]');

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
      ->leftJoin($query->getRootAlias().'.arDiagramHasArWallMessage arDiagramHasArWallMessage')
      ->andWhereIn('arDiagramHasArWallMessage.wall_message_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'arDiagram';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'guid'          => 'Text',
      'title'         => 'Text',
      'json'          => 'Text',
      'data_image'    => 'Text',
      'type'          => 'Text',
      'user_id'       => 'ForeignKey',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
      'messages_list' => 'ManyKey',
    );
  }
}
