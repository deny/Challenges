<?php

class Core_Filter_Input extends Zend_Filter_Input
{
	/**
	 * Domyślne opcje
	 *
	 * @var	array
	 */
	protected $aDefOptions = array(
		'notEmptyMessage' 	=> 'Pole nie może być puste',
		'missingMessage' 	=> 'Pole nie może być puste',
		'presence'			=> 'required'
	);

	/**
	 * Domyślne filry
	 *
	 * @var	array
	 */
	protected $aDefFitlers = array(
		'*' => 'StringTrim'
	);

	/**
	 * Oryginalne walidatory
	 *
	 * @var	array
	 */
	protected $aValidators;

	/**
	 * Konstruktor filtra
	 *
	 * @param array $aFilters
	 * @param array $aValidators
	 * @param array $aData
	 * @param array $aOptions
	 */
	public function __construct($aFilters, $aValidators, array $aData = null, array $aOptions = null)
	{
		$aOptions = isset($aOptions) ? $aOptions + $this->aDefOptions : $this->aDefOptions;
		$aFilters = isset($aFilters) ? $aFilters + $this->aDefFitlers : $this->aDefFitlers;

		parent::__construct($aFilters, $aValidators, $aData, $aOptions);

		$this->aValidators = $aValidators;

		$this->setDefaultEscapeFilter(
		    new Core_Filter_HtmlEntities(array(
				'charset'	 => 'utf-8'
		    ))
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Filter_Input::getMessages()
	 */
	public function getMessages()
	{
		$aTmp =  parent::getMessages();

		// podmiana komunikatów o braku wartości (jeśli trzeba)
		foreach($aTmp as $sField => $aErrors)
		{
			if(isset($aErrors['isEmpty']) && isset($this->aValidators[$sField]['emptyMsg']))
			{
				$aTmp[$sField]['isEmpty'] = $this->aValidators[$sField]['emptyMsg'];
			}
		}

		return $aTmp;
	}
}