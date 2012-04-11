<?php

/**
 * Tłumaczenie walidatora
 */
class Core_Validate_InArray extends Zend_Validate_InArray
{
	/**
	 * Tablica z komunikatami
	 *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_IN_ARRAY => "'%value%' jest niedozwoloną wartością",
    );

    protected $bMulti = false;

    /**
     * Kontruktor
     *
     * @param	array	$aOptions	opcje
     * @param	bool	$bMulti		czy dozwalamy na wiele wartości
     * @return	Core_Validate_InArray
     */
	public function __construct($aOptions, $bMulti = false)
	{
		parent::__construct($aOptions);
		$this->bMulti = $bMulti;
	}

    /**
     * (non-PHPdoc)
     * @see Zend_Validate_InArray::isValid()
     */
    public function isValid($value)
    {
    	return parent::isValid($value);
    	if(is_array($value) && $this->bMulti) // jeśli podano tablicę wartości
    	{
			foreach($value as $item)
			{
				if(!parent::isValid($item))
				{
					return false;
				}

				return true;
			}
    	}

    	return parent::isValid($value);
    }
}