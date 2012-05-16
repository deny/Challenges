<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Factory base trait
 */
trait UserFactory
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
		$aComponents[] = \Model\Users\User::info();
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
	 * @return	\Model\Users\User
	 */
	public function create($sEmail, $sPasswd, $sSalt, $sName, $sSurname, $sRole, $sStatus)
	{
		$aData = $this->prepareToCreate([$sEmail, $sPasswd, $sSalt, $sName, $sSurname, $sRole, $sStatus]);

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
		return ['user' => [
				'u_email' => $aData[0],
				'u_passwd' => $aData[1],
				'u_salt' => $aData[2],
				'u_name' => $aData[3],
				'u_surname' => $aData[4],
				'u_role' => $aData[5],
				'u_status' => $aData[6]
		]];
	}



// FACTORY METHODS


// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Users\User
	 */
	public function buildElement()
	{
		return new \Model\Users\User();
	}




}
