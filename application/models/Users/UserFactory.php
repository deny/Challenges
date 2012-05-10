<?php

/**
 * @namespace
 */
namespace Model\Users;

class UserFactory extends \Sca\DataObject\Factory
{
	use Base\UserFactory {
		create as protected;
	}

	/**
	 * Create object
	 *
	 * @param	string	sEmail		adres email
	 * @param	string	sPasswd		hasło
	 * @param	string	sName		imię
	 * @param	string	sSurname	nazwisko
	 * @return	\Model\Users\User
	 */
	public function createNew($sEmail, $sPasswd, $sName, $sSurname)
	{
		$sSalt = sha1(time() . '_c0n57_'. $sEmail . $sPasswd);
		$sPasswd = self::hashPasswd($sPasswd, $sSalt);

		return $this->create(
			$sEmail,
			$sPasswd,
			$sSalt,
			$sName,
			$sSurname,
			User::ROLE_USER,
			User::STATUS_ACTIVE
		);
	}

	/**
	 * Zwraca usera po adresie email
	 *
	 * @param	string	$sEmail	adres email
	 * @return	\Model\Users\User
	 */
	public function getByEmail($sEmail)
	{
		$aDbRes = $this->getSelect()
						->where('u_email = ?', $sEmail)
						->limit(1)->query()->fetchAll();

		if(empty($aDbRes))
		{
			throw new \Sca\DataObject\Exception('Brak usera o podanym emailu');
		}

		return $this->buildObject($aDbRes[0]);
	}


	/**
	 * Zwraca liczbę userów
	 *
	 * @return	int
	 */
	public function getCount()
	{
		$aDbRes = $this->oDb->select()
							->from('user', array('COUNT(*)'))
							->query()->fetchAll(\Zend_Db::FETCH_COLUMN);

		return $aDbRes[0];
	}

	/**
	 * Haszuje i soli hasło
	 *
	 * @param	string	$sPasswd	hasło
	 * @param	strin	$sSalt		sól
	 * @return	string
	 */
	public static function hashPasswd($sPasswd, $sSalt)
	{
		return sha1($sPasswd . '_ch4ll3nge5_'. $sSalt);
	}

	/**
	 * Zwraca listę roli
	 *
	 * @return	array
	 */
	public static function getRoles()
	{
		return [
			\Model\Users\User::ROLE_ADMIN 	=> 'administrator',
			\Model\Users\User::ROLE_MOD 	=> 'moderator',
			\Model\Users\User::ROLE_USER 	=> 'użytkownik'
		];
	}

	/**
	 * Zwraca listę roli
	 *
	 * @return	array
	 */
	public static function getStatus()
	{
		return [
			\Model\Users\User::STATUS_ACTIVE	=> 'aktywny',
			\Model\Users\User::STATUS_INACTIVE	=> 'nieaktywny',
			\Model\Users\User::STATUS_BANNED	=> 'zbanowany'
		];
	}
}