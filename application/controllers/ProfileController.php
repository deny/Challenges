<?php

use \Model\Users\User;

/**
 * Class IndexController
 */
class ProfileController extends Core_Controller_Action
{
	/**
	 * Edycja istniejęcego zadania
	 */
	public function editAction()
	{
		$this->mustBe([User::ROLE_ADMIN]);

		$oUser = $this->getUser();

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter($oUser);

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oUser->setName($aValues['name']);
				$oUser->setSurname($aValues['surname']);
				$oUser->setEmail($aValues['email']);
				$oUser->setPass($aValues['role']);
				$oUser->setStatus($aValues['status']);
				$oUser->save();

				$this->addMessage('Zmiany zostały zapisane');
				$this->_redirect('/');
				exit();
			}

			$this->showFormMessages($oFilter);
		}
		else
		{
			$this->view->assign('aValues', [
				'name'		=> $oUser->getName(),
				'surname'	=> $oUser->getSurname(),
				'email'		=> $oUser->getEmail()
			]);
		}

		$this->_helper->viewRenderer('profile-form');
	}

// FILTRY

	/**
	 * Zwraca filtr dla usera
	 *
	 * @param	\Model\Users\User	$oUser	obiekt edytowanego usera
	 * @return	Core_Filter_Input
	 */
	protected function getFilter($oUser = null)
	{
		$aValues = $this->_request->getPost();

		$oPasswd = new Zend_Validate_Identical($aValues['passwd']);
		$oPasswd->setMessage('Podane hasła są różne', Zend_Validate_Identical::NOT_SAME);

    	// walidatory
		$aValidators = array(
			'email'	=> array(
				new Core_Validate_EmailAddress(),
				new Core_Validate_EmailUnique($oUser),
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