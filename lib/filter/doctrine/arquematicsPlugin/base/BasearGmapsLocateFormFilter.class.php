<?php

/**
 * arGmapsLocate filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasearGmapsLocateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hash'                => new sfWidgetFormFilterInput(),
      'formated_address'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'user_locations_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate')),
      'messages_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage')),
      'gmaps_locate_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate')),
    ));

    $this->setValidators(array(
      'hash'                => new sfValidatorPass(array('required' => false)),
      'formated_address'    => new sfValidatorPass(array('required' => false)),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'user_locations_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate', 'required' => false)),
      'messages_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage', 'required' => false)),
      'gmaps_locate_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_gmaps_locate_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addUserLocationsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.arUsersGmapsLocate arUsersGmapsLocate')
      ->andWhereIn('arUsersGmapsLocate.profile_id', $values)
    ;
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
      ->leftJoin($query->getRootAlias().'.arGmapsLocateHasArWallMessage arGmapsLocateHasArWallMessage')
      ->andWhereIn('arGmapsLocateHasArWallMessage.wall_message_id', $values)
    ;
  }

  public function addGmapsLocateListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.arUsersGmapsLocate arUsersGmapsLocate')
      ->andWhereIn('arUsersGmapsLocate.locate_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'arGmapsLocate';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'hash'                => 'Text',
      'formated_address'    => 'Text',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
      'user_locations_list' => 'ManyKey',
      'messages_list'       => 'ManyKey',
      'gmaps_locate_list'   => 'ManyKey',
    );
  }
}
