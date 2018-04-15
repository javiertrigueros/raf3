<?php

class arquematicsExtraSlotsPluginConfiguration extends sfPluginConfiguration
{
  static $registered = false;
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    // Yes, this can get called twice. This is Fabien's workaround:
    // http://trac.symfony-project.org/ticket/8026

    if (!self::$registered)
    {
      $this->dispatcher->connect('a.migrateSchemaAdditions', array($this, 'migrate'));
      

    // This was inadvertently removed just prior to 1.4. Now apostrophe:migrate hooks up properly again
      self::$registered = true;
    }
    
    
  }

}
