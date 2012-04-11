<?php

/**
 * Helper wyświetlający pole formularza
 */
class View_Helper_Form_Field extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 *
	 * @param	string	$sLabel		opis pola
	 * @param	string	$sName		nazwa pola
	 * @param	string	$sField		pole formularza
	 * @param	array	$aAttribs	tablica z atrybutami
	 * @param	array	$aOptions	opcje dodatkowe
	 * @return	string
	 */
	public function form_Field($sLabel, $sName, $sField, $aAttribs = array(), $aOptions = array())
	{
		// pobranie błędów walidacji
		$sErrors =  $this->view->form_FieldErrors($sName);
		$bFlip = isset($aOptions['flip']) && $aOptions['flip'];

		// tag otwierający pole
		$sResult = '<div' . $this->getAttribs($aAttribs, !empty($sErrors), $bFlip) . ' >';

			// opis i pole
			$sLabel = '<label for="' . $sName . '">' . $sLabel . '</label>';
			$sField = '<span>'. $sField .'</span>';

			// dodanie do wyniku (odwrotna kolejność label<->pole)
			$sResult .= $bFlip ? $sField . $sLabel : $sLabel . $sField;

			// błędy
			$sResult .= $sErrors;

			// jeśli odwrotna kolejność to blok czyszczący opływanie
			$sResult .= $bFlip ? '<div class="clear"></div>' : '';

		$sResult .= '</div>';

		return $sResult;
	}

	/**
	 * Zwraca stringa z atrubutami HTML'a
	 *
	 * @param 	array	$aAttribs	tablica atrybutów
	 * @param	bool	$bError		czy są błędy walidacji
	 * @param	bool	$bFlip		czy odwrotna kolejnośc pole<->opis
	 * @return	string
	 */
	protected function getAttribs(array $aAttribs, $bError, $bFlip)
	{
		$sClass = 'form-field' . ($bFlip ? ' flip' : '');

		// dodanie doadtkowej klasy do pola
		if(isset($aAttribs['class']))
		{
			$aAttribs['class'] = $sClass .' '. $aAttribs['class'];
		}
		else
		{
			$aAttribs['class'] = $sClass;
		}

		if($bError) // jeśli są błędy
		{
			$aAttribs['class'] .= ' error';
		}

		$sResult = '';
		foreach($aAttribs as $sName => $sValue)
		{
			$sResult .= ' ' . $sName . '="' . $sValue . '"';
		}

		return $sResult;
	}
}