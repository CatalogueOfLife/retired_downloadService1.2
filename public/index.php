<?php

try {
    
    // Define path to application directory
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
    
    // Define application environment
    defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
    set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH . '/../library'), 
        APPLICATION_PATH . '/modules', 
        APPLICATION_PATH . '/classes', 
    )));
        
    
    require_once 'Zend/Application.php';
    $application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.xml');
    
    $application->bootstrap()->run();
    
}

catch (Exception $e) {
    printf("<pre>%s\n%s (%s): %s</pre>", $e->getTraceAsString(), $e->getFile(), $e->getLine(), $e->getMessage());
}

