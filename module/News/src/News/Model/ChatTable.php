<?php
namespace News\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Delete;
class ChatTable {
	protected $tableGateway;
	function __construct(TableGateway $tableGateway) {
		$this->tableGateway=$tableGateway;
	}
	function fetchAll(){
		return $this->tableGateway->select();
	}
	function insert(Chat $data){
		$data=array(
			'username'=>$data->username,
			'message'=>$data->message,	
		);
		return $this->tableGateway->insert($data);
	}
	function delete($id){
		return $this->tableGateway->delete(array('id'=>$id));
	}
	function clearall(){	
		$delete=new Delete();
		$delete->from('chat');
		return $this->tableGateway->deleteWith($delete);
	}
}