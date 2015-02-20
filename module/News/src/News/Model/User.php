<?php 
namespace News\Model;
class User {
	public $id;
	public $username;
	public $password;
	public $salt;
	public $email;
	public $title_user;
	function exchangeArray($data) {
		$this->id=(!empty($data['id'])?$data['id']:null);
		$this->username=(!empty($data['username'])?$data['username']:null);
		$this->password=(!empty($data['password'])?$data['password']:null);
		$this->email=(!empty($data['email'])?$data['email']:null);
		$this->salt=(!empty($data['salt'])?$data['salt']:null);
	}
	function getArrayCopy(){
		return get_object_vars($this);
	}
}
?>