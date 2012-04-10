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
		$oUser = new User();
    }

	public function listAction()
	{
		$iPage = $this->_request->getParam('page', 1);

		if($iPage < 1)
		{
			$this->moveTo404();
		}

		$oPaginator = $this->oTaskF->getPaginator($iPage, 10);

		if($oPaginator->count() > 0 && $iPage > $oPaginator->count())
		{
			$this->moveTo404();
			exit();
		}

		$this->view->assign('oPaginator', $oPaginator);
	}

	protected function getFilter()
	{
		
	}
}