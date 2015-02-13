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

class ChatController extends AbstractActionController {
	protected $auth;
	function __construct(){
		$auth=new AuthenticationService();
		$this->auth=$auth->getIdentity();
	}
	public function indexAction() {				
	}
	public function postchatAction(){
		$request=$this->getRequest();
		$message=$this->getRequest()->getPost('message');				
		if ($request->isPost()) {			
			$sql=new Sql($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
			$insert=$sql->insert('chat');
			$insert->values(array(
					'username'=>$this->auth->username,
					//'date'=>'xxx',
					'message'=>htmlspecialchars($message),
			));
			$sql->prepareStatementForSqlObject($insert)->execute();			
		}else echo 'fail';
		$this->redirect()->toRoute('news/chat');
	}
	public function getchatAction(){
		$sql=new Sql($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
		$select=$sql->select('chat')->order('id DESC')->limit(10);
		$statement=$sql->prepareStatementForSqlObject($select);				
		$chat_data=$statement->execute();
		$result_chat_data=new ResultSet();
		$result_chat_data->initialize($chat_data);
		//echo print_r($result_chat_data->toArray());
		//echo json_encode($result_chat_data);
		
		
		$json= new JsonModel(array('chat_data'=>array_reverse($result_chat_data->toArray())));
		return $json;		
	}
	function managerAction(){
		
	}
}