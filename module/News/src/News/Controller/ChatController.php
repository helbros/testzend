<?php

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Sql\Sql;
use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\View\Model\JsonModel;
use News\Model\UserTable;
use Zend\Db\TableGateway\TableGateway;
use News\Model\ArticleTable;
use Zend\Crypt\PublicKey\Rsa\PublicKey;
use News\Model\Chat;
use Zend\Db\Sql\Insert;

class ChatController extends AbstractActionController {
	protected $auth;
	protected $chatTable;
	function __construct() {
		$auth = new AuthenticationService ();
		$this->auth = $auth->getIdentity ();
	}
	public function indexAction() {
		// return array('a'=>'a');
	}
	public function getChatTable() {
		return $this->chatTable = $this->getServiceLocator ()->get ( 'News\Model\ChatTable' );
	}
	function testaddchatAction() {
		$chat = new Chat ();
		$chat->username = 'tuan';
		$chat->message = 'thank u very much';
		$this->getChatTable ()->insert ( $chat );
	}
	public function postchatAction() {
		$request = $this->getRequest ();
		$message = $this->getRequest ()->getPost ( 'message' );
		if ($request->isPost ()) {
			$chat = new Chat ();
			$chat->username = $this->auth->username;
			$chat->message = htmlspecialchars ( $message );
			$this->getChatTable ()->insert ( $chat );
			/*
			 * $sql=new Sql($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
			 * $insert=$sql->insert('chat');
			 * $insert->values(array(
			 * 'username'=>$this->auth->username,
			 * //'date'=>'xxx',
			 * 'message'=>htmlspecialchars($message),
			 * ));
			 * $sql->prepareStatementForSqlObject($insert)->execute();
			 */
		} else
			echo 'fail';
		//$this->redirect ()->toRoute ( 'news/chat' );
	}
	public function getchatAction() {
		$sql = new Sql ( $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' ) );
		$select = $sql->select ( 'chat' )->order ( 'id DESC' )->limit ( 10 );
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$chat_data = $statement->execute ();
		$result_chat_data = new ResultSet ();
		$result_chat_data->initialize ( $chat_data );
		// echo print_r($result_chat_data->toArray());
		// echo json_encode($result_chat_data);
		
		$json = new JsonModel ( array (
				'chat_data' => array_reverse ( $result_chat_data->toArray () ) 
		) );
		return $json;
	}
	function managerAction() {
		$res_chat = $this->getChatTable ()->fetchAll ();
		return array (
				'chat' => $res_chat 
		);
	}
	function deletechatAction() {
		$id_message = $this->params ( 'id' );
		$this->getChatTable ()->delete ( $id_message );
		$this->redirect ()->toRoute ( 'news/chat/manager' );
	}
	function clearallAction() {
		$this->getChatTable ()->clearall ();
		$this->redirect()->toRoute('news/chat/manager');
	}
	function banchatAction() {		
		$request=$this->getRequest();
		if ($request->isPost()) {
			$username=$request->getPost('username');			
			$sql=new Sql($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
			$insert=$sql->insert('userz_ban')->values(array('username'=>$username));
			$statement=$sql->prepareStatementForSqlObject($insert);
			$statement->execute();					
			//echo $sql->getSqlStringForSqlObject($insert);			
		}
				
		//return array('user'=>$user);		
	}
	function unbanchatAction() {
		$request=$this->getRequest();
		if ($request->isPost()) {
			$username=$request->getPost('username');			
			$sql=new Sql($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
			$delete=$sql->delete()->from('userz_ban')->where(array('username'=>$username));
			$statement=$sql->prepareStatementForSqlObject($delete);
			$statement->execute();					
			$this->redirect()->toRoute('news/chat/banchat');			
		}
	}
	function upiconAction() {
	}
}
