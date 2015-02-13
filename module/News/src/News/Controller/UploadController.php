<?php
namespace News\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use News\Form\UploadForm;
use Zend\View\Model\ViewModel;

class UploadController extends AbstractActionController {
	function indexAction() {
		$form=new UploadForm();
		$view=new ViewModel(array('form'=>$form));
		$request=$this->getRequest();
		if ($request->isPost()) {
			$files=$request->getFiles()->toArray();
			$fileName=$files['picture']['name'];
			$uploadObj=new \Zend\File\Transfer\Adapter\Http();
			$uploadObj->setDestination(realpath('public/files'));
			if ($uploadObj->receive($fileName)) {
				echo 'upload thanhcong';
			}
		}
		return $view;
	}
}