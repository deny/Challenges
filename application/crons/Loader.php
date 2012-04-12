#!/usr/bin/php -q
<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

// Define path to application directory
defined('PROJECT_PATH')
	|| define('PROJECT_PATH', realpath(APPLICATION_PATH . '/..'));

// ustalenie środowiska
if(count($_SERVER['argv']) < 3)
{
	echo 'Podaj środowisko i nazwę crona, którego chcesz wywołać.'."\n";
	exit();
}

define('APPLICATION_ENV', $_SERVER['argv'][1]);

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


// WYWOŁANIE CRONA
	$sCron = $_SERVER['argv'][2];
	$sLogDir = PROJECT_PATH . '/data/logs';
	$sClassPath = APPLICATION_PATH . '/crons/' . basename($sCron) . '.php';

	// jeśli cron istnieje to dołączamy plik
	if(!file_exists($sClassPath))
	{
		echo 'Cron '. $sCron .' nie istnieje.'."\n";
		exit();
	}

	require_once $sClassPath;

// przygotowanie loga
	$oLog = new Zend_Log();
	$oLog->addWriter(new Core_Log_Writer_File($sLogDir, Core_Log_Writer_File::TYPE_MAIN));
	$oLog->addWriter(new Core_Log_Writer_File($sLogDir, Core_Log_Writer_File::TYPE_CRON, $sCron));

// utworzenie i wywołanie crona
	$oCron = new $sCron($oLog);
	$oCron->execute(array_slice($_SERVER['argv'], 3));
