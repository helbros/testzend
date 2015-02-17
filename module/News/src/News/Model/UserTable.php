<?php 
namespace News\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
class UserTable  {
	protected $tableGateway;
	function __construct(TableGateway $tableGateway) {
		$this->tableGateway=$tableGateway;
	}
	function getall(){
		return $data=$this->tableGateway->select();
		
	}
	function fetchAll($paginated=FALSE){
		if ($paginated) {
			$select=new Select('userz');
			$resultSetPrototype=new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new User());
			$paginatorAdapter=new DbSelect($select, $this->tableGateway->getAdapter(),$resultSetPrototype);
			$paginator=new Paginator($paginatorAdapter);
			return $paginator;
			
		}
		$resulSet=$this->tableGateway->select();
		return $resulSet;
	}
	function getUser($id){
		$rowset=$this->tableGateway->select(array('id'=>$id));
		return $rowset->current();
	}
	function insertUser(User $data){
		$data=array(
				'username'=>$data->username,
				'password'=>$data->password,
				'email'=>$data->email,
				'title_user'=>'member',
				'salt'=>uniqid(mt_rand(), true)
		);		
		$this->tableGateway->insert($data);
	}
	function saveUser(User $user){
		$data=array(
				'username'=>$user->username,
				'password'=>$user->password,
				'email'=>$user->email
		);
		$id=$user->id;
		$this->tableGateway->update($data,array('id'=>$id));
	}
	function deleteUser($id){
		$this->tableGateway->delete(array('id'=>$id));
	}
}
?>