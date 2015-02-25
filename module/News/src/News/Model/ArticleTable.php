<?php

namespace News\Model;

use Zend\Db\TableGateway\TableGateway;

class ArticleTable {
	protected $tableGateway;
	function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	function fetchAll() {
		return $this->tableGateway->select ();
	}
	function insert(Article $data) {
		$data = array (
				'cate_article_id' => $data->cate_article_id,
				'title' => $data->title,
				'alias' => $data->alias,
				'intro_text' => $data->intro_text,
				'full_text' => $data->full_text,
				'created' => $data->created,
				'created_by' => $data->created_by,
				'modified' => $data->modified,
				'modified_by' => $data->modified_by,
				'publish' => $data->publish,
				'hits' => $data->hits,
				'featured' => $data->featured 
		);
		return $this->tableGateway->insert ( $data );
	}
	function delete($id) {
		return $this->tableGateway->delete ( array (
				'id' => $id 
		) );
	}
}