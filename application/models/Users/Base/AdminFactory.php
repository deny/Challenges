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
	 * @param	string	sRole
	 * @param	string	sStatus
	 * @param	string	sLoginAttempts
	 * @return	\Model\Users\Admin
	 */
	public function create($sEmail, $sPasswd, $sSalt, $sRole, $sStatus, $sLoginAttempts)
	{
		$aData = $this->prepareToCreate([$sEmail, $sPasswd, $sSalt, $sRole, $sStatus, $sLoginAttempts]);

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
				'uea_login_attempts' => $aData[5]
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
