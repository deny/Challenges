<?php

/**
 * @namespace
 */
namespace Model\Tasks\Base;

/**
 * Factory base trait
 */
trait TaskFactory
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
		$aComponents[] = \Model\Tasks\Task::info();
		parent::init($aComponents);
	}

// CREATE METHODS

	/**
	 * Create object
	 *
	 * @param	int	iAuthorId
	 * @param	string	sName
	 * @param	string	sDescription
	 * @param	string	sInput
	 * @param	string	sOutput
	 * @param	string	sAccess
	 * @return	\Model\Tasks\Task
	 */
	public function create($iAuthorId, $sName, $sDescription, $sInput, $sOutput, $sAccess)
	{
		$aData = $this->prepareToCreate([$iAuthorId, $sName, $sDescription, $sInput, $sOutput, $sAccess]);

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
		return ['task' => [
				't_author' => $aData[0],
				't_name' => $aData[1],
				't_description' => $aData[2],
				't_input' => $aData[3],
				't_output' => $aData[4],
				't_access' => $aData[5]
		]];
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Tasks\Task
	 */
	public function buildElement()
	{
		return new \Model\Tasks\Task();
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

		if(in_array('author', $aOptions)) // zawiera pole
		{
			$aThis = \Model\Tasks\Task::info();
			$aInfo = \Model\Users\User::info();
			$oSelect->join(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.t_author'
			);

		}



		return $oSelect;
	}


	/**
	 * Build model list
	 *
	 * @param	array	$aDbResult	database result
	 * @return	array
	 */
	protected function buildList(array &$aDbResult, array $aOptions = [])
	{
		if(empty($aDbResult))
		{
			return array();
		}

		if(in_array('participants', $aOptions))
		{
			$aIds = [];
			foreach($aDbResult as $aRow)
			{
				$aIds[] = $aRow[\Model\Tasks\Task::info()['key']];
			}

			$aTmp = \Model\Users\UserFactory::getInstance()->getTaskParticipants($aIds);

			foreach($aDbResult as &$aRow)
			{
				$aRow['_participants'] = $aTmp[$aRow[\Model\Tasks\Task::info()['key']]];
			}
		}



		return parent::buildList($aDbResult, $aOptions);
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
		if(in_array('author', $aOptions)) // preload standard field
		{
			$aRow['_author'] = (new \Model\Users\User())->init($aRow);
		}


	}


}
