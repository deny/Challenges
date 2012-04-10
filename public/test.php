<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to application directory
defined('PROJECT_PATH')
	|| define('PROJECT_PATH', realpath(APPLICATION_PATH . '/..'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'local'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Config/Ini.php';
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$oApp = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$oApp->bootstrap();

require_once 'testScript.php';