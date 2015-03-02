<?php
namespace News\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Db\NoRecordExists;
class ArticleFilter extends InputFilter {
	function __construct($dbAdapter) {
			$this->add(array(
				'name'=>'title',
				'required'=>true,
				'validators'=>array(
						array(
								'name'=>'StringLength',
								'options'=>array(
										'encoding'=>'UTF-8',
										'min'=>5,
								)
						),
						array(
								'name'=>'Zend\Validator\Db\NoRecordExists',
								'options'=>array(
										'table'=>'categories_article',
										'field'=>'title',
										'adapter'=>$dbAdapter,
										'message'=>array(
												NoRecordExists::ERROR_RECORD_FOUND=>'Tên danh mục này đã tồn tại'
										)
								)
						)
				)
		));
	}
}