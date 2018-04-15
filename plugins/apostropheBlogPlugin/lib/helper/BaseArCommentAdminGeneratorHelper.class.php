<?php

/**
 * arCommentAdmin module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage arCommentAdmin
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: helper.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseArCommentAdminGeneratorHelper extends sfModelGeneratorHelper
{
  public function getUrlForAction($action)
  {
    return 'list' == $action ? 'ar_comment_admin' : 'ar_comment_admin_'.$action;
  }
}
