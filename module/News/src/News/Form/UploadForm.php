<?php
namespace News\Form;
use Zend\Form\Form;
class UploadForm extends Form {
	public function __construct($name = NULL) {
		parent::__construct ( 'appForm' );
		$this->setAttribute ( 'method', 'post' );
		$this->setAttribute ( 'enctype', 'multipart/form-data' );
		$this->add (array (
				'name' => 'picture',
				'attributes' => array (
						'type' => 'file',
						'require' => 'require',
						'class' => 'txtInput txtMedium' 
				),
				'options' => array (
						'label' => 'File upload:' 
						) 
		));
		
		$this->add(array(
			'name'=>'submit',
			'attributes'=>array(
						'type'=>'submit',
						'value'=>'Send data'
						)
		));
	}
}