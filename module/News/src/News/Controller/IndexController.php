<?php

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use News\Form\InsertForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Validator\EmailAddress;
use News\Model\User;
use Zend\Db\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\View\Renderer\PhpRenderer;
use News\Form\UserFieldset;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\InputFilter\Input;
use Zend\Validator\StringLength;
use News\Form\MyValid;
use Zend\Captcha\Figlet;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Dumb;

class IndexController extends AbstractActionController {
	protected $userTable;
		
	function indexAction(){
		$view=new ViewModel();
		$view_chat=new ViewModel();
		$view_chat->setTemplate('news/chat/index');
		$view_manager=new ViewModel();
		$view_manager->setTemplate('news/manager/index');
		$view->addChild($view_chat,'view_chat')->addChild($view_manager,'view_manager');
		return $view;
		
	}
	function index2Action() {
		$user = $this->getServiceLocator ()->get ( 'News\Model\UserTable' );
		$view=$user->showall();
		//add form
		$captcha=new Captcha('captcha');
		$captcha->setCaptcha(new Dumb());
		$captcha->setLabel('Verify human :');
					
		$form=new Form();
		$form->setAttribute('method', 'post');	
		$form->add($captcha);	
		$form->add(new UserFieldset());
						
		$form->add(array(
				'name'=>'country',
				'attributes'=>array(
						'type'=>'text'
				),
				'options'=>array(
						'label'=>'Country'
				)
		));
		$form->add(array(
				'name'=>'countryx',
				'attributes'=>array(
						'type'=>'text'
				),
				'options'=>array(
						'label'=>'Country X'
				)
		));
		$form->add(array(
				'name'=>'emailx',
				'attributes'=>array(
						'type'=>'text'
				),
				'options'=>array(
						'label'=>'Email X'
				)
		));
		$form->add(array(
				'name'=>'csrf',
				'type'=>'Zend\Form\Element\Csrf',
				'options' => array(
						'csrf_options' => array(
								'timeout' => 10
						)
				)
		));
		$form->add(array(
				'name'=>'add',
				'attributes'=>array(
						'type'=>'submit',
						'value'=>'Add'
				)
		));
		//kiem tra filter
		$country_filter=new StringLength(5);
		$country_filter->setMessage('Chuỗi tối đa 5 ký tự',StringLength::TOO_SHORT);
		$email_filter=new EmailAddress();
		$email_filter->setMessage('Không đúng định dạng mail',EmailAddress::INVALID_FORMAT);
		$email_filter->setTranslator();
		
		$countryx=new Input('countryx');
		$countryx->getValidatorChain()->attach($country_filter);
		
		$emailx=new Input('emailx');
		$emailx->getValidatorChain()->attach($email_filter);
		
		$filter=new InputFilter();
		$filter->add($countryx);
		$filter->add($emailx);
		
	
		$filter->add ( array (
				'name' => 'country',
				'required' => true,
				'filters' => array (
						array (
								'name' => 'StripTags'
						),
						array (
								'name' => 'StringTrim'
						)
				),
				'validators' => array (
						array (
								'name' => 'News\Form\MyValid',
								
								
						)
				)
		) );
		$filter->add (array(
				'name'=>'csrf',
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
		));
		
		$request=$this->getRequest();
		if ($request->isPost()){
			$form->setInputFilter($filter);	
					
			$form->setData($request->getPost());
			$form->setValidationGroup('country','emailx');
			echo print_r($request->getPost());
			if ($form->isValid()) {
				echo "input ok";
			}else {
				echo print_r ( $form->getMessages () ); 
			}
		}
		return array(
				'user'=>$view,
				'form'=>$form
				
		);	
	}
	public function adduserAction() {
		$form = new InsertForm ();
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$user = new User ();
			$form->setInputFilter ( $user->getInputFilter () );
			$form->setData ( $request->getPost () );
			if ($form->isValid ()) {
				$user->exchangeArray($form->getData());
				echo 'insert done<br>';
				echo "$user->username : $user->pass";
				$this->getUserTable()->saveuser($user);
	
			} else
				echo print_r ( $form->getMessages () );
		}
		return array (
				'form' => $form
		);
	}
	public function viewAction(){
		$view = new ViewModel ();
		

		
		$menu=new ViewModel();
		$menu->setTemplate('news/index/menu');
		
		$sidebar=new ViewModel();
		$sidebar->setTemplate('news/index/sidebar');
		
		$view->addChild($menu,'menu');
		$view->addChild($sidebar,'sidebar');
		return $view;
	}
	public function getUserTable() {
		if (! $this->userTable) {
			$sm = $this->getServiceLocator ();
			$this->userTable = $sm->get ( 'News\Model\UserTable' );
		}
		//return $this->redirect()->toRoute('album');
		return $this->userTable;
	}

	public function dbAction() {
		//data		
 		$config=$this->getServiceLocator()->get('Config');
		$adapter=new \Zend\Db\Adapter\Adapter($config['db']);
		$query=$adapter->createStatement('select * from user');
		$result=$query->execute();
		
		//form
		$form = new InsertForm ();
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$user = new User ();
			$form->setInputFilter ( $user->getInputFilter () );
			$form->setData ( $request->getPost () );
			if ($form->isValid ()) {
				echo 'insert done <br>';
				echo print_r($form->getData());
				$input=$form->getData();

				//$query=$adapter->createStatement("insert into user(username,pass) values('".$input['username']."','".$input['password']."')");
				$query=$adapter->createStatement("insert into user(username,pass) values('{$input['username']}','{$input['password']}')");
				$query->execute();
			} else
				echo print_r ( $form->getMessages () );
		}
		return array (
				'form' => $form,
				'result' => $result		
		);
	}
	public function testAction() {
		$radio = new Element\Radio ( 'life' );
		$radio->setValueOptions ( array (
				'0' => 'OK GO',
				'1' => 'NO STOP NOW' 
		) );
		$select = new Element\Select ( 'Chose your way' );
		$select->setLabel ( 'Chose your way' );
		$select->setValueOptions ( array (
				'0' => 'Silent',
				'1' => 'Emotion',
				'2' => 'No mercy',
				'3' => 'Crazy' 
		) );
		$form = new Form ();
		$form->add ( $radio );
		$form->add ( $select );
		return array (
				'form' => $select 
		);
	}
	public function validAction() {
		$validator = new EmailAddress ();
		
		if ($validator->isValid ( 'dsfsdfdsyahoo.com' )) {
			echo 'true email';
		} else {
			// email is invalid; print the reasons
			foreach ( $validator->getMessages () as $messageId => $message ) {
				echo "$messageId: $message\n";
			}
		}
	}
	public function renderAction(){
		$data = array(
				array(
						'author' => 'Hernando de Soto',
						'title' => 'The Mystery of Capitalism'
				),
				array(
						'author' => 'Henry Hazlitt',
						'title' => 'Economics in One Lesson'
				),
				array(
						'author' => 'Milton Friedman',
						'title' => 'Free to Choose'
				)
		);
		$render=new PhpRenderer();
		$view=new ViewModel(array('book'=>$data));
		
		echo $render->render($data);
		
	}
	public function captchaAction(){
		$captcha=new Figlet(array(
				'name'=>'foo',
				'wordLen'=>6,
				'timeout'=>300,
		));
		$id=$captcha->generate();
		echo $captcha->getFiglet()->render($captcha->getWord());
	}
}
