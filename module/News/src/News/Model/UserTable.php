<?php 
namespace News\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Crypt\Password\Bcrypt;
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
	
	/**
	 * 
	 * 
	 * @param $param (id or username of user)
	 * @param string $flag (if $flag=false will select by ID, if $flag=false will select by username)  
	 * @return Ambigous <multitype:, ArrayObject, NULL>
	 */
	function getUser($param,$flag=FALSE){
		$rowset=$flag==FALSE? $this->tableGateway->select(array('id'=>$param)):$this->tableGateway->select(array('username'=>$param));
		return $rowset->current();
	}
	function insertUser(User $data){
		$bcrypt=new Bcrypt(array('salt'=>$this->createSalt()));
		$pass=$bcrypt->create($data->password);
		$salt=$bcrypt->getSalt();
		
		$data=array(
				'username'=>$data->username,
				'password'=>$pass,
				'salt'=>$salt,
				'email'=>$data->email,
				'title_user'=>'member',				
		);		
		$this->tableGateway->insert($data);
	}
	function createSalt($len = 16){
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+';
		$l = strlen($chars) - 1;
		$str = '';
		for ($i = 0; $i <=$len; $i++) {
			$str .= $chars[rand(0, $l)];
		}
		return $str;
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