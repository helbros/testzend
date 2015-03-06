<?php

namespace News\Form;

use Zend\Form\Form;

class ArticleForm extends Form {
	function __construct() {
		parent::__construct ( 'article' );
		$this->add ( array (
				'name' => 'cate_article_id',
				'type' => 'Select',
				'attributes' => array (
						'class' => 'form-control' 
				),
				'options' => array (
						'label' => 'Danh mục',
						'value_options'=>array(
								'0'=>'Chọn danh mục :'
						)
				) 
		) );
		$this->add ( array (
				'name' => 'title',
				'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'required' => 'required' 
				),
				'options' => array (
						'label' => 'Tên bài viết' 
				) 
		) );
		$this->add ( array (
				'name' => 'alias',
				'attributes' => array (
						'type' => 'text',
						'class' => 'form-control',
						'required' => 'required' 
				),
				'options' => array (
						'label' => 'Tên alias' 
				) 
		) );
		$this->add ( array (
				'name' => 'full_text',
				'type' => 'Textarea',
				'attributes' => array (
						
						'class' => 'form-control',
						
						// 'required'=>'required',
						//'cols' => '100',
						'rows' => '15' 
				),
				'options' => array (
						'label' => 'Nội dung' 
				) 
		) );
	}
}