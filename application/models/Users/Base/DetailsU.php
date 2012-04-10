<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Base trait
 */
trait DetailsU
{


// FIELDS

	/**
	 * @var	string
	 */
	private $sName = 'gal';

	/**
	 * @var	string
	 */
	private $sSurname = 'anonim';

	/**
	 * @var	int
	 */
	private $iIndexId = null;


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

		$this->sName = $aRow['ucd_name'];
		$this->sSurname = $aRow['ucd_surname'];
		$this->iIndexId = $aRow['ucd_index_id'];





		return $this;
	}

	/**
	 * Model default initialziation
	 *
	 * @param	\Model\Users\User	$oOwner	owner
	 */
	public function initDefault(\Model\Users\User $oOwner)
	{
		$aComponents = [self::info()];
		$aTmp = [
			'ucd_id'	=> $oOwner->getId(),
			'ucd_name' => $this->sName,
			'ucd_surname' => $this->sSurname,
			'ucd_index_id' => $this->iIndexId
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

	/**
	 * @return	int
	 */
	public function getIndexId()
	{
		return $this->iIndexId;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setName($mValue)
	{
		$this->sName = $mValue;
		$this->setDataValue(self::info()['table'], 'ucd_name', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setSurname($mValue)
	{
		$this->sSurname = $mValue;
		$this->setDataValue(self::info()['table'], 'ucd_surname', $mValue);
		return $this;
	}

	/**
	 * @param	int	$mValue		new value
	 * @return	void
	 */
	public function setIndexId($mValue)
	{
		$this->iIndexId = $mValue;
		$this->setDataValue(self::info()['table'], 'ucd_index_id', $mValue);
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
			'table' => 'user_c_detailsu',
			'alias'	=> 'ucd',
			'key'	=> 'ucd_id'
		];
	}
}
