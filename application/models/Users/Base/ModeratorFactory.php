<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Factory base trait
 */
trait ModeratorFactory
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
		$aComponents[] = \Model\Users\Moderator::info();
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
	 * @return	\Model\Users\Moderator
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
		$aParent = parent::prepareToCreate($aData);

		$aParent['user_e_moderator'] = [

		];

		return $aParent;
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Users\Moderator
	 */
	public function buildElement()
	{
		return new \Model\Users\Moderator();
	}




}
