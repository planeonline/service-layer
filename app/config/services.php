<?php

use Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine,
    Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
    Phalcon\Session\Adapter\Files as SessionAdapter,
    Phalcon\Mvc\Dispatcher as MvcDispatcher,
    Phalcon\Events\Manager as EventsManager,
    Phalcon\Mvc\Dispatcher\Exception as DispatchException;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function() use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function($view, $di) use ($config) {

    $volt = new VoltEngine($view, $di);

    $volt->setOptions(array(
        'compiledPath' => $config->application->cacheDir,
        'compiledSeparator' => '_'
    ));

    return $volt;
},
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function() use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function() {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function() {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});


/**
 * Handling not fount exception
 */
//$di->set('dispatcher', function() {
//
//    //Create an EventsManager
//    $eventsManager = new EventsManager();
//
//    //Attach a listener
//    /* @var $dispatcher Phalcon\Mvc\Dispatcher  */
//    
//    $eventsManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) {
//
//        
//        //Handle 404 exceptions
//        if ($exception instanceof DispatchException) {
//            $dispatcher->forward(array(
//                'controller' => 'index',
//                'action' => 'index'
//            ));
//            return false;
//        }
//
//        //Handle other exceptions
//        $dispatcher->forward(array(
//            'controller' => 'index',
//            'action' => 'show503'
//        ));
//
//        return false;
//    });
//
//    $dispatcher = new MvcDispatcher();
//
//    //Bind the EventsManager to the dispatcher
//    $dispatcher->setEventsManager($eventsManager);
//
//    return $dispatcher;
//
//}, true);
