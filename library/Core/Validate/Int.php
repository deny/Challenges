<?php

/**
 * Walidator INT
 */
class Core_Validate_Int extends Zend_Validate_Int
{
	 /**
	  * Tablica z komunikatami
	  *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => 'Wymagana jest liczba całkowita',
        self::NOT_INT => "'%value%' nie jest poprawną liczbą całkowitą"
    );
}