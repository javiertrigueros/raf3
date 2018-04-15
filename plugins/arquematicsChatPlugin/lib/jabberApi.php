<?php

class jabberApi
{
    /**
     * Sends a message
     *
     * @param string $from Sender Email
     * @param string $to Recipient Email
     * @param string $message Message
     */
    public static function sendMessage( $from, $to, $message )
    {
        if ( empty( $message ) )
        {
            throw new Exception( 'Please provide a message to send.' );
        }

        $user = self::getUser( $from );

        if ( $to instanceof JabberContact ) 
        {
            $contact = $to;
        }
        elseif ( ! $contact = JabberContactPeer::retrieveByEmail( $to ) )
        {
            throw new Exception( 'No contact found with this recipient. Contact has to be subscribed already. Adding contacts needs to be implemented.' );
        }


        JabberMessagePeer::newMessage( $user, $contact, $message );
    }
    
    /**
     * Sends a message to all contacts
     *
     * @param string $from
     * @param string $message
     * @param bool $includeAway false
     * @param bool $includeOffline false
     * @return int Number of messages sent.
     */
    public static function broadcastMessage( $from, $message, $includeAway = false, $includeOffline = false )
    {
        if ( empty( $message ) )
        {
            throw new Exception( 'Please provide a message to send.' );
        }

        $user = self::getUser( $from );

        $roster = $user->getJabberRostersJoinJabberContact();
        
        $totalSent = 0;
        
        foreach( $roster as $r )
        {
            $contact = $r->getJabberContact();
            
            if ( false ) $contact = new JabberContact();
            
            if ( $includeOffline || ( $includeAway && $contact->isAway() ) || $contact->isAvailable() )
            {
                JabberMessagePeer::newMessage( $user, $contact, $message );
                $totalSent++;
            }
        }
        
        return $totalSent;
    } 

    /**
     * Sets user show and extended status
     *
     * @param string $email
     * @param string $status extended status message
     * @param string $show available|unavailable|away
     */
    public static function setStatus( $email, $status, $show = '' )
    {
        $user = self::getUser( $email );
        
        $user->setExtraStatus( $status );
    
        if ( $jstatus = JabberStatusPeer::retrieveByDescription( $show ) )
        {
            $user->setJabberStatus( $jstatus );
            $user->save();
        }
          
    }
    
    /**
     * Gets array with list of incoming messages pending processing.
     *
     * @param string $email
     * @param bool $markedProcessed false
     * @return array of JabberMessage 
     */
    public static function getIncomingMessages( $email, $markedProcessed = false )
    {
        $user = self::getUser( $email );
        
        $messages = $user->getIncomingMessages();
        
        if ( $markedProcessed )
        {
            foreach ($messages as $msg )
            {
                $msg->setProcessed();
            }
            
        }

        return $messages;
    }
    
    public static function getContactStatusChanges( $email, $markedProcessed = false )
    {
    
        $user = self::getUser( $email );
              
        $contacts = $user->getContactsWithChangedStatus();
        
        if ( $markedProcessed )
        {
            foreach ($messages as $msg )
            {
                $msg->setProcessed();
            }
            
        }

        return $messages;
    }
    
    /**
     * Returns JabberUser by email
     *
     * @param string $email
     * @return JabberUser
     */
    public static function getUser( $email )
    {
        if ( $email instanceof JabberUser ) return $email;
        
        if ( ! $user = JabberUserPeer::retrieveByEmail( $email ) )
        {
            throw new Exception( 'No user found with this email in the local database. Please add user to JabberUser.' );
        }   

        return $user;       
    }
   
    public static function getUserContacts( $email )
    {
        $user = self::getUser( $email );
        
        $roster = $user->getJabberRostersJoinJabberContact();
        
        return $roster;
    }
    
    /**
     * Adds new Jabber User
     *
     * @param string $email
     * @param string $password
     * @param string $host
     * @param string $port
     * @return JabberUser
     */
    public static function addUser( $email, $password, $host = 'talk.google.com', $port = '5222' )
    {
        if ( JabberUserPeer::retrieveByEmail( $email ) )
        {
            throw new Exception( 'User already exists.' );
        }   
        
        list( $username, $domain ) = explode( '@', $email );
        
        if ( ! $server = JabberServerPeer::retrieveByHost( $host ) )
        {
            $server = JabberServerPeer::newInstance( $host, $port );
            
        }
        
        $user = new JabberUser();
        $user->setUser( $username );
        $user->setPasswd( $password );
        $user->setDomain( $domain );
        $user->setJabberServerId( $server->getId() );
        $user->setJabberStatusId( JabberStatus::OFFLINE );
        
        $user->save();
        
        return $user;
    }
    
    /**
     * Deletes all messages from message queue
     *
     */
    public static function clearMessagesQueue()
    {
        JabberMessagePeer::doDeleteAll();
    }
}
