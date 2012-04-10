<?php

/**
 * Bootstrap
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Ustawienia autoloadera
	 */
	protected function _initAutoload()
	{
		Zend_Loader_Autoloader::getInstance()
			->setFallbackAutoloader(true);
	}

	protected function _initOptions()
	{
		//$this->bootstrap('view');
		//$this->getResource('view')->doctype('XHTML1_STRICT');
	}

	/**
	 * Przerzucanie resource do rejestru
	 */
	protected function _initResources()
	{
		// baza danych
		$this->_bootstrap('db');
		Zend_Registry::set('db', $this->getResource('db'));
	}

	/**
	 * Init ScaZF options
	 */
	public function _initScaZF()
	{
		\Sca\Config::getInstance()->setDbAdapter($this->getResource('db'))
									->setModelAutoload(APPLICATION_PATH . '/models');
	}
}
