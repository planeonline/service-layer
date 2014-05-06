<?php
use Phalcon\DI,
    Phalcon\DI\FactoryDefault;

ini_set('display_errors',1);
error_reporting(E_ALL);

define('ROOT_PATH', __DIR__);

set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path()
);



$config = new \Phalcon\Config\Adapter\Ini(ROOT_PATH . '/../app/config/config.ini');

include ROOT_PATH . "/../app/config/loader.php";

$loader = new \Phalcon\Loader();

$loader->registerDirs(array(
    ROOT_PATH,
    '../app/controllers',
    '../app/models'
    
));

$loader->registerNamespaces(
        array(
           'RESTFulPhalcon' => '../library/RESTFulPhalcon', 
           'RESTFulPhalcon\Response' => '../library/RESTFulPhalcon/RestResponse', 
           'Phalcon' => '../library/incubator/Library/Phalcon/'
        )
);

$loader->register();
