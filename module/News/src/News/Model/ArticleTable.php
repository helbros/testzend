<?php
namespace News\Model;
use Zend\Db\TableGateway\TableGateway;
class ArticleTable  {
	protected $tableGateway;
	function __construct(TableGateway $tableGateway) {
		$this->tableGateway=$tableGateway;
	}
	function fetchAll(){
		return $this->tableGateway->select();
	}
}