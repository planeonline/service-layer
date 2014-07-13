<?php

use Phalcon\Mvc\Controller;

class OauthController extends Controller
{

    protected $oAuthProvider;

    public function request_tokenAction()
    {

        try {

            $this->getOauthProvider()->consumerHandler(array($this, 'lookupConsumer'));

            $this->getOauthProvider()->isRequestTokenEndpoint(true);

            $this->getOauthProvider()->checkOAuthRequest('http://service.planeonline.local/oauth/request_token', OAUTH_HTTP_METHOD_GET);

            echo $this->getOauthProvider()->generateToken(16);

        } catch (OAuthException $E) {
            echo OAuthProvider::reportProblem($E);
            $this->oauth_error = true;
        }
    }

    public function indexAction()
    {


    }

    public function lookupConsumer($provider)
    {
//        $consumer = ORM::Factory("consumer", $provider->consumer_key);
//        if($provider->consumer_key != $consumer->consumer_key) {
//            return OAUTH_CONSUMER_KEY_UNKNOWN;
//        } else if($consumer->key_status != 0) {  // 0 is active, 1 is throttled, 2 is blacklisted
//            return OAUTH_CONSUMER_KEY_REFUSED;
//        }
//        $provider->consumer_secret = $consumer->secret;
        $provider->consumer_secret = 'secret';
        return OAUTH_OK;
    }

    function new_consumer_key()
    {
        $fp = fopen('/dev/urandom', 'rb');
        $entropy = fread($fp, 32);
        fclose($fp);
        // in case /dev/urandom is reusing entropy from its pool, let's add a bit more entropy
        $entropy .= uniqid(mt_rand(), true);
        $hash = sha1($entropy); // sha1 gives us a 40-byte hash
        // The first 30 bytes should be plenty for the consumer_key
        // We use the last 10 for the shared secret
        return array(substr($hash, 0, 30), substr($hash, 30, 10));
    }

    public function tokenHandler($provider)
    {
//        $this->token = ORM::Factory("token", $provider->token);
//        if(!$this->token->loaded) {
//            return OAUTH_TOKEN_REJECTED;
//        } else if($this->token->type==1 && $this->token->state==1) {
//            return OAUTH_TOKEN_REVOKED;
//        } else if($this->token->type==0 && $this->token->state==2) {
//            return OAUTH_TOKEN_USED;
//        } else if($this->token->type==0 && $this->token->verifier != $provider->verifier) {
//            return OAUTH_VERIFIER_INVALID;
//        }
//        $provider->token_secret = $this->token->secret;
        $provider->token_secret = 'token_secret';
        return OAUTH_OK;
    }

    public function timestampNonceChecker()
    {
        return OAUTH_OK;
    }


    protected function getOauthProvider($params = array(), $instantiate = false)
    {
        if (is_null($this->oAuthProvider) OR $instantiate) {
            $this->oAuthProvider = new OAuthProvider($params);
        }

        $this->oAuthProvider->consumerHandler(array($this, 'lookupConsumer'));
        $this->oAuthProvider->timestampNonceHandler(array($this, 'timestampNonceChecker'));
        $this->oAuthProvider->tokenHandler(array($this, 'tokenHandler'));
        $this->oAuthProvider->setParam('_url', NULL); // Ignore the kohana_uri parameter

        return $this->oAuthProvider;
    }
} 