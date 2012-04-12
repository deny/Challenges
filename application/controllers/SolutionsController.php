<?php

use \Model\Users\User;
use \Model\Tasks\Solution;

/**
 * Class IndexController
 */
class SolutionsController extends Core_Controller_Action
{
	CONST ITEMS_COUNT = 10;

	/**
	 * Fabryka zadań
	 *
	 * @var	\Model\Tasks\SolutionFactory
	 */
	protected $oSolutionF;

	/**
	 * Inicjalizacja
	 */
	public function init()
	{
		parent::init();

		$this->oSolutionF = \Model\Tasks\SolutionFactory::getInstance();
	}

// USER-SECTION

	/**
	 * Pokazuje szczegóły rozwiązania
	 */
	public function showAction()
	{
		$this->mustBe([User::ROLE_USER, User::ROLE_MOD]);
		$this->view->assign('oSolution', $this->getSolution());
	}

	/**
	 * Lista moich rozwiązań
	 */
	public function myListAction()
	{
		$this->mustBe([User::ROLE_USER, User::ROLE_MOD]);

		$iPage = $this->_request->getParam('page', 1);

		if($iPage < 1)
		{
			$this->moveTo404();
		}

		$oWhere = new \Sca\DataObject\Where('s_author = ?', $this->oCurrentUser->getId());

		$oPaginator = $this->oSolutionF->getPaginator($iPage, self::ITEMS_COUNT, ['s_status', 't_name'], $oWhere, ['task']);

		if($oPaginator->count() > 0 && $iPage > $oPaginator->count())
		{
			$this->moveTo404();
			exit();
		}

		$this->view->assign('oPaginator', $oPaginator);
	}

	/**
	 * Dodawanie nowego rozwiazania
	 */
	public function addAction()
	{
		$this->mustBe([User::ROLE_USER, User::ROLE_MOD]);

		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oTask = \Model\Tasks\TaskFactory::getInstance()->getOne($iId);

			$oWhere = new \Sca\DataObject\Where('s_task = ?', $oTask->getId());
			$oWhere->addAnd('s_author = ?', $this->oCurrentUser->getId());

			$aTmp = $this->oSolutionF->getFromWhere($oWhere);

			if(!empty($aTmp))
			{
				$this->_redirect('/solutions/edit/id/'. $aTmp[0]->getId());
				exit();
			}
		}
		catch(\Sca\DataObject\Exception $oExc)
		{
			$this->moveTo404();
			exit();
		}

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter();

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oSolution = $this->oSolutionF->createNew(
					$oTask,
					$this->oCurrentUser,
					$aValues['code'],
					$aValues['language']
				);

				$this->addMessage('Rozwiazanie zostało przekazane do oceny');
				$this->_redirect('/solutions/my-list');
				exit();
			}

			$this->showFormMessages($oFilter);
		}

		$this->view->assign('oTask', $oTask);
		$this->_helper->viewRenderer('solution-form');
	}

	/**
	 * Edycja istniejęcego zadania
	 */
	public function editAction()
	{
		$this->mustBe([User::ROLE_USER, User::ROLE_MOD]);

		$oSolution = $this->getSolution();

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter();

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oSolution->setCode($aValues['code']);
				$oSolution->setLanguage($aValues['language']);
				$oSolution->setStatus(Solution::STATUS_NEW);
				$oSolution->save();

				$this->addMessage('Rozwiazanie zostało przekazane do oceny');
				$this->_redirect('/solutions/my-list');
				exit();
			}

			$this->showFormMessages($oFilter);
		}
		else
		{
			$this->view->assign('aValues', [
				'code'		=> html_entity_decode($oSolution->getCode(), ENT_QUOTES, 'UTF-8'),
				'language'	=> $oSolution->getLanguage()
			]);
		}

		$this->view->assign('oTask', $oSolution->getTask());
		$this->view->assign('oSolution', $oSolution);
		$this->_helper->viewRenderer('solution-form');
	}

// POMOCNICZE

	/**
	 * Zwraca rozwiązanie na podstawie otrzymanego ID
	 *
	 * @return	\Model\Tasks\Solution
	 */
	protected function getSolution()
	{
		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oSolution = $this->oSolutionF->getOne($iId);

			if($this->oCurrentUser->getId() != $oSolution->getAuthorId())
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

		return $oSolution;
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
			'code'	=> array(
			),
			'language' => array(
				new Core_Validate_InArray([
					Solution::LANGUAGE_PHP
				])
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}