<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
        array(
            $config->application->controllersDir,
            $config->application->modelsDir,
            $config->application->validatorsDir,
//            $config->application->RESTDir,
//            $config->application->RESTRequest,
//            $config->application->RESTModel
        )
);

$loader->registerNamespaces(
        array(
           'RESTFulPhalcon' => '../library/RESTFulPhalcon', 
           'RESTFulPhalcon\Response' => '../library/RESTFulPhalcon/RestResponse', 
        )
);

$loader->register();
