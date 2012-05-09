<?php

use \Model\Users\User;

/**
 * Class IndexController
 */
class TasksController extends Core_Controller_Action
{
	CONST ITEMS_COUNT = 10;

	/**
	 * Fabryka zadań
	 *
	 * @var	\Model\Tasks\TaskFactory
	 */
	protected $oTaskF;

	/**
	 * Inicjalizacja
	 */
	public function init()
	{
		parent::init();

		$this->oTaskF = \Model\Tasks\TaskFactory::getInstance();
	}

// USER-SECTION

	/**
	 * Lista wszystkich zadań
	 */
	public function listAction()
	{
		$this->mustBe([User::ROLE_USER, User::ROLE_MOD]);

		$iPage = $this->_request->getParam('page', 1);

		if($iPage < 1)
		{
			$this->moveTo404();
		}

		$oPaginator = $this->oTaskF->getPaginator($iPage, self::ITEMS_COUNT, ['t_name'], null, ['author']);

		if($oPaginator->count() > 0 && $iPage > $oPaginator->count())
		{
			$this->moveTo404();
			exit();
		}

		$this->view->assign('oPaginator', $oPaginator);
	}

	/**
	 * Pokazuje szczegóły zadania
	 */
	public function showAction()
	{
		$this->mustBe([User::ROLE_USER, User::ROLE_MOD]);

		$oTask =  $this->getTask(false);

		if(isset($this->oCurrentUser) &&
		   $this->oCurrentUser->getRole() == \Model\Users\User::ROLE_MOD &&
		   $oTask->getAuthorId() == $this->oCurrentUser->getId()
		)
		{
			$this->_redirect('/tasks/my-show/id/'. $oTask->getId());
			exit();
		}

		$this->view->assign('oTask', $oTask);
	}

// MODERATOR-SECTION

	/**
	 * Pokazuje szczegóły zadania
	 */
	public function myShowAction()
	{
		$this->mustBe([User::ROLE_MOD]);
		$this->view->assign('oTask', $this->getTask(true));
	}

	/**
	 * Moderator tasks list
	 */
	public function myListAction()
	{
		$this->mustBe(User::ROLE_MOD);

		$iPage = $this->_request->getParam('page', 1);

		if($iPage < 1)
		{
			$this->moveTo404();
		}

		$oWhere = new \Sca\DataObject\Where('t_author = ?', $this->oCurrentUser->getId());

		$oPaginator = $this->oTaskF->getPaginator($iPage, self::ITEMS_COUNT, ['t_name'], $oWhere);

		if($oPaginator->count() > 0 && $iPage > $oPaginator->count())
		{
			$this->moveTo404();
			exit();
		}

		$this->view->assign('oPaginator', $oPaginator);
	}

	/**
	 * Dodawanie nowego zadania
	 */
	public function addAction()
	{
		$this->mustBe(User::ROLE_MOD);

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter();

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oTask = $this->oTaskF->create(
					$this->oCurrentUser->getId(),
					$aValues['name'],
					$aValues['description'],
					$aValues['input'],
					$aValues['output']
				);

				$this->addMessage('Zadanie zostało pomyślnie dodane do listy zadań');
				$this->_redirect('/tasks/my-list');
				exit();
			}

			$this->showFormMessages($oFilter);
		}

		$this->_helper->viewRenderer('task-form');
	}

	/**
	 * Edycja istniejęcego zadania
	 */
	public function editAction()
	{
		$this->mustBe(User::ROLE_MOD);

		$oTask = $this->getTask();

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter();

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oTask->setName($aValues['name']);
				$oTask->setDescription($aValues['description']);
				$oTask->setInput($aValues['input']);
				$oTask->setOutput($aValues['output']);
				$oTask->save();

				$this->addMessage('Zadanie zostało pomyślnie zmienione');
				$this->_redirect('/tasks/my-list');
				exit();
			}

			$this->showFormMessages($oFilter);
		}
		else
		{
			$this->view->assign('aValues', [
				'name'			=> $oTask->getName(),
				'description'	=> $oTask->getDescription(),
				'input'			=> $oTask->getInput(),
				'output'		=> $oTask->getOutput()
			]);
		}

		$this->view->assign('oTask', $oTask);
		$this->_helper->viewRenderer('task-form');
	}

	/**
	 * Usuwanie zadania
	 */
	public function delAction()
	{
		$this->mustBe(User::ROLE_MOD);

		$oTask = $this->getTask();
		$oTask->delete();

		$this->addMessage('Zadanie zostało pomyślnie usunięte');
		$this->_redirect('/tasks/my-list');
		exit();
	}

// POMOCNICZE

	/**
	 * Zwraca zadanie na podstawie otrzymanego ID
	 *
	 * @return	\Model\Tasks\Task
	 */
	protected function getTask($bCheckAuthor = true)
	{
		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oTask = $this->oTaskF->getOne($iId);

			if($bCheckAuthor && $this->oCurrentUser->getId() != $oTask->getAuthorId())
			{
				$this->moveTo404();
				exit();
			}
		}
		catch(\Sca\DataObject\Exception $oExc)
		{
			$this->moveTo404();
			exit();
		}

		return $oTask;
	}

// FILTRY

	/**
	 * Zwraca filtr dla zadań
	 *
	 * @param	\Model\Tasks\Task	$oTask	obiekt edytowanego zadania
	 * @return	Core_Filter_Input
	 */
	protected function getFilter($oTask = null)
	{
		$aValues = $this->_request->getPost();

    	// walidatory
		$aValidators = array(
			'name'	=> array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 255)),
			),
			'description' => array(
			),
			'input' => array(
			),
			'output' => array(
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}