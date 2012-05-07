<?php

/**
 * Helper zwracajacy klasy dla layoutu
 */
class View_Helper_Layout_Class extends Zend_View_Helper_Abstract
{
	/**
	 * Klasy dodatkowe dla contentu
	 *
	 * @var	string
	 */
	protected $sPageWrapper = '';

	/**
	 * Funkcja helpera
	 *
	 * @param	string	$sPageWrapper	klasa dodatkowa dla contentu
	 * @return	View_Helper_Layout_Class
	 */
	public function layout_Class($sPageWrapper = null)
	{
		if(isset($sPageWrapper))
		{
			$this->sPageWrapper = $sPageWrapper;
		}

		return $this;
	}

	/**
	 * Zwraca klasy dodatkowe dla contentu
	 *
	 * @return	string
	 */
	public function getPageWrapper()
	{
		return $this->sPageWrapper;
	}
}