<?php
use Zend\Form\Form;
class ArticleForm extends Form {
	function __construct() {
		parent::__construct('article');
		$this->add(array(
			'name'=>'cate_article_id',
			'type'=>'Select'
				
		));
	}
}