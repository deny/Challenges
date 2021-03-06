<?php

/**
 * Rozbudowany kontroler
 */
class Core_Controller_Action extends \Zend_Controller_Action
{
	/**
	 * Stałe dla flash messenegera
	 *
	 * @var	string
	 */
	const MSG_OK 	= 'msg-ok';
	const MSG_ERROR = 'msg-error';

	/**
	 * Zalogowany usera
	 *
	 * @var	\Model\Users\User
	 */
	protected $oCurrentUser;

	/**
	 * Inicjalizacja
	 */
	public function init()
	{
		parent::init();

		if(Core_Auth::getInstance()->hasIdentity())
		{
			$this->oCurrentUser = Core_Auth::getInstance()->getUser();
			$this->view->assign('oCurrentUser', $this->oCurrentUser);
		}
	}

	/**
	 * Przekazuje do widoku niezbędne dane z formularzy
	 *
	 * @param 	Zend_Filter_Input	$oFilter	obiekt filtra
	 * @return	void
	 */
	protected function showFormMessages(Zend_Filter_Input $oFilter = null)
	{
		$this->view->assign('aValues', $this->_request->getPost());
		$this->view->assign('aErrors', $oFilter->getMessages());
	}

	/**
	 * Dodaje komunikat do Flash Messengera
	 *
	 * @param	string	$sMessage	treść wiadomości
	 * @param	strng	$sType		typ wiadomości (stałe Core_Controller_Action::MSG_*)
	 * @param	bool	$bNow		czy wiadomośc powinna pojawiż się od razu
	 * @return	void
	 */
	protected function addMessage($sMessage, $sType = self::MSG_OK, $bNow = false)
	{
		if($bNow)
		{
			$this->_helper->flashMessenger->addCurrentMsg($sMessage, $sType);
		}
		else
		{
			$this->_helper->flashMessenger->addMsg($sMessage, $sType);
		}
	}

	/**
	 * Sprawdza czy user jest zalogowany i czy ma odpowiednią rolę
	 *
	 * @param	array|string	$mType
	 * @return	void
	 */
	protected function mustBe($mType)
	{
		if($this->oCurrentUser === null)
		{
			$this->addMessage('Aby mieć dostęp do portalu musisz się zalogować');
			$this->_redirect('/');
			exit();
		}

		$mType = is_array($mType) ? $mType : [$mType];

		if(!in_array($this->oCurrentUser->getRole(), $mType))
		{
			throw new Zend_Controller_Action_Exception('Page not found', 403);
		}
	}

	/**
	 * Przenosi usera na 404
	 *
	 * @return	void
	 */
	protected function moveTo404()
	{
		throw new Zend_Controller_Action_Exception('Page not found', 404);
	}
}