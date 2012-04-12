<?php

/**
 * Klasa zapisująca logi do pliku
 *
 * @author	Daniel Kózka
 */
class Core_Log_Writer_File extends Zend_Log_Writer_Abstract
{
	// TYPY logów
	const TYPE_MAIN = 'main';
	const TYPE_CRON = 'cron';
	const TYPE_GENERAL = 'general';

	/**
	 * Format loga
	 */
	const FORMAT = "[%priorityName%]\t %date% [%sender%]:\t %message%";
	const FORMAT_GENERAL = "[%priorityName%]\t %date% \t %message%";

	/**
	 * Obiekt pliku
	 *
	 * @var SplFileObject
	 */
	protected $oFile = null;

	/**
	 * Ścieżka do pliku z logiem
	 *
	 * @var	string
	 */
	protected $sFile;

	/**
	 * Akceptowane poziomy komunikatów
	 *
	 * @var	array
	 */
	protected $aAcceptMsg = array();

	/**
	 * Konstruktor
	 *
	 * @param	string 	$sLogDir	ścieżka do katalogu z logami
	 * @param	string	$sLogType	typ loga
	 * @return	Core_Log_Writer_File
	 */
	public function __construct($sLogDir, $sLogType, $sName = '')
	{
		$this->_formatter = new Zend_Log_Formatter_Simple(self::FORMAT);

		switch($sLogType)
		{
			case self::TYPE_MAIN:
				$sLogDir .= '/main/' . $sName;
				$this->aAcceptMsg[] = Zend_Log::ERR;
				$this->aAcceptMsg[] = Zend_Log::INFO;
				break;
			case self::TYPE_CRON:
				$sLogDir .= '/crons/' . $sName;
				$this->aAcceptMsg[] = Zend_Log::NOTICE;
				break;
			case self::TYPE_GENERAL:
				$this->_formatter = new Zend_Log_Formatter_Simple(self::FORMAT_GENERAL);
				$sLogDir .= '/general/' . $sName;
				$this->aAcceptMsg[] = Zend_Log::ERR;
				$this->aAcceptMsg[] = Zend_Log::INFO;
				break;
		}

		if(!file_exists($sLogDir)) // utworzenie ścieżki, jeśli nie istniała
		{
			mkdir($sLogDir, 0775, true);
			chmod($sLogDir, 0775);
		}

		$this->sFile = $sLogDir .'/'. date('Y-m-d') . '.log'; // ścieżka do pliku
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Zend/Log/Writer/Zend_Log_Writer_Abstract#_write($event)
	 */
	protected function _write($aEvent)
	{
		if(in_array($aEvent['priority'], $this->aAcceptMsg)) // jeśli to komunikat z akceptowanego poziomu
		{
			if($this->oFile === null) // otwarcie pliku jeśli jeszcze tego nie zrobiono
			{
				$bFile = file_exists($this->sFile);

				$this->oFile = new SplFileObject($this->sFile, 'a'); // otwarcie i dopisywanie

				if(!$bFile) // plik jest nowy - zmieniamy mu uprawnienia
				{
					chmod($this->sFile, 0664);
				}
			}

			$aEvent['date'] = date('Y-m-d H:i:s');
			$sMsg = $this->_formatter->format($aEvent);

			if($this->oFile->flock(LOCK_EX)) // jeśli udalo się nalożyć blokadę
			{
				$this->oFile->fwrite($sMsg . "\n");
				$this->oFile->flock(LOCK_UN);
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Zend/Log/Writer/Zend_Log_Writer_Abstract#shutdown()
	 */
	public function shutdown()
	{
		unset($this->oFile);
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Zend/Log/Zend_Log_FactoryInterface#factory($config)
	 */
	static public function factory($config)
	{
		$aConfig = Core_Log_Writer_File::_parseConfig($config);
		return new self($aConfig['dir'], $aConfig['type'], $aConfig['cron']);
	}
}