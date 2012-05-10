<?php

/**
 * @namespace
 */
namespace Model\Tasks;

class SolutionFactory extends \Sca\DataObject\Factory
{
	use Base\SolutionFactory {
		create as protected;
	}

	/**
	 * Tworzy nowe rozwiazanie zadania
	 *
	 * @param	\Model\Tasks\Task	$oTask			zadanie
	 * @param	\Model\Users\User	$oUser			autor rozwiązania
	 * @param	string				$sCode			kod rozwiązania
	 * @param	string				$sLangauage		zastosowany język
	 */
	public function createNew(\Model\Tasks\Task $oTask, \Model\Users\User $oUser, $sCode, $sLanguage)
	{
		return $this->create(
			$oTask->getId(),
			$oUser->getId(),
			$sCode,
			null,
			null,
			null,
			$sLanguage,
			\Model\Tasks\Solution::STATUS_NEW
		);
	}

	/**
	 * Zwraca zadanie do oceny
	 *
	 * @return	false|\Model\Tasks\Solution
	 */
	public function getForBuild($iId)
	{
		$this->oDb->query(
			'UPDATE solution'.
			' SET s_worker_id = '. $iId .', s_status = "'. Solution::STATUS_TESTING .'"'.
			' WHERE s_status = "'. Solution::STATUS_NEW .'"'.
			' LIMIT 1'
		);

		$aDbRes = $this->getSelect('*', ['task'])
						->where('s_worker_id = ?', $iId)
						->limit(1)->query()->fetchAll();

		if(empty($aDbRes))
		{
			return false;
		}

		return $this->buildObject($aDbRes[0]);
	}

	/**
	 * Zwraca najnowsze rozwiązania usera
	 *
	 * @return	array
	 */
	public function getLast(\Model\Users\User $oUser, $iCount)
	{
		$aDbRes = $this->getSelect('*', ['task'])
							->where('s_author = ?', $oUser->getId())
							->order('s_id DESC')
							->limit($iCount)
							->query()->fetchAll();

		return $this->buildList($aDbRes);
	}

	/**
	 * Zwraca liczbę dodanych rozwiązań
	 *
	 * @return	int
	 */
	public function getCount($sStatus = null)
	{
		$oWhere = new \Sca\DataObject\Where();

		if(isset($sStatus))
		{
			$oWhere->addAnd('s_status = ?', Solution::STATUS_SUCCESS);
		}
		$aDbRes = $this->oDb->select()
							->from('solution', array('COUNT(*)'))
							->where($oWhere)
							->query()->fetchAll(\Zend_Db::FETCH_COLUMN);

		return $aDbRes[0];
	}

	/**
	 * Zwraca liczbę dodanych przez usera rozwiązań
	 *
	 * @return	int
	 */
	public function getUserCount(\Model\Users\User $oUser, $sStatus = null)
	{
		$oWhere = new \Sca\DataObject\Where('s_author = ?', $oUser->getId());

		if(isset($sStatus))
		{
			$oWhere->addAnd('s_status = ?', Solution::STATUS_SUCCESS);
		}
		$aDbRes = $this->oDb->select()
							->from('solution', array('COUNT(*)'))
							->where($oWhere)
							->query()->fetchAll(\Zend_Db::FETCH_COLUMN);

		return $aDbRes[0];
	}

	/**
	 * Zwraca nazwy dostępnych jezyków programowania
	 *
	 * @return	array
	 */
	public static function getLanguages()
	{
		return [
			Solution::LANGUAGE_PHP	=> 'PHP 5.4'
		];
	}

}