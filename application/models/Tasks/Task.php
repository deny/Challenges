<?php

/**
 * @namespace
 */
namespace Model\Tasks;

class Task extends \Sca\DataObject\Element
{
	const ACCESS_PUBLIC = 'public';
	const ACCESS_PRIVATE = 'private';

	use Base\Task;

	/**
	 * Przypina użytkowników do zadania
	 *
	 * @param	array	$aUserIds	numery id userów
	 * @return	void
	 */
	public function resetParticipants(array $aUserIds)
	{
		$this->oDb->delete('task_j_participants', 't_id = '. $this->getId());

		if(!empty($aUserIds))
		{
			foreach($aUserIds as $iId)
			{
				$aTmp[] = '('. $this->getId() .', '. $iId .')';
			}

			$this->oDb->query(
				'INSERT INTO task_j_participants VALUES '. implode(',', $aTmp)
			);
		}
	}
}