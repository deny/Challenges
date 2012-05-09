<?php

/**
 * @namespace
 */
namespace Model\Users;

class User extends \Sca\DataObject\Element
{
	use Base\User;

	CONST ROLE_USER = 'user';
	CONST ROLE_MOD = 'mod';
	CONST ROLE_ADMIN = 'admin';

	CONST STATUS_INACTIVE = 'inactive';
	CONST STATUS_ACTIVE = 'active';
	CONST STATUS_BANNED = 'banned';


	/**
	 * Zwraca imię i nazwisko usera
	 *
	 * @return	string
	 */
	public function getFullName()
	{
		return $this->getName() . ' '. $this->getSurname();
	}

	/**
	 * Sprawdza czy podane hasło jest hasłem usera
	 */
	public function isPasswdMatch($sPasswd)
	{
		return $this->getPasswd() == UserFactory::hashPasswd($sPasswd, $this->getSalt());
	}

	/**
	 * Ustawia nwoe hasło usera
	 *
	 * @param	string	$sPasswd	nowe hasło
	 * @return	\Model\Users\User
	 */
	public function setNewPassword($sPasswd)
	{
		$sSalt = sha1(time() . '_c0n57_'. $this->getEmail() . $sPasswd);
		$sPasswd = \Model\Users\UserFactory::hashPasswd($sPasswd, $sSalt);

		$this->setPasswd($sPasswd);
		$this->setSalt($sSalt);

		return $this;
	}
}