<?php

class jabberStatusTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));
        /*
        $this->addArguments(array(
        new sfCommandArgument('action', sfCommandArgument::REQUIRED, 'Action (daemon|send|status)', 'daemon'),
        ));
        */
        $this->addArguments(array(
        new sfCommandArgument('email', sfCommandArgument::REQUIRED, 'Jabber\'s User Email'),
        new sfCommandArgument('status', sfCommandArgument::REQUIRED, 'Jabber\'s User Status'),
        new sfCommandArgument('extra', sfCommandArgument::OPTIONAL, 'Jabber\'s User Extra Status', ''),
        ));

        $this->addOptions(array(
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'jabber'),


        // add your own options here
        ));

        $this->namespace        = 'jabber';
        $this->name             = 'status';
        $this->briefDescription = 'Sets the Jabber user status.';
        $this->detailedDescription = <<<EOF
The [jabber|INFO] sets the status of Jabber user.
Call it with:

  [php symfony jabber|INFO]
EOF;
}

protected function execute($arguments = array(), $options = array())
{
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    // add your code here

    jabberApi::setStatus( $arguments['email'], $arguments[ 'extra'], $arguments['status'] );

    $this->log( 'Set new status' );

}
}
