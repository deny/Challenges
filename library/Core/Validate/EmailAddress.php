<?php

/**
 * Tłumaczenie dla walidatora adresów email
 */
class Core_Validate_EmailAddress extends Zend_Validate_EmailAddress
{
	/**
	 * Tablica z komunikatami
     * @var array
     */
    protected $_messageTemplates = array(
		self::INVALID				=> 'Podaj prawidłowy e-mail w formacie nazwa@domena.com',
		self::INVALID_FORMAT		=> 'Podaj prawidłowy e-mail w formacie nazwa@domena.com',
		self::INVALID_HOSTNAME		=> 'Podaj prawidłowy e-mail w formacie nazwa@domena.com',
		self::INVALID_MX_RECORD		=> 'Podaj prawidłowy e-mail w formacie nazwa@domena.com',
		self::DOT_ATOM				=> 'Podaj prawidłowy e-mail w formacie nazwa@domena.com',
		self::QUOTED_STRING			=> 'Podaj prawidłowy e-mail w formacie nazwa@domena.com',
		self::INVALID_LOCAL_PART	=> 'Podaj prawidłowy e-mail w formacie nazwa@domena.com',
		self::LENGTH_EXCEEDED		=> 'Podaj prawidłowy e-mail w formacie nazwa@domena.com'
	);

	/**
	 * Zwraca błędy tylko z walidatora emaili (uwua dodatkowe błędy z walidatora hostname)
	 * @see Zend_Validate_Abstract::getMessages()
	 */
	public function getMessages()
	{
		$aMsg = parent::getMessages();

		foreach($aMsg as $sErr => $aVal)
		{
			if(strpos($sErr,'hostname') !== false)
			{
				unset($aMsg[$sErr]);
			}
		}

		return $aMsg;
	}
}