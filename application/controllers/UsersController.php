<?php

use \Model\Users\User;

/**
 * Class IndexController
 */
class UsersController extends Core_Controller_Action
{
	CONST ITEMS_COUNT = 10;

	/**
	 * Fabryka userów
	 *
	 * @var	\Model\Users\UserFactory
	 */
	protected $oUserF;

	/**
	 * Inicjalizacja
	 */
	public function init()
	{
		parent::init();

		$this->oUserF = \Model\Users\UserFactory::getInstance();
	}

// USER-SECTION

	/**
	 * Lista wszystkich userów
	 */
	public function listAction()
	{
		$this->mustBe([User::ROLE_ADMIN]);

		$iPage = $this->_request->getParam('page', 1);

		if($iPage < 1)
		{
			$this->moveTo404();
		}

		$oPaginator = $this->oUserF->getPaginator($iPage, self::ITEMS_COUNT, ['u_surname', 'u_name'], null);

		if($oPaginator->count() > 0 && $iPage > $oPaginator->count())
		{
			$this->moveTo404();
			exit();
		}

		$this->view->assign('oPaginator', $oPaginator);
	}

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
				$oUser->setRole($aValues['role']);
				$oUser->setStatus($aValues['status']);
				$oUser->save();

				$this->addMessage('Użytkownik <strong>'. $oUser->getFullName() . '</strong> został pomyślnie zmieniony');
				$this->_redirect('/users/list');
				exit();
			}

			$this->showFormMessages($oFilter);
		}
		else
		{
			$this->view->assign('aValues', [
				'name'		=> $oUser->getName(),
				'surname'	=> $oUser->getSurname(),
				'email'		=> $oUser->getEmail(),
				'role'		=> $oUser->getRole(),
				'status'	=> $oUser->getStatus()
			]);
		}

		$this->view->assign('oUser', $oUser);
		$this->_helper->viewRenderer('user-form');
	}

	/**
	 * Zmiana statusu usera
	 */
	public function statusAction()
	{
		$this->mustBe([User::ROLE_ADMIN]);

		$oUser = $this->getUser();

		if($oUser->getStatus() == User::STATUS_ACTIVE)
		{
			$oUser->setStatus(User::STATUS_BANNED);
			$this->addMessage('Użytkownik <strong>'. $oUser->getFullName() . '</strong> został zbanowany');
		}
		else
		{
			$oUser->setStatus(User::STATUS_ACTIVE);
			$this->addMessage('Użytkownik <strong>'. $oUser->getFullName() . '</strong> został odblokowany');
		}
		$oUser->save();

		$this->_redirect('/users/list');
		exit();
	}

	/**
	 * Usuwanie zadania
	 */
	public function delAction()
	{
		$this->mustBe([User::ROLE_ADMIN]);

		$oUser = $this->getUser();
		$oUser->delete();

		$this->addMessage('Użytkownik <strong>'. $oUser->getFullName() . '</strong> został usunięty');
		$this->_redirect('/users/list');
		exit();
	}

// POMOCNICZE

	/**
	 * Zwraca zadanie na podstawie otrzymanego ID
	 *
	 * @return	\Model\Users\User
	 */
	protected function getUser()
	{
		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oUser = $this->oUserF->getOne($iId);
		}
		catch(\Sca\DataObject\Exception $oExc)
		{
			$this->moveTo404();
			exit();
		}

		return $oUser;
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
			'role' => array(
				new Core_Validate_InArray(array_keys(\Model\Users\UserFactory::getRoles()))
			),
			'status' => array(
				new Core_Validate_InArray(array_keys(\Model\Users\UserFactory::getStatus()))
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}