<?php

use \Model\Users\User;
use \Model\Users\UserFactory;

/**
 * Class IndexController
 */
class IndexController extends Core_Controller_Action {

    /**
     * indexAction
     */
    public function indexAction()
	{
		echo $this->oCurrentUser->getEmail();
    }
}