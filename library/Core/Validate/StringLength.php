<?php

/**
 * Walidator długości stringa
 */
class Core_Validate_StringLength extends Zend_Validate_StringLength
{
	 /**
	  * Tablica z komunikatami
	  *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID   => 'Błędny ciąg znaków.',
        self::TOO_SHORT => 'Zbyt krótki tekst (minimalna długość to %min%)',
        self::TOO_LONG  => 'Zbyt długi tekst (maksymalna długość to %max%)',
    );
}