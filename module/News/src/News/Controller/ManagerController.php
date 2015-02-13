<?php

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use News\Form\RegisterForm;
use News\Form\RegisterFilter;
use News\Model\User;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Captcha\Image;
use Zend\Form\Form;
use Zend\Uri\Uri;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Authentication\AuthenticationService;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\AggregateResolver;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\View\Resolver\TemplatePathStack;
use Zend\Db\TableGateway\TableGateway;
use News\Model\Article;
use Zend\Db\Sql\Select;

class ManagerController extends AbstractActionController {
	protected $userTable;
	protected $auth;
	function testforwardAction(){
		$result=$this->forward()->dispatch('News\Controller\Stock',array('action'=>'index'));
		return array('showy'=>$result,'showx'=>'showx');
	}
	function homeAction() {				
		$this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('my title');					
		$select=new Select('article');
		$adapterPaginator=new DbSelect($select, $this->getDbAdapter());
		$paginator=new Paginator($adapterPaginator);
		$paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
		$paginator->setItemCountPerPage(4);

		$view_chatbox=new ViewModel();
		$view_chatbox->setTemplate('News\Chat\index');
		
		$view_stock=new ViewModel();
		$result=$this->forward()->dispatch('News\Controller\Stock',array('action'=>'index'));		
		//echo print_r($result);		
		
		$view_stock->setTemplate('News\Stock\index');		
		$view_stock->setVariable('stock_most_increase',$result->stock_most_increase);
		$view_stock->setVariable('stock_most_down',$result->stock_most_down);
		$view_stock->setVariable('stock_most_match',$result->stock_most_match);
		
		$view=new ViewModel();
		$view->addChild($view_chatbox,'chatbox')->addChild($view_stock,'view_stock');
		$view->setVariable('paginator', $paginator);				
		return $view;		
	}
	function __construct() {
		$this->auth = new AuthenticationService ();
	}
	function getUserTable() {
		$userTable = $this->getServiceLocator ()->get ( 'News\Model\UserTable' );
		return $userTable;
	}
	function indexAction() {		
		
		if ($this->auth->hasIdentity ()) {
			$user = $this->auth->getIdentity ();
			$paginator = $this->getUserTable ()->fetchAll ( true );
			$paginator->setCurrentPageNumber ( $this->params ()->fromQuery ( 'page', 1 ) );
			$paginator->setItemCountPerPage ( 4 );
		} // else $this->redirect()->toRoute('news/auth/login');
		
		$view_chat = new ViewModel ();
		$view_chat->setTemplate ( 'news/chat/index' );
		
		$view = new ViewModel ( array (
				'data' => $paginator,
				'user' => $user 
		) );
		$view->addChild ( $view_chat, 'view_chat' );
		$layout = $this->layout ();
		$layout->addChild ( $view_chat, 'view_chat_x' );
		
		return $view;
	}
	function getDbAdapter() {
		return $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' );
	}
	function adduserAction() {
		$request = $this->getRequest ();
		$form = new RegisterForm ();
		$filter = new RegisterFilter ( $this->getDbAdapter () );
		if ($request->isPost ()) {
			$form->setInputFilter ( $filter );
			$form->setData ( $request->getPost () );
			
			if ($form->isValid ()) {
				echo 'input ok';
				echo print_r ( $form->getData () );
				$user = new User ();
				$user->exchangeArray ( $form->getData () );
				$this->getUserTable ()->insertUser ( $user );
				echo print_r ( $user );
				// $this->redirect()->toRoute('manager');
			} else
				echo print_r ( $form->getMessages () );
		}
		return array (
				'form' => $form 
		);
	}
	function editAction() {
		$id = $this->params ()->fromRoute ( 'id', 0 );
		$current_user = $this->getUserTable ()->getUser ( $id );
		$form = new RegisterForm ();
		echo print_r ( $current_user );
		$form->bind ( $current_user );
		$form->get ( 'submit' )->setAttribute ( 'value', 'Save' );
		$filter = new RegisterFilter ( $this->getDbAdapter () );
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form->setInputFilter ( $filter );
			$form->setData ( $request->getPost () );
			if ($form->isValid ()) {
				// echo print_r($current_user);
				$this->getUserTable ()->saveUser ( $current_user );
				// $this->redirect()->toRoute('manager');
			}
		}
		
