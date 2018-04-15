<?php

/**
 * PluginarComment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginarComment extends BasearComment
{
    private $userProfile = false;
    
    public function getChildren()
    {
        return Doctrine::getTable('arComment')
                ->getChildren($this->getId());
    }
    
    
    public function getUserProfile()
    {
       $user = $this->getUser();
        
       if ($user && is_object($user))
       {
           return $user->getProfile();  
       }
       else return false;
    }
    
    public function getEmail()
    {
        if (!$this->userProfile)
        {
            $this->userProfile = $this->getUserProfile();
        }
        
        if ($this->userProfile 
                && is_object($this->userProfile)
                && ($this->userProfile->getId() > 0))
        {
            return $this->userProfile->getEmailAddress();
            
        }
        else return $this->getCommentAuthorEmail();
    }
    
    
    public function getUserImage()
    {
          return Doctrine_Core::getTable('arProfileUpload')
                ->retrieveByUserId($this->getUser()->getProfile()->getId());  
       
    }
    
    public function hasUserProfile()
    {
        $user = $this->getUser();
      
        return ($user && is_object($user) && ($user->getId() > 0));
    }
    
    public function getUserProfileLongName()
    {
        return $this->getUser()->getProfile()->getFirstLast();
    }
    
    public function hasCommentAuthorUrl()
    {
        return strlen(trim($this->comment_author_url)) > 0;
    }
    /**
     * nivel del comentario
     * 
     * @return <integer>
     */
    public function getLevel()
    {
         $comments = Doctrine::getTable('arComment')
              -> getAllByBlogItemId($this->getABlogItemId()); 
         
         $treeNodes = arNestedHelp::buildTree($comments);
         
         return arNestedHelp::getTreeLevel($treeNodes, $this->getId());
    }
    
  /**
   * 
   * @param Doctrine_Connection $conn
   * @return boolean
   * @throws Exception
   */  
  public function approved( Doctrine_Connection $conn = null)
  {
    $ret = false;
    $conn = $conn ? $conn : $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {
        $this->setCommentApproved(true);
        
        $this->save($conn);

        $conn->commit();

        return true;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
        
        return $ret;
    }
  }
  /**
   * 
   * @param Doctrine_Connection $conn
   * @return boolean
   * @throws Exception
   */
  public function pending( Doctrine_Connection $conn = null)
  {
    $ret = false;
    $conn = $conn ? $conn : $this->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {
        $this->setCommentApproved(false);
        
        $this->save($conn);

        $conn->commit();

        return true;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        throw $e;
        
        return $ret;
    }
  }
    
}