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
}