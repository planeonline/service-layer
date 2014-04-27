<?php

error_reporting(E_ALL);

try {

    /**
     * Read the configuration
     */
    $config = new \Phalcon\Config\Adapter\Ini(__DIR__ . '/../app/config/config.ini');

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);
    
    $application->router->addPost("#^/([a-zA-Z0-9\_\-]+)(/.*)*$#", array(
        "controller" => 1,        
        "action" => 'post',        
        "params" => 2
    ));
    
    $application->router->addPut("#^/([a-zA-Z0-9\_\-]+)(/.*)*$#", array(
        "controller" => 1,        
        "action" => 'put',        
        "params" => 2
    ));

    echo $application->handle()->getContent();
    
} catch (\Exception $e) {
    echo $e->getMessage();
} 