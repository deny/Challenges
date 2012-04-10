<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Base trait
 */
trait User
{
	// CONST ROLE_USER = 'user';
	// CONST ROLE_MOD = 'mod';
	// CONST ROLE_ADMIN = 'admin';

	// CONST STATUS_INACTIVE = 'inactive';
	// CONST STATUS_ACTIVE = 'active';
	// CONST STATUS_BANNED = 'banned';



// FIELDS

	/**
	 * @var	string
	 */
	private $sEmail = null;

	/**
	 * @var	string
	 */
	private $sPasswd = null;

	/**
	 * @var	string
	 */
	private $sSalt = null;

	/**
	 * @var	\Model\Users\DetailsU
	 */
	private $oDetails = null;

	/**
	 * @var	string
	 */
	private $sRole = null;

	/**
	 * @var	string
	 */
	private $sStatus = null;


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

		$this->sEmail = $aRow['email'];
		$this->sPasswd = $aRow['passwd'];
		$this->sSalt = $aRow['salt'];
		$this->sRole = $aRow['role'];
		$this->sStatus = $aRow['status'];


		if(isset($aRow['_details']))
		{
			$this->oDetails = $aRow['_details'] ?
									$aRow['_details'] :
									(new \Model\Users\DetailsU())->initDefault($this);
		}




		return $this;
	}


// GETTERS

	/**
	 * @return	string
	 */
	public function getEmail()
	{
		return $this->sEmail;
	}

	/**
	 * @return	string
	 */
	public function getPasswd()
	{
		return $this->sPasswd;
	}

	/**
	 * @return	string
	 */
	protected function getSalt()
	{
		return $this->sSalt;
	}

	/**
	 * @return	\Model\Users\DetailsU
	 */
	public function getDetails()
	{
		if(!isset($this->oDetails))
		{
			try
			{
				$this->oDetails = \Model\Users\DetailsUFactory::getInstance()->getOne($this->getId());
			}
			catch(\Sca\DataObject\Exception $oExc) // no data - create default object
			{
				$this->oDetails = (new \Model\Users\DetailsU())->initDefault($this);
			}
		}

		return $this->oDetails;
	}

	/**
	 * @return	string
	 */
	public function getRole()
	{
		return $this->sRole;
	}

	/**
	 * @return	string
	 */
	public function getStatus()
	{
		return $this->sStatus;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setEmail($mValue)
	{
		$this->sEmail = $mValue;
		$this->setDataValue(self::info()['table'], 'u_email', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	protected function setPasswd($mValue)
	{
		$this->sPasswd = $mValue;
		$this->setDataValue(self::info()['table'], 'u_passwd', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	protected function setSalt($mValue)
	{
		$this->sSalt = $mValue;
		$this->setDataValue(self::info()['table'], 'u_salt', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setRole($mValue)
	{
		$this->sRole = $mValue;
		$this->setDataValue(self::info()['table'], 'u_role', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setStatus($mValue)
	{
		$this->sStatus = $mValue;
		$this->setDataValue(self::info()['table'], 'u_status', $mValue);
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
			'table' => 'user',
			'alias'	=> 'u',
			'key'	=> 'u_id'
		];
	}
}
