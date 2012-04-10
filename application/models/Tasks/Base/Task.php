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


// FIELDS

	/**
	 * @var	int
	 */
	private $iAuthorId = null;

	/**
	 * @var	\Model\Tasks\Users:Moderator
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

		$this->sName = $aRow['t_name'];
		$this->sDescription = $aRow['t_description'];
		$this->sInput = $aRow['t_input'];
		$this->sOutput = $aRow['t_output'];



		if(isset($aRow['_author']))
		{
			$this->oAuthor = $aRow['_author'];
		}



		return $this;
	}


// GETTERS

	/**
	 * @return	\Model\Tasks\Users:Moderator
	 */
	public function getAuthorId()
	{
		return $this->iAuthorId;
	}

	/**
	 * @return	\Model\Tasks\Users:Moderator
	 */
	public function getAuthor()
	{
		if(!isset($this->oAuthor))
		{
			$this->oAuthor = \Model\Tasks\Users:ModeratorFactory::getInstance()->getOne($this->iAuthorId);
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
