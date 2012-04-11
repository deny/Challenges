<?php

/**
 * Resource automatycznie ładujący zmienne do rejestru
 */
class Core_Application_Resource_Registry extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * (non-PHPdoc)
	 * @see Zend_Application_Resource_Resource::init()
	 */
	public function init()
	{
		foreach($this->_options as $sKey => $sValue)
		{
			if(Zend_Registry::isRegistered($sKey))
			{
				throw new Excecption('Zmienna ' . $sKey . ' była już ładowana do rejestru');
			}

			Zend_Registry::set($sKey, $sValue);
		}
	}
}