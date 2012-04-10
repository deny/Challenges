<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Base trait
 */
trait DetailsM
{


// FIELDS

	/**
	 * @var	string
	 */
	private $sName = gal;

	/**
	 * @var	string
	 */
	private $sSurname = anonim;


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

		$this->sName = $aRow['uemcd_name'];
		$this->sSurname = $aRow['uemcd_surname'];





		return $this;
	}

	/**
	 * Model default initialziation
	 *
	 * @param	\Model\Users\Moderator	$oOwner	owner
	 */
	public function initDefault(\Model\Users\Moderator $oOwner)
	{
		$aComponents = [self::info()];
		$aTmp = [
			'uemcd_id'	=> $oOwner->getId(),
			'uemcd_name' => $this->sName,
			'uemcd_surname' => $this->sSurname
		];
		parent::initDefault($aTmp, $aComponents);
		return $this;
	}


// GETTERS

	/**
	 * @return	string
	 */
	public function getName()
	{
		return $this->sName;
	}

	/**
	 * @return	string
	 */
	public function getSurname()
	{
		return $this->sSurname;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setName($mValue)
	{
		$this->sName = $mValue;
		$this->setDataValue(self::info()['table'], 'uemcd_name', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setSurname($mValue)
	{
		$this->sSurname = $mValue;
		$this->setDataValue(self::info()['table'], 'uemcd_surname', $mValue);
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
			'table' => 'user_e_moderator_c_detailsm',
			'alias'	=> 'uemcd',
			'key'	=> 'uemcd_id'
		];
	}
}
