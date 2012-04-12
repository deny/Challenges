<?php

/**
 * Klasa wysyłająca logi mailem
 *
 * @author	Daniel Kózka
 */
class Core_Log_Writer_Mail extends Zend_Log_Writer_Abstract
{
	/**
	 * Format loga
	 */
	const FORMAT = "%date% [%sender%]: %message%";
	const FORMAT_GENERAL = "%date%: %message%";

	/**
	 * Treść wysyłanej wiadomosci
	 *
	 * @var	string
	 */
	protected $sMessage = '';

	/**
	 * Adres email na który wysyłamy logi
	 *
	 * @var	string
	 */
	protected $sEmail;

	/**
	 * Temat wiadomości
	 *
	 * @var	string
	 */
	protected $sSubject = 'Wystąpiły błędy w cronie ';

	/**
	 * Konstruktor
	 *
	 * @param	string	$sEmail		adres email
	 * @param	string	$sName		nazwa crona
	 * @param	bool	$bExtName	czy rozserzać subjecta
	 * @param	string	$sFormat	format loga
	 * @return	Core_Log_Writer_Mail
	 */
	public function __construct($sEmail, $sName, $bExtName = true, $sFormat = self::FORMAT)
	{
		$this->_formatter = new Zend_Log_Formatter_Simple($sFormat);
		$this->sEmail = $sEmail;

		if($bExtName)
		{
			$this->sSubject .= $sName;
		}
		else
		{
			$this->sSubject = $sName;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Zend/Log/Writer/Zend_Log_Writer_Abstract#_write($event)
	 */
	protected function _write($aEvent)
	{
		if($aEvent['priority'] == Zend_Log::ERR)
		{
			$aEvent['date'] = date('Y-m-d H:i:s');
			$this->sMessage .= $this->_formatter->format($aEvent);
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Zend/Log/Writer/Zend_Log_Writer_Abstract#shutdown()
	 */
	public function shutdown()
	{
		if(!empty($this->sMessage))
		{
			$oMail = new Core_Mail();
			$oMail->addTo($this->sEmail)
					->setFromSite('error')
					->setSubject($this->sSubject)
					->setBodyText($this->sMessage, 'utf-8')
					->send();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Zend/Log/Zend_Log_FactoryInterface#factory($config)
	 */
	static public function factory($config)
	{
		$aConfig = Core_Log_Writer_File::_parseConfig($config);
		return new self($aConfig['email'], $aConfig['cron']);
	}
}