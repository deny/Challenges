<?php

use \Model\Users\User;

/**
 * KOntroler podsumowania
 */
class DashboardController extends Core_Controller_Action
{
    public function indexAction()
    {
		$this->mustBe([User::ROLE_USER, User::ROLE_MOD, User::ROLE_ADMIN]);


		$this->view->assign('iUsers', \Model\Users\UserFactory::getInstance()->getCount());
		$this->view->assign('iTasks', \Model\Tasks\TaskFactory::getInstance()->getCount());
		$this->view->assign('iSolutions', \Model\Tasks\SolutionFactory::getInstance()->getCount());
		$this->view->assign('iSolutionsSuccess', \Model\Tasks\SolutionFactory::getInstance()->getCount(\Model\Tasks\Solution::STATUS_SUCCESS));


		if(in_array($this->oCurrentUser->getRole(), [User::ROLE_USER, User::ROLE_MOD]))
		{
			$this->view->assign('iMySolutions', \Model\Tasks\SolutionFactory::getInstance()->getUserCount($this->oCurrentUser));
			$this->view->assign('iMySolutionsSuccess', \Model\Tasks\SolutionFactory::getInstance()->getUserCount($this->oCurrentUser, \Model\Tasks\Solution::STATUS_SUCCESS));
			$this->view->assign('aSolutions', \Model\Tasks\SolutionFactory::getInstance()->getLast($this->oCurrentUser, 5));
		}

		if($this->oCurrentUser->getRole() == User::ROLE_MOD)
		{
			$this->view->assign('iMyTasks', \Model\Tasks\TaskFactory::getInstance()->getUserCount($this->oCurrentUser));
		}

		$this->view->assign('aTasks', \Model\Tasks\TaskFactory::getInstance()->getLast(5));
	}
}
