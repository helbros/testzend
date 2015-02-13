<?php
namespace News\Controller;
require 'vendor/facebook/autoload.php';

use Zend\Mvc\Controller\AbstractActionController;
use News\Form\RegisterForm;
use News\Form\RegisterFilter;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\View\Model\JsonModel;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Db\RecordExists;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Zend\Session\Container;


class AuthController extends AbstractActionController {	
	function __construct(){
		FacebookSession::setDefaultApplication('684652011592771','42aeb9182578e6d3416dd90818201106');
		$container=new Container('fb');
	}
	function loginfbAction(){
		
		$helper= new FacebookRedirectLoginHelper('http://localhost/workspace/testzend/public/news/auth/getdatafb');
		$link=$helper->getLoginUrl();
		return array('link'=>$link);
	}
	function getdatafbAction(){
		$helper=new FacebookRedirectLoginHelper('http://localhost/workspace/testzend/public/news/auth/getdatafb');
		$session=$helper->getSessionFromRedirect();
		if ($session) {
			$user=(new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());
			echo 'Name = '.$user->getName();
		}
		
	}
	function loginAction() {
		$auth = new AuthenticationService ();
		$messAuth = null;
		$form = new RegisterForm ();
		$form->get ( 'submit' )->setValue ( 'Login' );
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$filter = new RegisterFilter ( $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' ) );
			$form->setInputFilter ( $filter );
			$form->setData ( $request->getPost () );
			$form->setValidationGroup ( 'username', 'password', 'remember' );
			if ($form->isValid ()) {
				$data = $form->getData ();							
				$dbAdapter = $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' );
				$authAdapter = new DbTable ( $dbAdapter, 'userz', 'username', 'password', 'MD5(?)' );
				$authAdapter->setIdentity ( $data ['username'] );
				$authAdapter->setCredential ( $data ['password'] );
				
				$resultAuth = $auth->authenticate ( $authAdapter );
				$auth->getStorage ()->write ( $authAdapter->getResultRowObject (array('id','username','title_user')));				
				if ($resultAuth->isValid ()) {
					echo 'all valid <br>';
									
					$this->redirect ()->toRoute ( 'news/manager' );
				} else {					
					foreach ( $resultAuth->getMessages () as $mess ) {
						$messAuth .= $mess;
					}
				}
			} else
				echo 'wrong';
		}
		/*
		 * fsdfsdfdsf
		 */
		
		return array (
				'form' => $form,
				'messages' => $messAuth 
		);
	}
	function logoutAction() {
		$auth = new AuthenticationService ();
		$auth->clearIdentity ();				
		$this->redirect ()->toRoute ( 'news/manager' );
	}
	function testAction(){
		return array('show'=>'tetete');
	}
	function checkAuthAction(){
		$auth = new AuthenticationService ();
		$validator_band=new RecordExists(array(
				'table'=>'userz_ban',
				'field'=>'username',
				'adapter'=>$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'),
		));
		$result_band=($validator_band->isValid($auth->getIdentity()->username))?	true:false;				
		return new JsonModel(array(
				'checkAuth'=>$auth->getIdentity()? true:false,
				'band'=>$this->getServiceLocator()->get('checkAuthBand'),
				
		));
	}
}