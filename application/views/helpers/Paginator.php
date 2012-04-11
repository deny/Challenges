<?php

/**
 * Nadpisany helper paginatora
 */
class View_Helper_Paginator extends \Zend_View_Helper_PaginationControl
{
	/**
	 * Funkcja helpera
	 *
	 * @param	\Zend_Paginator		$oPaginator	obiekt paginatora
	 * @param	array				$aOptions	opcje paginatora
	 * @return	string
	 */
	public function paginator(\Zend_Paginator $oPaginator, array $aOptions)
	{
		return parent::paginationControl(
			$oPaginator,
			'Sliding',
			'app-partials/paginator.phtml',
			$aOptions
		);
	}
}