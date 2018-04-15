<?php

class syTwitterPluginRouting
{
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
    {
        $routing = $event->getSubject();

        // add plug-in routing rules on top of the existing ones
        $routing->prependRoute('login',
                               new sfRoute('/login', array('module' => 'twitter', 'action' => 'twitterLogin'))
        );

        $routing->prependRoute('logout',
                               new sfRoute('/logout', array('module' => 'twitter', 'action' => 'twitterLogout'))
        );
    }
}
