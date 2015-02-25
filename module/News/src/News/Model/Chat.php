<?php
namespace News\Model;
class Chat {
	public $username;
	public $date_time;
	public $message;
	function exchangeArray($data) {
		$this->username=(!empty($data['username']))? $data['username']:null;
		$this->date_time=(!empty($data['date_time']))? $data['date_time']:null;
		$this->message=(!empty($data['message']))? $data['message']:null;
	}
}