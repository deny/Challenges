<?php

/**
 * Zwraca html'a z błędami dla wybranego pola
 */
class View_Helper_Form_FieldErrors extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 *
	 * @param	string	$sField	nazwa pola
	 * @return	string
	 */
	public function form_FieldErrors($sField)
	{
		$sResult = '';
		if(!empty($this->view->aErrors[$sField]))
		{
			$sResult .= '<div class="error-inline">'.
							$this->view->formErrors($this->view->aErrors[$sField]) .
						'</div>';
		}

		return $sResult;
	}
}