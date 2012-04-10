<?php

/**
 * Rozbudowany kontroler
 */
class Core_Controller_Action extends Zend_Controller_Action
{
	/**
	 * Zalogowany usera
	 *
	 * @var	\Model\Users\User
	 */
	protected $oCurrentUser;

	/**
	 * Inicjalizacja
	 */
	public function init()
	{
		parent::init();

		$this->oCurrentUser = \Model\Users\UserFactory::getInstance()->getOne(1);
	}

	/**
	 * Przenosi usera na 404
	 *
	 * @return	void
	 */
	protected function moveTo404()
	{
		throw new Core_Controller_Action_Exception('Page not found', 404);
	}
}