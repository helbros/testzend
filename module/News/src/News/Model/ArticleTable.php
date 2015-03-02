<?php

namespace News\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ArticleTable{
	protected $tableGateway;
	protected $serviceLocator;
	function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}	
	
	function fetchAll() {
		return $this->tableGateway->select ();
	}
	function listArticlebyCate($cate_id){		
		$select=new Select();
		$select->from('article')->where("cate_article_id = $cate_id")->order('created DESC');
		$resultSetPrototype=new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new Article());
		$paginatorAdapter=new DbSelect($select, $this->tableGateway->getAdapter(),$resultSetPrototype);
		$paginator=new Paginator($paginatorAdapter);
		
		return $paginator;
	}
	function getArticle($id) {
		$select=new Select();
		$select->from('article')->join('categories_article', 'article.cate_article_id = categories_article.id',array('cate_title'=>'title'))->where("article.id = $id");
		return $this->tableGateway->selectWith($select)->current();
		
		//$rowset=$this->tableGateway->select (array('id'=>$id));
		//return $rowset->current();		
	}
	function getRelateArticle($cate_article_id){
		$select=new Select();
		$select->from('article')->where("cate_article_id = $cate_article_id")->limit(5);
		return $this->tableGateway->selectWith($select);
	}
	function getLastArticle(){
		$select=new Select();
		$select->from('article')->limit('5')->order('created ASC');
		return $this->tableGateway->selectWith($select);
	}
	function insert(Article $data) {
		$data = array (
				'cate_article_id' => $data->cate_article_id,
				'title' => $data->title,
				'alias' => $data->alias,
				'intro_text' => $this->getIntroText($data->full_text),
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
	
	/**
	 * CÁC HÀM HỖ TRỢ KHÁC
	 *
	 */
	function getIntroText($text)
	{
		$intro_text=explode('@--Readmore--@',$text);
		if ($intro_text[0]) {
			return $intro_text[0];
		}
	}
}