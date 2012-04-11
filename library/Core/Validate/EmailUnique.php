<?php

/**
 * Walidator unikalności emaila
 */
class Core_Validate_EmailUnique extends Zend_Validate_Abstract
{
	/**
	 * Stała określajaca błąð
	 *
	 * @var	string
	 */
	const NOT_UNIQUE = 'email_not_unique';

	/**
	 * Obiekt edytowanego usera (pomijamy jego emaila)
	 *
	 * @var	User
	 */
	protected $oUser;

	/**
	 * Tablica z komunikatami
     * @var array
     */
    protected $_messageTemplates = array(
		self::NOT_UNIQUE => 'Podany email występuje już w bazie',
	);

	/**
	 * Konstuktor
	 *
	 * @param	User	$oUser	obiekt edytowanego usera (pomijamy jego emaila)
	 * @return	Core_Validate_EmailUnique
	 */
	public function __construct($oUser)
	{
		$this->oUser = $oUser;
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Validate_Interface::isValid()
	 */
	public function isValid($sValue)
	{
		if(empty($sValue))
		{
			$this->_error(self::NOT_UNIQUE);
			return false;
		}

		if(isset($this->oUser) && $this->oUser->getEmail() == $sValue)
		{
			return true;
		}

		$oDb = Zend_Registry::get('db');
		$aDbRes = $oDb->select()
					->from('users', 'user_id')
					->where('email = ?', $sValue)
					->limit(1)->query()->fetchAll();

		if(empty($aDbRes))
		{
			return true;
		}

		$this->_error(self::NOT_UNIQUE);
		return false;
	}
}