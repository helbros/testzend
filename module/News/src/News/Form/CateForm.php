<?php
namespace News\Form;
use Zend\Form\Form;
class CateForm extends Form {
	public function __construct() {
		parent::__construct('cate');
		$this->add(array(
				'name'=>'parent_id',
				'type'=>'Select',
				'attributes'=>array(						
						'class'=>'form-control',
				),
				'options'=>array(
						//'label'=>'Cấp danh mục :',
						'value_options'=>array(
								'0'=>'Root'
						)
				)
		));
		$this->add(array(
				'name'=>'title',
				'attributes'=>array(
						'type'=>'text',
						'class'=>'form-control',
				),
				'options'=>array(
						//'label'=>'Danh mục'
				)
		));
		$this->add(array(
				'name'=>'alias',
				'attributes'=>array(
						'type'=>'text',
						'class'=>'form-control',
				),
				'options'=>array(
						//'label'=>'Tên alias'
				)
		));
	}
}