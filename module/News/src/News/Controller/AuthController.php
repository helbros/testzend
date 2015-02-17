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
use News\Model\User;
use Zend\View\Model\ViewModel;
use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;


class AuthController extends AbstractActionController {	
	protected $userTable; 
	function __construct(){
		FacebookSession::setDefaultApplication('684652011592771','42aeb9182578e6d3416dd90818201106');
		$container=new Container('fb');
	}
	function getUserTable(){
		return $this->userTable=$this->getServiceLocator()->get('News\Model\UserTable');
	}
	
	function loginfbAction(){			
		
		/* echo $this->getRequest()->getUri()->getHost();
		echo '<br>';
		$helper=$this->getServiceLocator()->get('ViewHelperManager')->get('ServerUrl');
		echo $helper->__invoke(); */
		$helper=new FacebookRedirectLoginHelper('http://homestock.vn/testzend/public/news/auth/getdatafb');
		$session=$helper->getSessionFromRedirect();
		if ($session) {
			$user=(new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());
			//echo 'Name = '.$user->getName();			
		}
		$userx=new User();
		
		$userx->username = '100004863904987';
		$userx->password = '100004863904987';
		$userx->birthday = '07/19/1988';
		$userx->email = 'tiqui2taca@gmail.com';	
		$userx->first_name = 'Thanh Tuan';
		$userx->gender = 'male';
		$userx->last_name ='Nguyen';
		$userx->link = 'http://www.facebook.com/100004863904987';
		$userx->locale = 'vi_VN';
		$userx->name = 'Thanh Tuan Nguyen';
		$userx->timezone = 7;
		$userx->updated_time = '2014-10-02T03:28:53+0000';
		$userx->verified =1;
		
		$validator_user=new RecordExists(array(
				'table'=>'userz',
				'field'=>'username',
				'adapter'=>$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')
		));
		if ($validator_user->isValid($userx->username)) {
			$this->redirect()->toRoute('news/manager/home');			
		}else {			
			$this->getUserTable()->insertUser($userx); 
			$this->redirect()->toRoute('news/manager/home');
		}
		
		
		
	}
	function registerAction(){
		$request=$this->getRequest();
		$form=new RegisterForm();
		$filter=new RegisterFilter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
		if ($request->isPost()) {
			$form->setValidationGroup('username','password','confirm_password','email','captcha');
			$form->setInputFilter($filter);
			$form->setData($request->getPost());
			if ($form->isValid()) {
				echo 'valid';
				echo print_r($form->getData());
				$user=new User();
				$user->exchangeArray($form->getData());
				$this->getUserTable()->insertUser($user);
			}else echo print_r ( $form->getMessages () );
		}
		return array('form'=>$form);
	}
	function testAction(){
		$container=new Container('helbros');
		$container->getManager()->destroy();
		return array('show'=>'tetete');
	}
	function loginAction() {
		$container=new Container('helbros');
		echo $container->test;
		//create link login fb
		$helper= new FacebookRedirectLoginHelper('http://homestock.vn/testzend/public/news/auth/loginfb');
		$link_login_fb=$helper->getLoginUrl();
		
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
				'messages' => $messAuth,
				'link_login_fb'=>$link_login_fb 
		);
	}
	function logoutAction() {
		$auth = new AuthenticationService ();
		$auth->clearIdentity ();				
		$this->redirect ()->toRoute ( 'news/manager' );
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