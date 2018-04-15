<?php

/* Include JAXL Class */
include_once("jaxl-1.0.3/jaxl.class.php");


class sfJabber extends XMPP
{
    /**
     * JabberUser
     *
     * @var JabberUser
     */
    public $jabberUser = null;

    protected $ready = false;
    
    protected $callbacks = array(
    'ready' => null,
    'loop' => null,
    'message' => null,
    'status' => null,
    'writingStarted' => null,
    'writingPaused' => null,
    );
    

    public function init( JabberUser $user )
    {
        $this->jabberUser = $user;
        $c = sfConfig::get( 'app_jabber_callback' ) ;
        
        if ( $c )
        {               
            $this->callbacks = array_merge( $this->callbacks, $c );
        }
        
    }
    
    public function isReady()
    {
        if ( $this->ready )
        {
            $this->doCallback( 'ready', array( $this, $message ) );
        }
        
        return $this->ready;

    }
    
    function eventMessage($fromJid, $content, $offline = FALSE) {
        print "Got new message '$content' from $fromJid...\n";

        if ( ! $contact = $this->jabberUser->isJidInRoster( $fromJid ) )
        {
            $contact = JabberContactPeer::getInstance( $fromJid );
            $this->jabberUser->addContactToRoster( $contact );
        }
        
        $message = new JabberMessage();
        $message->setIsIncoming( true );
        $message->setContent( $content );
        $message->setJabberUser( $this->jabberUser );

        $contact->addJabberMessage( $message );
        $message->save();
        
        $this->doCallback( 'message', array( $this, $message ) );

    }

    function eventPresence($fromJid, $status, $photo, $show = null) {
        // Change your status message to your friend's status
        // $this->sendStatus($status);

        if ( empty( $status ) ) 
        {
            $status = 'unavailable';
            $show = 'unavailable';
        }

        print "Got new status $status ($show) from $fromJid...\n";
        if ( ! $contact = $this->jabberUser->isJidInRoster( $fromJid ) )
        {
            $contact = JabberContactPeer::getInstance( $fromJid );
            $this->jabberUser->addContactToRoster( $contact );
        }
        
        $jstatus = JabberStatusPeer::retrieveByDescription( $show );
        
        if ( $jstatus  )
        {
            $contact->setJabberStatus( $jstatus );
        }
        else
        {
            
            $contact->setJabberStatusId( JabberStatus::AVAILABLE );
        }
        
        if ( $status ) $contact->setCustomStatus( $status );
        
        $contact->save();
        
        $this->doCallback( 'status', array( $this, $contact ) );

    }
    
    /**
     * Event fired when contact starts writing a message
     *
     * @param  $fromJid
     */
    function eventComposing($fromJid) {
        if ( ! $contact = $this->jabberUser->isJidInRoster( $fromJid ) )
        {
            $contact = JabberContactPeer::getInstance( $fromJid );
            $this->jabberUser->addContactToRoster( $contact );
        }

        $this->doCallback( 'writingStarted', array( $this, $contact ) );      
    }
    
    /**
     * Event fired when contact pauses writing a message
     *
     * @param  $fromJid
     */
    function eventPaused($fromJid) {
        if ( ! $contact = $this->jabberUser->isJidInRoster( $fromJid ) )
        {
            $contact = JabberContactPeer::getInstance( $fromJid );
            $this->jabberUser->addContactToRoster( $contact );
        }

        $this->doCallback( 'writingPaused', array( $this, $contact ) );
    }    
    
    public function processLoop()
    {
        
        if ( ! $this->ready ) return;
        
        JabberUserPeer::clearInstancePool();
        
        if ( ! $this->jabberUser = JabberUserPeer::retrieveByPK( $this->jabberUser->getId() ) )
        {
            die( "Terminating." ); 
        }
        
    //    echo "entry process loop\n";

        if ( $this->jabberUser->getUpdateStatus() )
        {
            $this->setStatus();            
        }
        
        $msgs = $this->jabberUser->getMessagesToSend();
        foreach( $msgs as $m )
        {
            if ( false ) $m = new JabberMessage();
            
            $m->setIsProcessed(true);
            $m->save();
            
            $this->sendMessage( $m->getJabberContact()->getJid(), $m->getContent() );
            echo "Sent message to ".$m->getJabberContact()->getJid()."\n";
        }
        

        $this->doCallback( 'loop', array( $this ) );
        $sleep = sfConfig::get( 'app_jabber_sleep' );
        
        if ( $sleep )
        {
            sleep( $sleep );
        }
        
    }
    
    
    public function doCallback( $name, $args = array() )
    {
        if ( !empty( $this->callbacks[$name] ) )
        {
            $callback = $this->callbacks[$name];
            
            if ( strpos( $callback, '::') !== false )
            {
                $callable = explode( '::', $callback );
            }
            else 
            {
                $callable = $callback;
            }
            
            if ( ! is_callable( $callable ) ) 
            {
                throw new Exception( "Cannot call $callback" );
            }
            
            //echo "  Calling $callback...\n";
            return call_user_func_array( $callable, $args );
        }        
    }
    
    
    function setStatus()
    {
        $this->ready = true;
        
        $this->jabberUser->setUpdateStatus( false );
        $this->jabberUser->save();

        $this->sendStatus( $this->jabberUser->getExtraStatus(), $this->jabberUser->getJabberStatus()->getIsOnline() ? 'Available' : 'Offline' );
        print "Setting Status...\n";
        print "Done\n";
        
    }
    
    
    function setNewStatus( $status, $now = false ) 
    {
        $jstatus = JabberStatusPeer::retrieveByDescription( $status );
        if ( $jstatus )
        {
            $this->jabberUser->setJabberStatus( $jstatus );
            $this->jabberUser->setUpdateStatus( true );
            $this->jabberUser->save();
        }
    
        if ( $now )
        {
            $this->setStatus();
        }
    }
}


?>
