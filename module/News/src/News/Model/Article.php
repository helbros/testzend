<?php
namespace News\Model;
class Article {
	public $id;
	public $cate_article_id;
	public $title;
	public $alias;
	public $intro_text;
	public $full_text;
	public $created;
	public $created_by;
	public $modified;
	public $modified_by;
	public $publish;
	public $hits;
	public $featured;
	public $cate_title;
	function exchangeArray($data) {
		$this->id=!empty($data['id'])? $data['id']:null;
		$this->cate_article_id=!empty($data['cate_article_id'])? $data['cate_article_id']:null;
		$this->title=!empty($data['title'])? $data['title']:null;
		$this->alias=!empty($data['alias'])? $data['alias']:null;
		$this->intro_text=!empty($data['intro_text'])? $data['intro_text']:null;
		$this->full_text=!empty($data['full_text'])? $data['full_text']:null;
		$this->created=!empty($data['created'])? $data['created']:null;
		$this->created_by=!empty($data['created_by'])? $data['created_by']:null;
		$this->modified=!empty($data['modified'])? $data['modified']:null;
		$this->modified_by=!empty($data['modified_by'])? $data['modified_by']:null;
		$this->publish=!empty($data['publish'])? $data['publish']:null;
		$this->hits=!empty($data['hits'])? $data['hits']:null;
		$this->featured=!empty($data['featured'])? $data['featured']:null;
		$this->cate_title=!empty($data['cate_title'])? $data['cate_title']:null;
	}
}