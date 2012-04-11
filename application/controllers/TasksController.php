<?php


/**
 * Class IndexController
 */
class TasksController extends Core_Controller_Action
{
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
    /**
     * indexAction
     */
    public function indexAction()
	{
    }

	public function listAction()
	{
		$iPage = $this->_request->getParam('page', 1);

		if($iPage < 1)
		{
			$this->moveTo404();
		}

		$oPaginator = $this->oTaskF->getPaginator($iPage, 10, [], null, ['author']);

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
				$this->_redirect('/tasks/list');
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
				$this->_redirect('/tasks/list');
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
	 * Usuwania zadania
	 */
	public function delAction()
	{
		$oTask = $this->getTask();
		$oTask->delete();

		$this->addMessage('Zadanie zostało pomyślnie usunięte');
		$this->_redirect('/tasks/list');
		exit();
	}

// POMOCNICZE

	/**
	 * Zwraca zadanie na podstawie otrzymanego ID
	 *
	 * @return	\Model\Tasks\Task
	 */
	protected function getTask()
	{
		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oTask = $this->oTaskF->getOne($iId);

			if($this->oCurrentUser->getId() != $oTask->getAuthorId())
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
	protected function getFilter($oUser = null)
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