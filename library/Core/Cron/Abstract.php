<?php

/**
 * Abstrakcyjna klasa crona
 *
 * @author	Daniel Kózka
 */
abstract class Core_Cron_Abstract
{
	/**
	 * Tablica z parametrami przekazanymi podczas uruchamiania crona
	 *
	 * @var	array
	 */
	protected $aRunParams;

	/**
	 * Czas wywołania crona
	 *
	 * @var	int
	 */
	protected $iTime;

	/**
	 * Czy podczas działania crona wystąpiły błędy
	 *
	 * @var	bool
	 */
	protected $bError = false;

	/**
	 * Obiekt loga
	 *
	 * @var	Zend_Log
	 */
	protected $oLog;

	/**
	 * Obiekt dostepu do bazy danych
	 *
	 * @var	Zend_Db_Adapter_Abstract
	 */
	protected $oDb;

	/**
	 * Konstruktor crona
	 *
	 * @param Zend_Log $oLog	obiekt loga
	 * @return	Core_Cron_Abstract
	 */
	public function __construct(Zend_Log $oLog)
	{
		$this->iTime = $_SERVER['REQUEST_TIME'];
		$this->oLog = $oLog;
		$this->oDb = Zend_Registry::get('db');
	}

	/**
	 * Uruchamia crona
	 *
	 * @param	array	$aParams	tablica z parametrami wywołania crona
	 */
	public function execute($aParams = array())
	{
		$this->aRunParams = $aParams;

		$this->preRun();

		try
		{
			$this->run();
		}
		catch(Exception $oExc)
		{
			$this->log($oExc);
		}

		$this->postRun();
	}

	/**
	 * Zwraca true, jeśli podzczas działanai crona wystąpiły błędy
	 *
	 * @return	bool
	 */
	public function isError()
	{
		return $this->bError;
	}

	/**
	 * Funkcja uruchamian przed uruchomieniem głównej części crona
	 */
	protected function preRun()
	{
		$this->oLog->setEventItem('sender', get_class($this));
		$this->oLog->log('Rozpoczęcie działania', Zend_Log::INFO);
	}

	/**
	 * Funkcja uruchamiana po zakończeniu głównej części crona
	 */
	protected function postRun()
	{
		if($this->bError)
		{
			$this->oLog->log('Błąd podczas działania crona', Zend_Log::ERR);
		}
		else
		{
			$this->oLog->log('Zakończenie działania', Zend_Log::INFO);
		}

	}

	/**
	 * Dodaje informację o błędzie do loga
	 *
	 * @param 	Exception 	$oException obiekt logowanego wyjątku
	 * @param	string		$sInfo		dodatkowy opis
	 */
	protected function log(Exception $oException, $sInfo = null)
	{
		$this->bError = true;

		$sMessage = "\n---------------------------------------------------\n" .
			(isset($sInfo) ? $sInfo . "\n" : '') .
			'Wyrzucono wyjątek: '. get_class($oException) .': '.
			$oException->getFile() . '('.
			$oException->getLine() .') -> ' .
			$oException->getMessage() . "\n" .
			$oException->getTraceAsString() .
			"\n---------------------------------------------------\n";

		$this->oLog->log($sMessage, Zend_Log::NOTICE); // zalogowanie błędu jako informację
	}

	/**
	 * Funkcja realizująca zadanie crona
	 */
	abstract protected function run();
}