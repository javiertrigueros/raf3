<?php

require_once dirname(__FILE__).'/../lib/arCommentAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/arCommentAdminGeneratorHelper.class.php';

/**
 * arCommentAdmin actions.
 *
 * @package    alcoor
 * @subpackage arCommentAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class arCommentAdminActions extends autoArCommentAdminActions
{
  public function preExecute()
  {
    sfProjectConfiguration::getActive()
            ->loadHelpers(array('I18N','Partial'));
    parent::preExecute();
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    // sorting
    if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort')))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
    }

    // pager
    if ($request->getParameter('page'))
    {
      $this->setPage($request->getParameter('page'));
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();
  }
  
  protected function isValidSortColumn($column)
  {
    return Doctrine_Core::getTable('arComment')->hasColumn($column)
            || ($column === 'author');
  }
  
  protected function getSort()
  {
    if (null !== $sort = $this->getUser()->getAttribute('arCommentAdmin.sort', null, 'admin_module'))
    {
      return $sort;
    }

    $this->setSort($this->configuration->getDefaultSort());

    return $this->getUser()->getAttribute('arCommentAdmin.sort', null, 'admin_module');
  }

  protected function setSort(array $sort)
  {
    if (null !== $sort[0] && null === $sort[1])
    {
      $sort[1] = 'asc';
    }

    $this->getUser()->setAttribute('arCommentAdmin.sort', $sort, 'admin_module');
  }
  
  
  protected function addSortQuery($query)
  {
    if (array(null, null) == ($sort = $this->getSort()))
    {
      return;
    }

    if (!in_array(strtolower($sort[1]), array('asc', 'desc')))
    {
      $sort[1] = 'asc';
    }

    if ($sort[0] === 'author')
    {    
        $query->addOrderBy('u.username ' . $sort[1]);
        $query->addOrderBy('a.comment_author '. $sort[1]);
    }
    else
    {
       $query->addOrderBy($sort[0] . ' ' . $sort[1]);  
    }
    
    $this->sort = $sort;
  }
  
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine_Core::getTable('arComment')
      ->createQuery('a');

    if ($tableMethod)
    {
      $query = Doctrine_Core::getTable('arComment')->$tableMethod($query);
    }

    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
    return $event->getReturnValue();
    
    //return $query;
  }
  
  protected function getPager()
  {
    $pager = $this->configuration->getPager('arComment');
    $pager->setQuery($this->buildQuery());
    
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    return $pager;
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

    if ($this->getRoute()->getObject()->delete())
    {
      $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
    }

    $this->redirect('@ar_comment_admin');
  }
  
    
  protected function executeBatchApproved(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $records = Doctrine_Query::create()
      ->from('arComment')
      ->whereIn('id', $ids)
      ->execute();

    foreach ($records as $record)
    {
      $this->dispatcher->notify(new sfEvent($this, 'admin.approved_object', array('object' => $record)));

      $record->approved();
    }

    $this->getUser()->setFlash('notice', __('The selected items have been approved successfully.', null,'blog'));
    $this->redirect('@ar_comment_admin');
  }
  
  protected function executeBatchPending(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $records = Doctrine_Query::create()
      ->from('arComment')
      ->whereIn('id', $ids)
      ->execute();

    foreach ($records as $record)
    {
      $this->dispatcher->notify(new sfEvent($this, 'admin.pending_object', array('object' => $record)));

      $record->pending();
    }

    $this->getUser()->setFlash('notice', __('The selected items have been changed status to pending successfully.', null, 'blog'));
    $this->redirect('@ar_comment_admin');
  }
    
}
