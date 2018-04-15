<?php

class jabberAddUserTask extends sfBaseTask
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
        new sfCommandArgument('password', sfCommandArgument::REQUIRED, 'Jabber\'s User password to connect to Jabber server'),
        new sfCommandArgument('host', sfCommandArgument::OPTIONAL, 'Jabber\'s server hostname where user resides.', 'talk.google.com' ),
        new sfCommandArgument('port', sfCommandArgument::OPTIONAL, 'Jabber\'s server port where user resides.', '5222' ),
        
        ));

        $this->addOptions(array(
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'jabber'),


        // add your own options here
        ));

        $this->namespace        = 'jabber';
        $this->name             = 'add-user';
        $this->briefDescription = 'Adds a Jabber user to the system';
        $this->detailedDescription = <<<EOF
The [jabber|INFO] sends a message to a contact
Call it with:

  [php symfony jabber|INFO]
EOF;
}
    
    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
    
    
        jabberApi::addUser( $arguments['email'], $arguments['password'], $arguments['host'], $arguments['port'] );
            
        $this->logSection( 'jabber', 'Jabber user added.' );
    
    }
}
