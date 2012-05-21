<?php

/**
 * @namespace
 */
namespace Model\Tasks\Base;

/**
 * Base trait
 */
trait Solution
{
	// CONST LANGUAGE_PHP = 'php';
	// CONST LANGUAGE_CPP = 'cpp';

	// CONST STATUS_NEW = 'new';
	// CONST STATUS_TESTING = 'testing';
	// CONST STATUS_SUCCESS = 'success';
	// CONST STATUS_ERROR = 'error';



// FIELDS

	/**
	 * @var	int
	 */
	private $iTaskId = null;

	/**
	 * @var	\Model\Tasks\Task
	 */
	private $oTask = null;

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
	private $sCode = null;

	/**
	 * @var	string
	 */
	private $sInfo = null;

	/**
	 * @var	int
	 */
	private $iRunTime = null;

	/**
	 * @var	int
	 */
	private $iWorkerId = null;

	/**
	 * @var	string
	 */
	private $sLanguage = null;

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

		$this->iTaskId = $aRow['s_task'];
		$this->iAuthorId = $aRow['s_author'];
		$this->sCode = $aRow['s_code'];
		$this->sInfo = $aRow['s_info'];
		$this->iRunTime = $aRow['s_run_time'];
		$this->iWorkerId = $aRow['s_worker_id'];
		$this->sLanguage = $aRow['s_language'];
		$this->sStatus = $aRow['s_status'];



		if(isset($aRow['_task']))
		{
			$this->oTask = $aRow['_task'];
		}

		if(isset($aRow['_author']))
		{
			$this->oAuthor = $aRow['_author'];
		}



		return $this;
	}


// GETTERS

	/**
	 * @return	\Model\Tasks\Task
	 */
	public function getTaskId()
	{
		return $this->iTaskId;
	}

	/**
	 * @return	\Model\Tasks\Task
	 */
	public function getTask()
	{
		if(!isset($this->oTask))
		{
			$this->oTask = \Model\Tasks\TaskFactory::getInstance()->getOne($this->iTaskId);
		}

		return $this->oTask;
	}

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
	public function getCode()
	{
		return $this->sCode;
	}

	/**
	 * @return	string
	 */
	public function getInfo()
	{
		return $this->sInfo;
	}

	/**
	 * @return	int
	 */
	public function getRunTime()
	{
		return $this->iRunTime;
	}

	/**
	 * @return	int
	 */
	public function getWorkerId()
	{
		return $this->iWorkerId;
	}

	/**
	 * @return	string
	 */
	public function getLanguage()
	{
		return $this->sLanguage;
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
	public function setCode($mValue)
	{
		$this->sCode = $mValue;
		$this->setDataValue(self::info()['table'], 's_code', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setInfo($mValue)
	{
		$this->sInfo = $mValue;
		$this->setDataValue(self::info()['table'], 's_info', $mValue);
		return $this;
	}

	/**
	 * @param	int	$mValue		new value
	 * @return	void
	 */
	public function setRunTime($mValue)
	{
		$this->iRunTime = $mValue;
		$this->setDataValue(self::info()['table'], 's_run_time', $mValue);
		return $this;
	}

	/**
	 * @param	int	$mValue		new value
	 * @return	void
	 */
	public function setWorkerId($mValue)
	{
		$this->iWorkerId = $mValue;
		$this->setDataValue(self::info()['table'], 's_worker_id', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setLanguage($mValue)
	{
		$this->sLanguage = $mValue;
		$this->setDataValue(self::info()['table'], 's_language', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setStatus($mValue)
	{
		$this->sStatus = $mValue;
		$this->setDataValue(self::info()['table'], 's_status', $mValue);
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
			'table' => 'solution',
			'alias'	=> 's',
			'key'	=> 's_id'
		];
	}
}
