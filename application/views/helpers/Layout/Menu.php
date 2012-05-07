<?php

/**
 * Helper dla menu głównego
 */
class View_Helper_Layout_Menu extends Zend_View_Helper_Abstract
{
	/**
	 * Menu główne
	 *
	 * @var	array
	 */
	protected static $aMenu = array(
		'tasks'			=> array('Zadania', '/tasks/list'),
		'tasks-my'		=> array('Moje zadania', '/tasks/my-list'),
		'solutions-my'	=> array('Moje rozwiązania', '/solutions/my-list')
	);

	/**
	 * Adresy i powiązane z nimi menu
	 */
	protected static $aAdresses = array(
		'tasks/*'		=> 'tasks',
		'tasks/add'		=> 'tasks-my',
		'tasks/edit'	=> 'tasks-my',
		'tasks/my-list'	=> 'tasks-my',
		'tasks/my-show'	=> 'tasks-my',
		'solutions/*'	=> 'solutions-my'
	);

	/**
	 * Funkcja helpera
	 *
	 * @return	string
	 */
	public function layout_Menu()
	{
	// ustalenie kontrolera i akcji
		$oRequest = Zend_Controller_Front::getInstance()->getRequest();
		$sController = $oRequest->getControllerName();
		$sAction = $oRequest->getActionName();

	// ustalenie aktywnego menu
		$sActive = '';
		if(isset(self::$aAdresses[$sController . '/' . $sAction]))
		{
			$sActive = self::$aAdresses[$sController . '/' . $sAction];
		}
		elseif(isset(self::$aAdresses[$sController . '/*']))
		{
			$sActive = self::$aAdresses[$sController . '/*'];
		}

	// dostosowanie menu
		$aMenu = self::$aMenu;

		$oAuth = Core_Auth::getInstance();
		if($oAuth->hasIdentity() && $oAuth->getUser()->getRole() != \Model\Users\User::ROLE_MOD)
		{
			unset($aMenu['tasks-my']);
		}

	// utworzenie menu
		$sResult = '<nav class="main-menu"><ul>';

		foreach($aMenu as $sKey => $aParam)
		{
			$sSelected = '';

			if($sKey == $sActive)
			{
				$sSelected = 'selected';
			}

			$sResult .= '<li class="'. $sSelected .'"><a href="'. $aParam[1] .'">'. $aParam[0] .'</a></li> ';
		}



		$sResult .= '</ul></nav>';

		return $sResult;
	}
}