<?php

use \Model\Tasks\Solution;
use \Model\Tasks\SolutionFactory;

/**
 * Dokonujący oceny rozwiązań
 */
class BuildCron extends Core_Cron_Abstract
{
	/**
	 * Limit czasowy
	 */
	const TIME_LIMIT = 1;

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
				case Solution::LANGUAGE_CPP;
					$this->buildCPP($oSolution);
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

		// wstepne sprawdzenie kodu
		$sCode = html_entity_decode($oSolution->getCode(), ENT_QUOTES, 'UTF-8');

		$aProhibitetFunc = [
			'set_time_limit', 'exec'
		];

		$bGoodCode = true;
		foreach($aProhibitetFunc as $sFunc)
		{
			if(strpos($sCode, $sFunc. '(') !== false)
			{
				$oSolution->setStatus(Solution::STATUS_ERROR);
				$oSolution->setInfo('Wywołanie niedozwolonej funkcji: '. $sFunc);
				$oSolution->setRunTime(null);
				$bGoodCode = false;
				break;
			}
		}

		if($bGoodCode)
		{
			// wgrywamy rozwiązanie i dane do plików
			file_put_contents($sFile . '.data', $oSolution->getTask()->getInput());
			file_put_contents($sFile . '.php', $sCode);

			// uruchomienie rozwiązania
			$aOutput = [];
			$iReturn = null;

			$iStartTime = microtime(true);
			exec('timeout '. self::TIME_LIMIT .' php '. $sFile .'.php "`cat '. $sFile .'.data`" 2> /dev/null', $aOutput, $iReturn);
			$iEndTime = microtime(true);

			unlink($sFile.'.php');
			unlink($sFile.'.data');

			$sResult = str_replace("\r", '', implode("\n", $aOutput));
			$sOutput = str_replace("\r", '', $oSolution->getTask()->getOutput());

			if($iReturn != 0)
			{
				$oSolution->setStatus(Solution::STATUS_ERROR);

				$iRunTime = ceil($iEndTime - $iStartTime);
				if($iRunTime > 1)
				{
					$oSolution->setInfo('Czas wykonania programu większy od '. self::TIME_LIMIT .'s');
				}
				else
				{
					$oSolution->setInfo('Błąd podczas uruchomienia programu');
				}

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
		}

		$oSolution->setWorkerId(null);
		$oSolution->save();
	}

	/**
	 * Buduje rozwiązanie C++
	 */
	protected function buildCPP(\Model\Tasks\Solution $oSolution)
	{
		$sTemp = PROJECT_PATH . '/data/tmp';
		$sFile = $sTemp .'/'. str_replace([' ', '.'], '_', time() . microtime());

		// wstepne sprawdzenie kodu
		$sCode = html_entity_decode($oSolution->getCode(), ENT_QUOTES, 'UTF-8');

		$aProhibitetFunc = [

		];

		$bGoodCode = true;
		foreach($aProhibitetFunc as $sFunc)
		{
			if(strpos($sCode, $sFunc. '(') !== false)
			{
				$oSolution->setStatus(Solution::STATUS_ERROR);
				$oSolution->setInfo('Wywołanie niedozwolonej funkcji: '. $sFunc);
				$oSolution->setRunTime(null);
				$bGoodCode = false;
				break;
			}
		}

		// kompilacja (do pliku *.run)
		if($bGoodCode)
		{
			$aOutput = [];
			file_put_contents($sFile . '.cpp', $sCode);

			exec('g++ '. $sFile .'.cpp -o '. $sFile . '.run 2> /dev/null', $aOutput, $iReturn);

			if($iReturn != 0)
			{
				$oSolution->setStatus(Solution::STATUS_ERROR);
				$oSolution->setInfo('Błąd podczas kompilacji programu');
				$oSolution->setRunTime(null);

				$bGoodCode = false;
			}

			unlink($sFile.'.cpp');
		}

		if($bGoodCode)
		{
			file_put_contents($sFile . '.data', $oSolution->getTask()->getInput());

			// uruchomienie rozwiązania
			$aOutput = [];
			$iReturn = null;

			$iStartTime = microtime(true);
			exec('timeout '. self::TIME_LIMIT .' '. $sFile .'.run "`cat '. $sFile .'.data`" 2> /dev/null', $aOutput, $iReturn);
			$iEndTime = microtime(true);

			unlink($sFile.'.data');
			unlink($sFile.'.run');

			$sResult = str_replace("\r", '', implode("\n", $aOutput));
			$sOutput = str_replace("\r", '', $oSolution->getTask()->getOutput());

			if($iReturn != 0)
			{
				$oSolution->setStatus(Solution::STATUS_ERROR);

				$iRunTime = ceil($iEndTime - $iStartTime);
				if($iRunTime > 1)
				{
					$oSolution->setInfo('Czas wykonania programu większy od '. self::TIME_LIMIT .'s');
				}
				else
				{
					$oSolution->setInfo('Błąd podczas uruchomienia programu');
				}

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
		}

		$oSolution->setWorkerId(null);
		$oSolution->save();
	}
}