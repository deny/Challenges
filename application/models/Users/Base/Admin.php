<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Base trait
 */
trait Admin
{


// FIELDS

	/**
	 * @var	string
	 */
	private $sLoginAttempts = null;


// INITIALIZATION

	/**
	 * Model initialziation
	 *
	 * @param	array	$aRow			row from DB
	 * @param	array	$aComponents	components desc
	 */
	public function init(array &$aRow, array &$aComponents = [])
	{
		$aComponents[] = self::info();
		parent::init($aRow, $aComponents);

		$this->sLoginAttempts = $aRow['login_attempts'];





		return $this;
	}


// GETTERS

	/**
	 * @return	string
	 */
	public function getLoginAttempts()
	{
		return $this->sLoginAttempts;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setLoginAttempts($mValue)
	{
		$this->sLoginAttempts = $mValue;
		$this->setDataValue(self::info()['table'], 'uea_login_attempts', $mValue);
		return $this;
	}


// STATIC

	/**
	 * Return model DB information
	 *
	 * @return	array
	 */
	public static function info()
	{
		return [
			'table' => 'user_e_admin',
			'alias'	=> 'uea',
			'key'	=> 'u_id'
		];
	}
}
