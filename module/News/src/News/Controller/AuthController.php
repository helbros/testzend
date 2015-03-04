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
use Zend\Crypt\Password\Bcrypt;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Zend\Uri\Uri;
use Zend\Mvc\Router\Http\RouteMatch;
use Facebook\Entities\AccessToken;
use Zend\Authentication\Validator\Authentication;

class AuthController extends AbstractActionController {
	protected $userTable;
	function __construct() {
		FacebookSession::setDefaultApplication ( '684652011592771', '42aeb9182578e6d3416dd90818201106' );
		$container = new Container ( 'fb' );
	}
	function getUserTable() {
		return $this->userTable = $this->getServiceLocator ()->get ( 'News\Model\UserTable' );
	}
	function loginfbAction() {					
		$helper = new FacebookRedirectLoginHelper ( 'http://homestock.vn/testzend/public/news/auth/loginfb' );
		$session = $helper->getSessionFromRedirect ();
		if ($session) {
			$user = (new FacebookRequest ( $session, 'GET', '/me' ))->execute ()->getGraphObject ( GraphUser::className () );
			// echo 'Name = '.$user->getName();
		}
		$userx = new User ();
		$userx->username = $user->getProperty ( 'name' );
		$userx->email = $user->getProperty ( 'email' );
		
		// $authAdapter=new DbTable($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'),'userz','username','password');
		
		$register = $this->forward ()->dispatch ( 'News\Controller\Auth', array (
				'action' => 'register' 
		) );
		$form = $register->form;
		$form->bind ( $userx );		
		return array (
				'form' => $form,				
		);
	}
	function registerAction() {	
		$helper = new FacebookRedirectLoginHelper ( 'http://homestock.vn/testzend/public/news/auth/loginfb' );
		$link_login_fb = $helper->getLoginUrl ();
		
		$request = $this->getRequest ();
		$form = new RegisterForm ();
		$form->get('submit')->setValue('Đăng ký');
		$filter = new RegisterFilter ( $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' ) );
		if ($request->isPost ()) {		
			$form->setValidationGroup ( 'username', 'password', 'confirm_password', 'email', 'captcha' );							
			$form->setInputFilter ( $filter );
			$form->setData ( $request->getPost () );
			if ($form->isValid ()) {
				echo 'valid';
				echo print_r ( $form->getData () );
				$user = new User ();
				$user->exchangeArray ( $form->getData () );
				$this->getUserTable ()->insertUser ( $user );
			} 				
		}
		return array (
				'form' => $form,				
				'link_login_fb'=>$link_login_fb 
		);
	}
	function testAction() {
		//$user = $this->getUserTable ()->getUser ( 'guest', true );
		//echo print_r ( $user );
		$bcrypt = new Bcrypt (array('salt'=>'fScVO^!dn_rPVXNG*'));		
		//$bcrypt->setSalt('$#3i3xPi$flS0yyE=');
		$pass = $bcrypt->create ( '123456' );
		
		echo "pass : $pass<br>";
		echo "salt : " . $bcrypt->getSalt () . '<br>';
		if ($bcrypt->verify ( '123', $hash )) {
			echo 'true';
		} else
			echo 'fail';
	}
	function loginAction() {
		$container = new Container ( 'helbros' );
		echo $container->test;
		
		// create link login fb
		$helper = new FacebookRedirectLoginHelper ( 'http://homestock.vn/testzend/public/news/auth/loginfb' );
		$link_login_fb = $helper->getLoginUrl ();
		
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
				$user = $this->getUserTable ()->getUser ( $data ['username'], true );
				$final_password = (new Bcrypt ( array (
						'salt' => $user->salt 						
				) ))->create ( $data ['password'] );				
				
				$authAdapter = new DbTable ( $dbAdapter, 'userz', 'username', 'password' );
				$authAdapter->setIdentity ( $data ['username'] );
				$authAdapter->setCredential ( $final_password );
				
				$resultAuth = $auth->authenticate ( $authAdapter );
				$auth->getStorage ()->write ( $authAdapter->getResultRowObject ( array (
						'id',
						'username',
						'title_user' 
				) ) );
				if ($resultAuth->isValid ()) {
					echo 'all valid <br>';
					//$this->redirect ()->toRoute ( 'news/manager' );
				} else {
					foreach ( $resultAuth->getMessages () as $mess ) {
						$messAuth .= $mess;
					}
				}
			}				
		}
		/*
		 * fsdfsdfdsf
		 */
		
		return array (
				'form' => $form,
				'messages' => $messAuth,			
				'link_login_fb' => $link_login_fb 
		);
	}
	function logoutAction() {
		$auth = new AuthenticationService ();
		$auth->clearIdentity ();
		$this->redirect ()->toRoute ( 'news/manager' );
	}
	function checkAuthAction() {
		$auth = new AuthenticationService ();
		$validator_band = new RecordExists ( array (
				'table' => 'userz_ban',
				'field' => 'username',
				'adapter' => $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' ) 
		) );
		$result_band = ($validator_band->isValid ( $auth->getIdentity ()->username )) ? true : false;
		return new JsonModel ( array (
				'checkAuth' => $auth->getIdentity () ? true : false,
				'band' => $this->getServiceLocator ()->get ( 'checkAuthBand' ) 
		)
		 );
	}
	function loginajaxAction(){
		$auth=new AuthenticationService();
		$request=$this->getRequest();
		$username=$request->getPost('username');
		$password=$request->getPost('password');
		$user = $this->getUserTable ()->getUser ($username, true );
		$final_password = (new Bcrypt ( array (
				'salt' => $user->salt
		) ))->create ( $password );
		
		$authAdapter=new DbTable($this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' ) ,'userz','username','password');
		$authAdapter->setIdentity($username);
		$authAdapter->setCredential($final_password);
		$result_auth=$auth->authenticate($authAdapter);
		if ($result_auth->isValid()) {
			$logined_frontend_status=true;
		}else $logined_frontend_status=false;
		return new JsonModel(array('logined_frontend_status'=>$logined_frontend_status)); 
	}
}