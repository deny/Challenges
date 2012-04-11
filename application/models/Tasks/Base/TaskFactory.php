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
	 * @return	\Model\Tasks\Task
	 */
	public function create($iAuthorId, $sName, $sDescription, $sInput, $sOutput)
	{
		$aData = $this->prepareToCreate([$iAuthorId, $sName, $sDescription, $sInput, $sOutput]);

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
				't_output' => $aData[4]
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
			$aInfo = \Model\Users\Moderator::info();
			$oSelect->join(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.t_author'
			);
			$oSelect->join(
				\Model\Users\User::info()['table'] .' AS '. \Model\Users\User::info()['alias'],
				\Model\Users\User::info()['alias'] .'.'.\Model\Users\User::info()['key'] .' = '.
				\Model\Users\Moderator::info()['alias'] .'.'. \Model\Users\Moderator::info()['key']
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
		if(in_array('author', $aOptions)) // preload standard field
		{
			$aRow['_author'] = (new \Model\Users\Moderator())->init($aRow);
		}


	}


}
