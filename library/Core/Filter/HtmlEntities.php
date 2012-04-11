<?php

/**
 * Klasa filtra naprawiajaca problem z ó
 */
class Core_Filter_HtmlEntities extends Zend_Filter_HtmlEntities
{
	/**
	 * Konstruktor
	 *
	 * @param	array	$aOptions	opcje
	 * @return	Core_Filter_HtmlEntities
	 */
	public function __construct($aOptions)
	{
		if(!isset($aOptions['quotestyle']))
		{
			$aOptions['quotestyle'] = ENT_QUOTES;
		}
		parent::__construct($aOptions);
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Filter_HtmlEntities::filter()
	 */
	public function filter($value)
	{
		return str_replace(
				array('&oacute;', '&Oacute;'),
				array('ó', 'Ó'),
				parent::filter($value)
			);
	}
}