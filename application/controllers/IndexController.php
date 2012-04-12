<?php

/**
 * Kontroler strony głównej
 */
class IndexController extends Core_Controller_Action
{
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init()
	{
		parent::init();
		$this->_helper->layout()->setLayout('login');
	}

// LOGOWANIE

	/**
	 * Logowanie
	 */
	public function indexAction()
	{
		if(isset($this->oCurrentUser)) // jeśli user jest zalogwany
    	{
    		$this->_redirect('/dashboard');
    	}

		if($this->_request->isPost()) // jeśli wysłano formularz
		{
			$oFilter = $this->getLoginFilter();

			if($oFilter->isValid()) // jeśli wpisano sensowne dane
			{
				// próba zalogowania
				$oResult = Core_Auth::getInstance()->authenticate(
					new Core_Auth_Adapter(
						$oFilter->getEscaped('email'),
						$oFilter->getEscaped('passwd')
					)
				);

				if($oResult->isValid()) // udane logowanie
				{
					if($oFilter->getEscaped('remember') == 1)
					{
						Zend_Session::rememberMe();
					}

					$this->_redirect('/dashboard');
					exit();
				}
				else // nieudane logowanie
				{
					$this->addMessage('Niepoprawny login bądź hasło', self::MSG_ERROR, true);
				}
			}
			else // niepoprawne dane w formularzu
			{
				$this->showFormMessages($oFilter);
			}
		}
    }

    /**
     * Wylogowanie aktualonego usera
     */
    public function logoutAction()
    {
    	if(isset($this->oCurrentUser)) // jeśli user jest zalogwany
    	{
	    	Core_Auth::getInstance()->clearIdentity();
	    	Zend_Session::destroy(true);
    	}

    	$this->_redirect('/');
    }

// REJESTRACJA

	/**
	 * Rejestracja usera
	 */
	public function registerAction()
	{
		if($this->_request->isPost()) // jeśli wysłano formularz
		{
			$oFilter = $this->getRegisterFilter();

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oUser = \Model\Users\UserFactory::getInstance()->createNew(
					$aValues['email'],
					$aValues['passwd'],
					$aValues['name'],
					$aValues['surname']
				);

				$this->showFormMessages('KOnto zostało utworzone. Zaloguj się.');
				$this->_redirect('/');
				exit();
			}

			$this->showFormMessages($oFilter);
		}
	}

// FILTRY

    /**
     * Zwraca filtr logowania
     *
     * @return	Zend_Filter_Input
     */
    protected function getLoginFilter()
    {
		$aValues = $this->_request->getPost();

    	// walidatory
		$aValidators = array(
			'email'	=> array(
				new Core_Validate_EmailAddress(),
				'emptyMsg' => 'Musisz podać adres email'
			),
			'passwd' => array(
				'emptyMsg' => 'Musisz podać hasło'
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
    }

	/**
	 * Zwraca filtr rejestracji
	 *
	 * @return	Zend_Filter_Input
	 */
	protected function getRegisterFilter()
	{
		$aValues = $this->_request->getPost();

		$oPasswd = new Zend_Validate_Identical($aValues['passwd']);
		$oPasswd->setMessage('Podane hasła są różne', Zend_Validate_Identical::NOT_SAME);

    	// walidatory
		$aValidators = array(
			'email'	=> array(
				new Core_Validate_EmailAddress(),
				new Core_Validate_EmailUnique(),
				new Core_Validate_StringLength(array('min' => 1, 'max' => 50))
			),
			'name'	=> array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 20)),
			),
			'surname' => array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 30)),
			),
			'passwd' => array(
				new Core_Validate_StringLength(array('min' => 8)),
			),
			'passwd2' => array(
				new Core_Validate_StringLength(array('min' => 8)),
				$oPasswd
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}
