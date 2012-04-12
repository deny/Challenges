<?php

/**
 * Helper wyświetlajacy komunikaty z Flash Messengera
 */
class View_Helper_Layout_FlashMessenger extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 *
	 * @return	string
	 */
	public function layout_FlashMessenger()
	{
		$sResult = '';
		$oMsg = new Core_Controller_Action_Helper_FlashMessenger();

		if($oMsg->hasCurrentMessages())
		{
			$sResult = $this->getHtml($oMsg->getCurrentMessages());
			$oMsg->clearCurrentMessages();
		}
		elseif($oMsg->hasMessages())
		{
			$sResult = $this->getHtml($oMsg->getMessages());
		}

		return $sResult;
	}

	/**
	 * Zwraca HTML'a z powiadomieniami
	 *
	 * @param	array	$aMsg	tablica komunikatów
	 * @return	string
	 */
	protected function getHtml(array $aMsg)
	{
		$sResult = '';

		// analizuję poszczególne wiadomości
		foreach($aMsg as $aInfo)
		{
			$sResult .= '<div class="messenger '. $aInfo['type'] .'">'. $aInfo['message'] .'</div>';

		}

		return $sResult;
	}
}