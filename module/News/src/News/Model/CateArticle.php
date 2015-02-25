<?php
namespace News\Model;
class CateArticle {
	public $id;
	public $parent_id;
	public $level;
	public $title;
	public $alias;
	public $path;
	function exchangeArray($data) {
		$this->id=(!empty($data['id'])?$data['id']:null);
		$this->parent_id=(!empty($data['parent-id'])?$data['parent-id']:null);
		$this->level=(!empty($data['level'])?$data['level']:null);
		$this->title=(!empty($data['cate-name'])?$data['cate-name']:null);
		$this->alias=(!empty($data['alias-cate-name'])?$data['alias-cate-name']:null);
		$this->path=(!empty($data['path'])?$data['path']:null);
	}
}