<?php
namespace News\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
class CateArticleTable {	
	protected $tableGateway;
	function __construct(TableGateway $tableGateway) {
		$this->tableGateway=$tableGateway;
	}
	function insert(CateArticle $data){
		$data=array(
				'parent_id'=>$data->parent_id,
				'title'=>$data->title,
				'alias'=>$data->alias,
				'path'=>$this->createPathCate($data->parent_id)
		);
		$this->tableGateway->insert($data);
	}
	function update(CateArticle $set,$where){
		$data=array(
				'parent_id'=>$set->parent_id,				
				'title'=>$set->title,
				'alias'=>$set->alias,	
				'path'=>$this->createPathCate($set->parent_id)
		);
		return $this->tableGateway->update($data,$where);
	}
	function delete($id_cate){
		return $this->tableGateway->delete(array('id'=>$id_cate));
	}
	function createPathCate($cate_id){
		$res=$this->getWhere($cate_id);
		$num_row=$this->fetchAll()->count();
		return $current_path=$res->path.'-'.($num_row+1);
	}
	function fetchAll(){
		return $this->tableGateway->select();
	}
	function get($field){
		$select=new Select();
		$select->from('categories_article')->columns($field);
		return $this->tableGateway->selectWith($select);
	}
	function getWhere($where){		
		$result=$this->tableGateway->select(array('id'=>$where));
		return $result->current();
	}
	function getCateArticleList(){
		$select=new Select();
		$select->from('categories_article')->order('path ASC');
		return $this->tableGateway->selectWith($select);
	}
	function add_prefixCate($cate_id){
		$prefix_level='';
		$path=$this->getWhere($cate_id);
		$level=substr_count($path->path, '-');
		for ($i=1;$i<=$level;$i++){
			$prefix_level.='---------/';
		}
		return $prefix_level;
	}
}