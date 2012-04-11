<?php

/**
 * Walidator Callback z możliwością definiowania wiadomości w konstruktorze
 */
class Core_Validate_Callback extends Zend_Validate_Callback
{
	/**
	 * Konstruktor
	 *
	 * @param	array	$aOptions	opcje konfugiracyjne
	 * @return	Core_Validate_Callback
	 */
	public function __construct(array $aOptions)
	{
		parent::__construct($aOptions);

		if(isset($aOptions['message']))
		{
			$this->_messageTemplates = array(
		        self::INVALID_VALUE    => $aOptions['message'],
		        self::INVALID_CALLBACK => 'Błędna funkcja walidujaca',
		    );
		}
	}
}