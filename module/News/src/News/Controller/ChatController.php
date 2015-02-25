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
		$this->redirect ()->toRoute ( 'news/chat' );
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
		$res_chat = $this->getChatTable ()->fecthAll ();
		return array (
				'chat' => $res_chat 
		);
	}
	function deletechatAction() {
		$id_message = $this->params ( 'id' );
		$this->getChatTable ()->delete ( $id_message );
		$this->redirect ()->toRoute ( 'news/chat/manager' );
	}
	function clearallchatAction() {
		$this->getChatTable ()->clearall ();
	}
	function banchatAction() {
		$id_user = $this->params ( 'id_user' );
		$this->getChatTable ()->banchat ( $id_user );
	}
	function unbanchatAction() {
		$id_user = $this->params ( 'id_user' );
		$this->getChatTable ()->unbanchat ( $id_user );
	}
	function upiconAction() {
	}
}
