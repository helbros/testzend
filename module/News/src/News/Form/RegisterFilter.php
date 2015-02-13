<?php
namespace News\Form;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class RegisterFilter extends InputFilter {
	public function __construct($dbAdapter) {
		$this->add(array(
				'name'=>'username',
				'required'=>true,				
				'validators' => array (
						array (
								'name' => 'Zend\Validator\StringLength',
								'options' => array (
										'encoding' => 'UTF-8',
										'min' => 5,
										'max' => 10
								)
						),
						/* array(
								'name'=>'Zend\Validator\Db\NoRecordExists',
								'options'=>array(
										'table'=>'user',
										'field'=>'username',
										'adapter'=>$dbAdapter,
										'messages'=>array(\Zend\validator\db\NoRecordExists::ERROR_RECORD_FOUND=>'Usernam đã tồn tại')
									
								)
						) */
				)
		));
		$this->add(array(
				'name'=>'password',
				'required'=>true,
				'validators'=>array(
						array(
								'name'=>'Zend\Validator\StringLength',
								'options'=>array(
										'encoding'=>'UTF-8',
										'min'=>5,
										'max'=>10
							)						
						)
				)
		));
		$this->add(array(
				'name'=>'confirm_password',
				'required'=>true,
				'validators'=>array(
						array(
								'name'=>'Zend\Validator\Identical',
								'options'=>array(
										'token'=>'password'
							)						
						)
				)
		));
		$this->add(array(
				'name'=>'email',
				'required'=>true,
				'validators'=>array(
						array(
								'name'=>'Zend\Validator\EmailAddress'								
							)						
						)
		));
		
	}
}