<?php

class syTwitterPluginActions extends sfActions
{
    public function executeTwitterLogin(sfWebRequest $request)
    {
        $oa = new OAuth(
                sfConfig::get('app_twitter_consumer_key'),
                sfConfig::get('app_twitter_consumer_secret')
        );

        if(FALSE == $request->hasParameter('oauth_token'))
        {
            $request_token_url = sfConfig::get('app_twitter_request_token_url');

            if( ! ($url = sfConfig::get('app_base_url')) )
            {
                $url = "http://" . $_SERVER['HTTP_HOST'];
            }

            $callback_url = $url;
            $callback_url.= $this->getController()->genUrl('@login');

            $request_token = $oa->getRequestToken($request_token_url,
                                                  $callback_url);

            $next_url = sfConfig::get('app_twitter_authenticate_url');
            $next_url.= '?oauth_token=' . $request_token['oauth_token'];

            $this->redirect( $next_url );
        }
        else
        {
            $oauth_token = $request->getParameter('oauth_token');
            $oauth_verifier = $request->getParameter('oauth_verifier');

            $access_token = $oa->getAccessToken(sfConfig::get('app_twitter_access_token_url'),
                                                $oauth_token,
                                                $oauth_verifier);

            $this->getUser()->setAttribute('screen_name', $access_token['screen_name']);
            $this->getUser()->setAttribute('oauth_token', $access_token['oauth_token']);
            $this->getUser()->setAttribute('oauth_token_secret', $access_token['oauth_token_secret']);
            $this->getUser()->setAuthenticated(TRUE);
        }

        $this->redirect('@homepage');
    }

    public function executeTwitterLogout(sfWebRequest $request)
    {
        $this->getUser()->setAttribute('screen_name', NULL);
        $this->getUser()->setAttribute('oauth_token', NULL);
        $this->getUser()->setAttribute('oauth_token_secret', NULL);
        $this->getUser()->setAuthenticated(FALSE);

        $this->redirect('/');
    }
}

