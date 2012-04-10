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
	 * @param	string	sRole
	 * @param	string	sStatus
	 * @return	\Model\Users\Moderator
	 */
	public function create($sEmail, $sPasswd, $sSalt, $sRole, $sStatus)
	{
		$aData = $this->prepareToCreate([$sEmail, $sPasswd, $sSalt, $sRole, $sStatus]);

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

	/**
	 * Return select object for model
	 *
	 * @param	mixed	$mFields	fields definition
	 * @param	array	$aOptions	other options
	 * @return	\Zend_Db_Select
	 */
	protected function getSelect($mFields = '*', array $aOptions = [])
	{
		$oSelect = parent::getSelect($mFields, $aOptions);

		if(in_array('details', $aOptions)) // component preload
		{
			$aThis = \Model\Users\Moderator::info();
			$aInfo = \Model\Users\DetailsM::info();
			$oSelect->joinLeft(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.'. $aThis['key']
			);
		}



		return $oSelect;
	}



	/**
	 * Prepare data to build
	 *
	 * @param	array	$aRow		db row
	 * @param	array	$aOptions	build options
	 * @return	void
	 */
	protected function prepareToBuild(array &$aRow, array $aOptions = [])
	{
		if(in_array('details', $aOptions)) // component preload
		{
			if(isset($aRow[\Model\Users\DetailsM::info()['key']]))
			{
				$aRow['_details'] = (new \Model\Users\DetailsM())->init($aRow);
			}
			else
			{
				$aRow['_details'] = false;
			}
		}


	}


}
