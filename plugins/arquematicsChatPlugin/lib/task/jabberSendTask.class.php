<?php

class jabberSendTask extends sfBaseTask
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
        new sfCommandArgument('recipient', sfCommandArgument::REQUIRED, 'Jabber\'s Contact Email Recipient (set all to send to all contacts)'),
        new sfCommandArgument('message', sfCommandArgument::REQUIRED, 'Message'),
        ));

        $this->addOptions(array(
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'jabber'),


        // add your own options here
        ));

        $this->namespace        = 'jabber';
        $this->name             = 'send';
        $this->briefDescription = 'Sends a message to a contact';
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

    // add your code here
    
    if ( $arguments['recipient'] == 'all' )
    {
        printf( "Sent %s messages.", jabberApi::broadcastMessage( $arguments['email'], $arguments['message'] ) );   
    }
    else
    {
        jabberApi::sendMessage( $arguments['email'], $arguments['recipient'], $arguments['message'] );
    }
    
    $this->log( 'Message added to queue.' );

}
}
