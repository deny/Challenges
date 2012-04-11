<?php

/**
 * Adapter logowowania
 */
class Core_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
	/**
	 * Email
	 *
	 * @var string
	 */
	protected $sEmail;

	/**
	 * Hasło
	 *
	 * @var string
	 */
	protected $sPasswd;

	/**
	 * Konstruktor
	 *
	 * @param	string	$sEmail		email
	 * @param	string	$sPasswd	hasło
	 */
	public function __construct($sEmail, $sPasswd)
	{
		$this->sEmail 	= $sEmail;
		$this->sPasswd	= $sPasswd;
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Auth_Adapter_Interface::authenticate()
	 */
	public function authenticate()
	{
		// wyjęcie usera
		try
		{
			$oUser = UserFactory::getNew()->getByEmail($this->sEmail);
		}
		catch(Exception $e) // brak usera
		{
			return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->sEmail);
		}

	
		// sprawdzenie hasła i aktywności konta
		if($oUser->isPasswdMatch($this->sPasswd) && $oUser->getStatus() == User::STATUS_ACTIVE)
		{
			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $oUser->getId());
		}

		// niepoprawne hasło
		return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $this->sEmail);
	}
}