		return array (
				'id' => $id,
				'form' => $form 
		);
	}
	function deleteAction() {
		$id = $this->params ()->fromRoute ( 'id', 0 );
		$this->getUserTable ()->deleteUser ( $id );
		// $this->redirect()->toRoute('manager');
	}
	function testAction() {
		$adapter = $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' );
		$sql = new Sql ( $adapter );
		$select = $sql->select ()->from ( 'user' );
		
		$paginatorAdapter = new DbSelect ( $select, $adapter );
		$paginator = new Paginator ( $paginatorAdapter );
		$paginator->setCurrentPageNumber ( $this->params ()->fromQuery ( 'page', 1 ) );
		$paginator->setItemCountPerPage ( 4 );
		
		// test captcha
		$capt_image = new Image ( array (
				'font' => './data/font/arial.ttf',
				'width' => 250,
				'height' => 100,
				'dotNoiseLevel' => 40,
				'lineNoiseLevel' => 3 
		) );
		$capt_image->setImgDir ( './data/captcha' );
		$uri = \Zend\Uri\UriFactory::factory ( 'http://localhost/workspace/testzend/' );
		$scheme = $uri->getPath (); // "example.com"
		echo $uri->getPath () . '<br>';
		echo $uri->getScheme () . '<br>';
		echo $uri->getHost () . '<br>';
		
		$capt_image->setImgUrl ( $uri->getScheme () . '://' . $uri->getHost () . $uri->getPath () . 'data/captcha' );
		
		$form = new Form ();
		$form->add ( array (
				'type' => 'Zend\Form\Element\Captcha',
				'name' => 'captcha',
				'options' => array (
						'label' => 'Please verify you are human',
						'captcha' => $capt_image 
				) 
		) );
		$form->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'type' => 'submit',
						'value' => 'Test Captcha Now' 
				) 
		) );
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form->setData ( $request->getPost () );
			if ($form->isValid ()) {
				echo 'captcha right';
			} else
				echo 'captcha wrong';
		}
		return array (
				'form' => $form,
				'url' => $scheme,
				'paginator' => $paginator 
		);
	}
	function testaclAction() {
		$this->getServiceLocator ()->get ( 'checkAuth' );
		$acl = new Acl ();
		$acl->addRole ( new GenericRole ( 'admin' ) );
		$acl->addRole ( new GenericRole ( 'member' ) );
		$acl->addRole ( new GenericRole ( 'guest' ) );
		$acl->addResource ( new Resource ( 'view', 'edit', 'delete', 'add' ) );
		$acl->allow ( 'admin', $adminResource );
		$acl->allow ( 'member', null, array (
				'view',
				'add' 
		) );
		$acl->allow ( 'guest', null, 'view' );
		
		echo $acl->isAllowed ( 'admin', 'view' ) ? 'yes' : 'no';
		// $module = $this->params()->fromRoute('controller');
		$controller = $this->params ()->fromRoute ( 'controller' );
		$action = $this->params ()->fromRoute ( 'action' );
		// echo $module.'<br>';
		echo $controller . '<br>';
		echo $action . '<br>';
		echo $this->getEvent ()->getRouteMatch ()->getParam ( 'controller', 'index' ) . '<br>';
		echo $this->getEvent ()->getRouteMatch ()->getParam ( 'action', 'index' );
	}
	function setmodAction() {
		$sql = new Sql ( $this->getDbAdapter () );
		$select = $sql->update ();
		$select->table ( 'user' );
		$select->set ( array (
				'title_user' => $this->params ()->fromRoute ( 'param' ) 
		) );
		$select->where ( array (
				'id' => $this->auth->getStorage ()->read ()->id 
		) );
		$statement = $sql->prepareStatementForSqlObject ( $select );
		// $res=$statement->execute();
		echo $sql->getSqlStringForSqlObject ( $select );
	}
	function somebodyAction(){
		echo print_r($this->getUserTable()->getUser(2));
	}
}