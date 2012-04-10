<?php

/**
 * @namespace
 */
namespace Model\Tasks\Base;

/**
 * Factory base trait
 */
trait SolutionFactory
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
		$aComponents[] = \Model\Tasks\Solution::info();
		parent::init($aComponents);
	}

// CREATE METHODS

	/**
	 * Create object
	 *
	 * @param	int	iTaskId
	 * @param	int	iAuthorId
	 * @param	string	sCode
	 * @param	string	sInfo
	 * @param	int	iRunTime
	 * @param	int	iWorkerId
	 * @param	string	sLanguage
	 * @param	string	sStatus
	 * @return	\Model\Tasks\Solution
	 */
	public function create($iTaskId, $iAuthorId, $sCode, $sInfo, $iRunTime, $iWorkerId, $sLanguage, $sStatus)
	{
		$aData = $this->prepareToCreate([$iTaskId, $iAuthorId, $sCode, $sInfo, $iRunTime, $iWorkerId, $sLanguage, $sStatus]);

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
		return ['solution' => [
				's_task' => $aData[0],
				's_author' => $aData[1],
				's_code' => $aData[2],
				's_info' => $aData[3],
				's_run_time' => $aData[4],
				's_worker_id' => $aData[5],
				's_language' => $aData[6],
				's_status' => $aData[7]
		]];
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Tasks\Solution
	 */
	public function buildElement()
	{
		return new \Model\Tasks\Solution();
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

		if(in_array('task', $aOptions)) // zawiera pole
		{
			$aThis = \Model\Tasks\Solution::info();
			$aInfo = \Model\Tasks\Task::info();
			$oSelect->join(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.d_user'
			);
		}

		if(in_array('author', $aOptions)) // zawiera pole
		{
			$aThis = \Model\Tasks\Solution::info();
			$aInfo = \Model\Users\User::info();
			$oSelect->join(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.d_user'
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
		if(in_array('task', $aOptions)) // preload standard field
		{
			$aRow['_task'] = (new \Model\Tasks\Task())->init($aRow);
		}

		if(in_array('author', $aOptions)) // preload standard field
		{
			$aRow['_author'] = (new \Model\Users\User())->init($aRow);
		}


	}


}
