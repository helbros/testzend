<?php
namespace News\Form;
use Zend\Form\Form;
class RegisterForm extends Form {
	public function __construct() {
		parent::__construct('register');
		$this->setAttribute('method', 'post');
		$this->add(array(
				'name'=>'id',
				'attributes'=>array(
						'type' => 'hidden',						
				),				
		));
		$this->add(array(
				'name'=>'username',
				'attributes'=>array(
						'type' => 'text',
						'class'=>'form-control',
						'required'=>'required'						
				),
				'options'=>array(
						'label'=>'Username'
				)
		));
		$this->add(array(
				'name'=>'password',
				'attributes'=>array(
						'type' => 'password',
						'class'=>'form-control',
						'required'=>'required'
				),
				'options'=>array(
						'label'=>'Password'
				)
		));
		$this->add(array(
				'name'=>'confirm_password',
				'attributes'=>array(
						'type' => 'password',
						'class'=>'form-control',
						'required'=>'required'
				),
				'options'=>array(
						'label'=>'Confirn Password'
				)
		));
		$this->add(array(
				'name'=>'email',
				'attributes'=>array(
						'class'=>'form-control',
						'required'=>'required'
				),
				'options'=>array(
						'label'=>'Email'
				)
		));
		$this->add(array(
				'name'=>'csrf',
				'type'=>'Zend\Form\Element\Csrf',
				'options'=>array(
						'csrf_options'=>array(
								'timeout'=>100
						)
				)
		));
		$this->add(array(
				'name'=>'submit',
				'attributes'=>array(
						'class'=>' btn btn-default',
						'type'=>'submit',
						'value'=>'Add'
				)
		));
		
		$capt_image=new \Zend\Captcha\Image(array(
				'font' => './data/font/ARIAL.TTF',
				'width' => 250,
				'height' => 100,
				'dotNoiseLevel' => 40,
				'lineNoiseLevel' => 3
		));
		$capt_image->setImgDir('./data/captcha');
		//getRequest()->getUri();
		//$uri = \Zend\Uri\UriFactory::factory();				
		//$capt_image->setImgUrl($uri->getScheme().'://'.$uri->getHost().$uri->getPath().'data/captcha');
		$capt_image->setImgUrl('data/captcha');
		
		$this->add(array(
				'name'=>'captcha',
				'type'=>'Zend\Form\Element\Captcha',
				'options' => array(
						'label' => 'Please verify you are human',
						'captcha' => $capt_image,
				),
		));
		$this->add(array(
				'name'=>'remember',
				'type'=>'Zend\Form\Element\Checkbox',
				'options'=>array(
						'label'=>'Remember'
				)
		));
	}
}