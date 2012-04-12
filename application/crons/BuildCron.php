<?php

use \Model\Tasks\Solution;
use \Model\Tasks\SolutionFactory;

/**
 * Dokonujący oceny rozwiązań
 */
class BuildCron extends Core_Cron_Abstract
{
	/**
	 * Globalsy
	 *
	 * @var GlobalsFactory
	 */
	protected $oGlobals;

	/**
	 * (non-PHPdoc)
	 * @see Core_Cron_Abstract::run()
	 */
	public function run()
	{
		$iId = $_SERVER['REQUEST_TIME'];

		while(($oSolution = SolutionFactory::getInstance()->getForBuild($iId)) !== false)
		{
			switch($oSolution->getLanguage())
			{
				case Solution::LANGUAGE_PHP;
					$this->buildPHP($oSolution);
					break;
			}
		}
	}

	/**
	 * Buduje rozwiazanie PHP
	 */
	protected function buildPHP(\Model\Tasks\Solution $oSolution)
	{
		$sTemp = PROJECT_PATH . '/data/tmp';
		$sFile = $sTemp .'/'. str_replace([' ', '.'], '_', time() . microtime());

		// wgrywamy rozwiązanie i dane do plików

		file_put_contents($sFile . '.data', $oSolution->getTask()->getInput());
		file_put_contents($sFile . '.php', html_entity_decode($oSolution->getCode(), ENT_QUOTES, 'UTF-8'));

		// uruchomienie rozwiązania
		$aOutput = [];
		$iReturn = null;

		$iStartTime = microtime(true);
		exec('php '. $sFile .'.php "`cat '. $sFile .'.data`" 2> /dev/null', $aOutput, $iReturn);
		$iEndTime = microtime(true);

		unlink($sFile.'.php');
		unlink($sFile.'.data');

		$sResult = str_replace("\r", '', implode("\n", $aOutput));
		$sOutput = str_replace("\r", '', $oSolution->getTask()->getOutput());

		if($iReturn != 0)
		{
			$oSolution->setStatus(Solution::STATUS_ERROR);
			$oSolution->setInfo('Błąd podczas uruchamiania programu');
			$oSolution->setRunTime(null);
		}
		elseif(strcmp($sResult, $sOutput))
		{
			$oSolution->setStatus(Solution::STATUS_ERROR);
			$oSolution->setInfo('Niepoprawny wynik działania programu');
			$oSolution->setRunTime(null);
		}
		else
		{
			$oSolution->setStatus(Solution::STATUS_SUCCESS);
			$oSolution->setRunTime(round(($iEndTime - $iStartTime) * 1000));
			$oSolution->setInfo(null);
		}

		$oSolution->setWorkerId(null);
		$oSolution->save();
	}
}