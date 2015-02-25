<?php

namespace News\Form;

use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\Validator\Db\NoRecordExists;

class RegisterFilter extends InputFilter {
	public function __construct($dbAdapter) {
		$this->add ( array (
				'name' => 'username',
				'required' => true,
				'validators' => array (
						array (
								'name' => 'Zend\Validator\StringLength',
								'options' => array (
										'encoding' => 'UTF-8',
										'min' => 5,
										'max' => 10 
								) 
						),
						/* array (
								'name' => 'Zend\Validator\Db\NoRecordExists',
								'options' => array (
										'table' => 'userz',
										'field' => 'username',
										'adapter' => $dbAdapter,
										'messages' => array (
												NoRecordExists::ERROR_RECORD_FOUND => 'Usernam đã tồn tại' 
										) 
								) 
						) */
						 
				) 
		) );
		$this->add ( array (
				'name' => 'password',
				'required' => true,
				'validators' => array (
						array (
								'name' => 'Zend\Validator\StringLength',
								'options' => array (
										'encoding' => 'UTF-8',
										'min' => 5,
										'max' => 10 
								) 
						) 
				) 
		) );
		$this->add ( array (
				'name' => 'confirm_password',
				'required' => true,
				'validators' => array (
						array (
								'name' => 'Zend\Validator\Identical',
								'options' => array (
										'token' => 'password' 
								) 
						) 
				) 
		) );
		$this->add ( array (
				'name' => 'email',
				'required' => true,
				'validators' => array (
						array (
								'name' => 'Zend\Validator\EmailAddress' 
						),
						array (
								'name' => 'Zend\Validator\Db\NoRecordExists',
								'options' => array (
										'table' => 'userz',
										'field' => 'email',
										'adapter' => $dbAdapter,
										'message' => array (
												NoRecordExists::ERROR_RECORD_FOUND => 'E-mail đã tồn tại' 
										) 
								) 
						) 
				) 
			));
	}
}