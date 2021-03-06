<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Factory base trait
 */
trait AdminFactory
{
	use \Sca\DataObject\Singleton;

	/**
	 * Factory initialization
	 *
	* @param	array	$aComponents	components descritpion
	 * @return	void
	 */
	protected function init(array &$aComponents = [])
	{
		$aComponents[] = \Model\Users\Admin::info();
		parent::init($aComponents);
	}

// CREATE METHODS

	/**
	 * Create object
	 *
	 * @param	string	sEmail
	 * @param	string	sPasswd
	 * @param	string	sSalt
	 * @param	string	sName
	 * @param	string	sSurname
	 * @param	string	sRole
	 * @param	string	sStatus
	 * @param	string	sIndex
	 * @param	string	sLoginAttempts
	 * @return	\Model\Users\Admin
	 */
	public function create($sEmail, $sPasswd, $sSalt, $sName, $sSurname, $sRole, $sStatus, $sIndex, $sLoginAttempts)
	{
		$aData = $this->prepareToCreate([$sEmail, $sPasswd, $sSalt, $sName, $sSurname, $sRole, $sStatus, $sIndex, $sLoginAttempts]);

		return $this->createNewElement($aData);
	}


	/**
	 * Prepare data to create
	 *
	 * @param	array	$aData	model data
	 * @return	array
	 */
	protected function prepareToCreate(array $aData)
	{
		$aParent = parent::prepareToCreate($aData);

		$aParent['user_e_admin'] = [
				'uea_login_attempts' => $aData[8]
		];

		return $aParent;
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Users\Admin
	 */
	public function buildElement()
	{
		return new \Model\Users\Admin();
	}




}
