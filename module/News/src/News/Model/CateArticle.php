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
		$this->parent_id=(!empty($data['parent_id'])?$data['parent_id']:null);
		$this->level=(!empty($data['level'])?$data['level']:null);
		$this->title=(!empty($data['title'])?$data['title']:null);
		$this->alias=(!empty($data['alias'])?$data['alias']:null);
		$this->path=(!empty($data['path'])?$data['path']:null);
	}
}