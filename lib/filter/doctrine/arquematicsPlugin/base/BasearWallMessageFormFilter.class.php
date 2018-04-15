<?php

/**
 * arWallMessage filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasearWallMessageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'is_publish'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'message'           => new sfWidgetFormFilterInput(),
      'published_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'laverna_docs_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arLavernaDoc')),
      'diagrams_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arDiagram')),
      'gmaps_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate')),
      'tags_list'         => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arTag')),
      'lists_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arProfileList')),
      'blog_list'         => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem')),
    ));

    $this->setValidators(array(
      'user_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'is_publish'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'message'           => new sfValidatorPass(array('required' => false)),
      'published_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'laverna_docs_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arLavernaDoc', 'required' => false)),
      'diagrams_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arDiagram', 'required' => false)),
      'gmaps_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate', 'required' => false)),
      'tags_list'         => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arTag', 'required' => false)),
      'lists_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arProfileList', 'required' => false)),
      'blog_list'         => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_wall_message_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addLavernaDocsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('arLavernaDocHasArWallMessage.laverna_id', $values)
    ;
  }

  public function addDiagramsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('arDiagramHasArWallMessage.diagram_id', $values)
    ;
  }

  public function addGmapsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('arGmapsLocateHasArWallMessage.locate_id', $values)
    ;
  }

  public function addTagsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.arTagHasArWallMessage arTagHasArWallMessage')
      ->andWhereIn('arTagHasArWallMessage.tag_id', $values)
    ;
  }

  public function addListsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.arWallMessageHasProfileList arWallMessageHasProfileList')
      ->andWhereIn('arWallMessageHasProfileList.profile_list_id', $values)
    ;
  }

  public function addBlogListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.arWallMessageHasBlogItem arWallMessageHasBlogItem')
      ->andWhereIn('arWallMessageHasBlogItem.a_blog_item_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'arWallMessage';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'user_id'           => 'ForeignKey',
      'is_publish'        => 'Boolean',
      'message'           => 'Text',
      'published_at'      => 'Date',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'laverna_docs_list' => 'ManyKey',
      'diagrams_list'     => 'ManyKey',
      'gmaps_list'        => 'ManyKey',
      'tags_list'         => 'ManyKey',
      'lists_list'        => 'ManyKey',
      'blog_list'         => 'ManyKey',
    );
  }
}
