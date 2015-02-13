<?php

namespace News\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class UserFieldset extends Fieldset implements InputFilterProviderInterface {
	public function __construct() {
		parent::__construct ( 'userfieldset' );
		$this->add ( array (
				'name' => 'username',
				'attributes' => array (
						'type' => 'text',
						//'required' => 'required' 
				),
				'options' => array (
						'label' => 'USER FIELDSET' 
				) 
		) );
		$this->add ( array (
				'name' => 'email',
				'attributes' => array (
						'type' => 'text',
						'required' => 'required' 
				),
				'options' => array (
						'label' => 'EMAIL FIELDSET' 
				) 
		) );
		$this->add ( array (
				'name' => 'age',
				'attributes' => array (
						'type' => 'text',
						'required' => 'required' 
				),
				'options' => array (
						'label' => 'AGE FIELDSET' 
				) 
		) );
		/* $this->add ( array (
				'type' => 'Zend\Form\Element\Collection',
				'name' => 'categories',
				'options' => array (
						'label' => 'Please choose categories for this product',
						'count' => 2,
						'should_create_template' => true,
						'template_placeholder' => '__placeholder__',
						'target_element' => array (
								'type' => 'Application\Form\CategoryFieldset' 
						) 
				) 
		) ); */
	}
	public function getInputFilterSpecification() {
		return array (
				'username' => array (
						'required' => true,
						'validators' => array (
								array (
										/* 'name' => 'StringLength',
										'options' => array (
												'encoding' => 'UTF-8',
												'min' => 3,
												'max' => 100  */
										'name'=>'NotEmpty',
										'options'=>array(
												'messages'=>array(
														\Zend\Validator\NotEmpty::IS_EMPTY=>'Ko dc trong'
												)
										)
										 
								) 
						) 
				),
				'email' => array (
						'required' => true,
						'validators' => array (
								array (
										'name' => 'StringLength',
										'options' => array (
												'encoding' => 'UTF-8',
												'min' => 3,
												'max' => 100,
												//'messages'=>array(EmailAddress::INVALID_FORMAT=>'sai dinh dang') 
										) 
								) 
						) 
				),
				'age' => array (
						'required' => true,
						'validators' => array (
								array (
										'name' => 'StringLength',
										'options' => array (
												'encoding' => 'UTF-8',
												'min' => 3,
												'max' => 100 
										) 
								) 
						) 
				) 
		)
		;
	}
}