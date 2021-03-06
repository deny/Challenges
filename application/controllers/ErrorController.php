<?php

class ErrorController extends Zend_Controller_Action
{
	public function init()
	{
		parent::init();

		if(!Core_Auth::getInstance()->hasIdentity())
		{
			$this->_helper->layout()->setLayout('login');
		}
	}

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
				$this->view->code = 404;
                break;
            default:
            	if($errors->exception->getCode() == 403)
            	{
	                $this->getResponse()->setHttpResponseCode(403);
	                $this->view->message = 'Access deny';
					$this->view->code = 403;
            	}
            	else
            	{
	                // application error
	                $this->getResponse()->setHttpResponseCode(500);
	                $this->view->message = 'Application error';
					$this->view->code = 500;
            	}
                break;
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request   = $errors->request;

    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}
