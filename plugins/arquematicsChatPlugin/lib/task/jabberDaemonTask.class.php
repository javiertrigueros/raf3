<?php

class jabberDaemonTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
        new sfCommandArgument('email', sfCommandArgument::OPTIONAL, 'Jabber\'s User Email'),
        ));

        $this->addOptions(array(
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'jabber'),
        new sfCommandOption('app', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name'),
        new sfCommandOption('cron', null, sfCommandOption::PARAMETER_NONE, 'If daemon is run from cron, use this flag so it does not go into loop.'),


        // add your own options here
        ));

        $this->namespace        = 'jabber';
        $this->name             = 'daemon';
        $this->briefDescription = 'Jabber daemon. Handles incoming and outgoing communications.';
        $this->detailedDescription = <<<EOF
The [jabber|INFO] manages the Jabber queue.
Call it with:

  [php symfony jabber|INFO]
EOF;
}

protected function execute($arguments = array(), $options = array())
{
    if ( !empty( $options['app'] ) )
    {
        $configuration = ProjectConfiguration::getApplicationConfiguration($options['app'], $options['env'] , true );
    }
    else 
    {
        $configuration = $this->configuration;
    }
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    // add your code here

    if ( ! $email = sfConfig::get( 'app_jabber_email' ) )
    {
        $email = $arguments['email'];
    }
    
    if ( empty( $email ) ) throw new Exception( 'No email provided. Neither found on application configuration' );
    
    if ( ! $user = JabberUserPeer::retrieveByEmail( $email ) )
    {
        throw new sfCommandException( 'No user found with email '.$email );
    }
    
    
    /* Create an instance of XMPP Class */
    $jabber = new sfJabber($user->getJabberServer()->getHost(),    // Jabber Server Hostname
    $user->getJabberServer()->getPort(),    // Jabber Server Port
    $user->getUser(),    // Jabber User
    $user->getPasswd(),    // Jabber Password
    $user->getDomain(),  // Jabber Domain
    null,   // MySQL DB Host
    null,   // MySQL DB Name
    null,   // MySQL DB User
    null,   // MySQL DB Pass
    sfConfig::get( 'app_jabber_log', false ),            // Enable Logging
    false                 // Enable MySQL Logging
    );
    
    
    $jabber->init( $user );

    try {
        /* Initiate the connection */
        $jabber->connect();

        $jabber->jabberUser->setStatusAvailable();
//        $jaxl->processLoop();
        
        /* Communicate with Jabber Server */
        while($jabber->isConnected) {
            $jabber->processLoop();
            $jabber->getXML();
            
            if ( $options['cron'] && $jabber->isReady() && sfConfig::get( 'app_jabber_cron' ) )
            {
                exit;
            }

        }
    }
    catch(Exception $e) {
        throw new sfCommandException( $e->getMessage() );
    }


}
}
