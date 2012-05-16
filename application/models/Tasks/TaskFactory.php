<?php

/**
 * @namespace
 */
namespace Model\Tasks;

class TaskFactory extends \Sca\DataObject\Factory
{
	use Base\TaskFactory;

	/**
	 * Zwraca najnowsze zadania
	 *
	 * @return	array
	 */
	public function getLast($iCount)
	{
		$aDbRes = $this->getSelect('*', ['author'])
							->order('t_id DESC')
							->limit($iCount)
							->query()->fetchAll();

		return $this->buildList($aDbRes);
	}

	/**
	 * Zwraca liczbę dodanych zadań
	 *
	 * @return	int
	 */
	public function getCount()
	{
		$aDbRes = $this->oDb->select()
							->from('task', array('COUNT(*)'))
							->query()->fetchAll(\Zend_Db::FETCH_COLUMN);

		return $aDbRes[0];
	}

	/**
	 * Zwraca liczbę dodanych prze usera zadań
	 *
	 * @return	int
	 */
	public function getUserCount(\Model\Users\User $oUser)
	{
		$aDbRes = $this->oDb->select()
							->from('task', array('COUNT(*)'))
							->where('t_author = ?', $oUser->getId())
							->query()->fetchAll(\Zend_Db::FETCH_COLUMN);

		return $aDbRes[0];
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

		if(in_array('list', $aOptions)) // zawiera pole
		{
			$oSelect->joinLeft('task_j_participants AS tjp', 'tjp.t_id = t.t_id', '');
		}

		return $oSelect;
	}
}