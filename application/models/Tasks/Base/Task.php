<?php

/**
 * @namespace
 */
namespace Model\Tasks\Base;

/**
 * Base trait
 */
trait Task
{
	// CONST ACCESS_PUBLIC = 'public';
	// CONST ACCESS_PRIVATE = 'private';



// FIELDS

	/**
	 * @var	int
	 */
	private $iAuthorId = null;

	/**
	 * @var	\Model\Users\User
	 */
	private $oAuthor = null;

	/**
	 * @var	string
	 */
	private $sName = null;

	/**
	 * @var	string
	 */
	private $sDescription = null;

	/**
	 * @var	string
	 */
	private $sInput = null;

	/**
	 * @var	string
	 */
	private $sOutput = null;

	/**
	 * @var	string
	 */
	private $sAccess = null;

	/**
	 * @var	array
	 */
	private $aParticipants = null;


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

		$this->iAuthorId = $aRow['t_author'];
		$this->sName = $aRow['t_name'];
		$this->sDescription = $aRow['t_description'];
		$this->sInput = $aRow['t_input'];
		$this->sOutput = $aRow['t_output'];
		$this->sAccess = $aRow['t_access'];



		if(isset($aRow['_author']))
		{
			$this->oAuthor = $aRow['_author'];
		}

		if(isset($aRow['_participants']))
		{
			$this->oParticipants = $aRow['_participants'];
		}



		return $this;
	}


// GETTERS

	/**
	 * @return	\Model\Users\User
	 */
	public function getAuthorId()
	{
		return $this->iAuthorId;
	}

	/**
	 * @return	\Model\Users\User
	 */
	public function getAuthor()
	{
		if(!isset($this->oAuthor))
		{
			$this->oAuthor = \Model\Users\UserFactory::getInstance()->getOne($this->iAuthorId);
		}

		return $this->oAuthor;
	}

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
	public function getDescription()
	{
		return $this->sDescription;
	}

	/**
	 * @return	string
	 */
	public function getInput()
	{
		return $this->sInput;
	}

	/**
	 * @return	string
	 */
	public function getOutput()
	{
		return $this->sOutput;
	}

	/**
	 * @return	string
	 */
	public function getAccess()
	{
		return $this->sAccess;
	}

	/**
	 * @return	array
	 */
	public function getParticipants()
	{
		if(!isset($this->oParticipants))
		{
			$this->oParticipants = \Model\Users\UserFactory::getInstance()->getTaskParticipants($this->getId());
		}

		return $this->oParticipants;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setName($mValue)
	{
		$this->sName = $mValue;
		$this->setDataValue(self::info()['table'], 't_name', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setDescription($mValue)
	{
		$this->sDescription = $mValue;
		$this->setDataValue(self::info()['table'], 't_description', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setInput($mValue)
	{
		$this->sInput = $mValue;
		$this->setDataValue(self::info()['table'], 't_input', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setOutput($mValue)
	{
		$this->sOutput = $mValue;
		$this->setDataValue(self::info()['table'], 't_output', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setAccess($mValue)
	{
		$this->sAccess = $mValue;
		$this->setDataValue(self::info()['table'], 't_access', $mValue);
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
			'table' => 'task',
			'alias'	=> 't',
			'key'	=> 't_id'
		];
	}
}
