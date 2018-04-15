<?php

/**
 * arWallMessage form base class.
 *
 * @method arWallMessage getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearWallMessageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'user_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'is_publish'        => new sfWidgetFormInputCheckbox(),
      'message'           => new sfWidgetFormTextarea(),
      'published_at'      => new sfWidgetFormDateTime(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'laverna_docs_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arLavernaDoc')),
      'diagrams_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arDiagram')),
      'gmaps_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate')),
      'tags_list'         => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arTag')),
      'lists_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arProfileList')),
      'blog_list'         => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem')),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'is_publish'        => new sfValidatorBoolean(array('required' => false)),
      'message'           => new sfValidatorString(array('required' => false)),
      'published_at'      => new sfValidatorDateTime(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
      'laverna_docs_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arLavernaDoc', 'required' => false)),
      'diagrams_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arDiagram', 'required' => false)),
      'gmaps_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate', 'required' => false)),
      'tags_list'         => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arTag', 'required' => false)),
      'lists_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arProfileList', 'required' => false)),
      'blog_list'         => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_wall_message[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arWallMessage';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['laverna_docs_list']))
    {
      $this->setDefault('laverna_docs_list', $this->object->LavernaDocs->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['diagrams_list']))
    {
      $this->setDefault('diagrams_list', $this->object->Diagrams->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['gmaps_list']))
    {
      $this->setDefault('gmaps_list', $this->object->Gmaps->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['tags_list']))
    {
      $this->setDefault('tags_list', $this->object->Tags->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['lists_list']))
    {
      $this->setDefault('lists_list', $this->object->Lists->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['blog_list']))
    {
      $this->setDefault('blog_list', $this->object->Blog->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveLavernaDocsList($con);
    $this->saveDiagramsList($con);
    $this->saveGmapsList($con);
    $this->saveTagsList($con);
    $this->saveListsList($con);
    $this->saveBlogList($con);

    parent::doSave($con);
  }

  public function saveLavernaDocsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['laverna_docs_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->LavernaDocs->getPrimaryKeys();
    $values = $this->getValue('laverna_docs_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('LavernaDocs', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('LavernaDocs', array_values($link));
    }
  }

  public function saveDiagramsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['diagrams_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Diagrams->getPrimaryKeys();
    $values = $this->getValue('diagrams_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Diagrams', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Diagrams', array_values($link));
    }
  }

  public function saveGmapsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['gmaps_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Gmaps->getPrimaryKeys();
    $values = $this->getValue('gmaps_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Gmaps', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Gmaps', array_values($link));
    }
  }

  public function saveTagsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['tags_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Tags->getPrimaryKeys();
    $values = $this->getValue('tags_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Tags', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Tags', array_values($link));
    }
  }

  public function saveListsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['lists_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Lists->getPrimaryKeys();
    $values = $this->getValue('lists_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Lists', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Lists', array_values($link));
    }
  }

  public function saveBlogList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['blog_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Blog->getPrimaryKeys();
    $values = $this->getValue('blog_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Blog', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Blog', array_values($link));
    }
  }

}
