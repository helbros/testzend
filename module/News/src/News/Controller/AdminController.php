<?php
namespace News\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
class AdminController extends AbstractActionController {
	function indexAction() {
		$this->layout('layout/layout_admin');
		$view=new ViewModel();
		
	}
}