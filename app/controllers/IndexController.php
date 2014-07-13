<?php

use RESTFulPhalcon\RestController;

class IndexController extends RestController
{

    public function indexAction()
    {

        $consumer_key = 'key';
        $consumer_secret = 'secret';
        $url = 'http://service.planeonline.local/oauth/request_token';

        try{
            $oauth = new OAuth($consumer_key,$consumer_secret,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);

//            var_dump($oauth->generateSignature(OAUTH_HTTP_METHOD_GET,$url));

            $request_token_info = $oauth->getRequestToken($url,'http://service.planeonline.local/');
//            $request_token_info = $oauth->getAccessToken($url,'http://service.planeonline.local/');

            $request_token_info= array_keys($request_token_info);



            if(!empty($request_token_info)) {
                print_r(bin2hex($request_token_info[0]));
//                print_r($oauth->getLastResponse());
            } else {
                print "Failed fetching request token, response was: " . $oauth->getLastResponse();
            }

        }catch (Exception $e){
            var_dump($e);
            die(__FILE__ . __LINE__);
        }

        $this->view->response = print_r(bin2hex($request_token_info[0]),true);
    }

}

