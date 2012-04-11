<?php

/**
 * Dwa multiselecty z przerzucaniem elmentów
 */
class View_Helper_Form_MultiChoose extends Zend_View_Helper_Abstract
{
	/**
	 * Nazwa pola
	 *
	 * @var	string
	 */
	protected $sName;

	/**
	 * Możliwe do wybrania opcje
	 *
	 * @var	array
	 */
	protected $aOptions = array();

	/**
	 * Wybrane wartości
	 *
	 * @var	array
	 */
	protected $aValues = array();

	/**
	 * Atrybuty
	 *
	 * @var	array
	 */
	protected $aAttribs;


	/**
	 * Funkcja helepera
	 *
	 * @param	string	$sName		nazwa pola
	 * @param	mixed	$aValues	wartości
	 * @param	array	$aOptions	możliwe opcje
	 * @param	array	$aAttribs	atrybuty
	 * @return	string
	 */
	public function form_MultiChoose($sName, $aValues = array(), $aAttribs = array(), $aOptions = array())
	{
		$this->sName = $sName;
		$this->aAttribs = $aAttribs;


		// usuwamy z listy wybrane wartości
		foreach($aValues as $sValue)
		{
			if(isset($aOptions[$sValue]))
			{
				$this->aValues[$sValue] = $aOptions[$sValue];
				unset($aOptions[$sValue]);
			}
		}

		$this->aOptions = $aOptions;

		return $this->getHtml();
	}

	/**
	 * Zwraca HTML'a
	 *
	 * @return	string
	 */
	protected function getHtml()
	{
		$this->view->headScript()->appendFile('/js/formMultiChoose.js');

		// dodajemy atrybuty oznaczajace multiselecty
		$this->aAttribs['options']['multiple'] = true;
		$this->aAttribs['values']['multiple'] = true;
		$this->aAttribs['options']['class'] = 'options';
		$this->aAttribs['values']['Class'] = 'selected';

		// wygenerowanie HTML'a
		return '<div id="'. $this->sName . '_box" class="formMultiChoose">' .
			$this->view->formSelect(
				$this->sName . '_options', '', $this->aAttribs['options'], $this->aOptions
			) .
			'<div class="multiButtons">'.
				'<button id="'. $this->sName.'_add" class="add">&raquo;</button>'.
				'<button id="'. $this->sName.'_rmv" class="rmv">&laquo;</button>'.
			'</div>'.
			$this->view->formSelect(
				$this->sName, '', $this->aAttribs['values'], $this->aValues
			) .

		'</div>';
	}
}