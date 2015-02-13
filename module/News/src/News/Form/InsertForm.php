<?php

namespace News\Form;

use Zend\Form\Form;

class InsertForm extends Form {
	function __construct($name = NULL) {
		parent::__construct ( 'insertForm' );
		$this->setAttribute('method', 'post');
		$this->add ( array (
				'name' => 'username',
				'attributes' => array (
						'type' => 'text',
						'class' => 'form-control' 
				),
				'options' => array (
						'label' => 'Username' 
				) 
		) );
		$this->add ( array (
				'name' => 'password',
				'attributes' => array (
						'type' => 'text',
						'class' => 'form-control'
				),
				'options' => array (
						'label' => 'Password' 
				) 
		) );
		$this->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'type' => 'submit',
						'value'=>'Send' 
				) 
		) );
	}
}