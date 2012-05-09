<?php

/**
 * Helper dla okruszków
 */
class View_Helper_Layout_Breadcrumbs extends Zend_View_Helper_Abstract
{
	/**
	 * Definicja okruszków
	 *
	 * @var	array
	 */
	protected $aBreadcrumbs = array();

	/**
	 * Funkcja helpera
	 *
	 * @param	array	$aBreadcrumbs	okruszki
	 * @return	string
	 */
	public function layout_Breadcrumbs($aBreadcrumbs = null)
	{
		if(is_array($aBreadcrumbs))
		{
			$this->aBreadcrumbs = $aBreadcrumbs;
			return '';
		}

		$sResult = '<nav class="submenu"><ul>';

		if(empty($this->aBreadcrumbs))
		{
			$sResult .= '<li class="selected"><a>start</a></li>';
		}
		else
		{
			$sResult .= '<li><a href="/dashboard">start</a></li>';
		}

		foreach($this->aBreadcrumbs as $sName => $sLink)
		{
			if($sLink === null)
			{
				$sResult .= '<li class="selected"><a>'. $sName .'</a></li>';
			}
			else
			{
				$sResult .= '<li><a href="'. $sLink .'">'. $sName .'</a></li>';
			}
		}

		$sResult .= '</ul></nav>';

		return $sResult;
	}
}