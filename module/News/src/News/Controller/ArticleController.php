<?php
namespace News\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use News\Form\CateForm;
use News\Form\CateFilter;
use News\Model\CateArticle;
use Zend\Db\ResultSet\ResultSet;
class ArticleController extends AbstractActionController {
	protected $articleTable;
	protected $cateArticleTable;
	function indexAction() {
		
	}
	function getArticleTable(){
		$this->articleTable=$this->getServiceLocator()->get('News\Model\ArticleTable');
		return $this->articleTable;
	}
	function getCateArticleTable(){
		$this->cateArticleTable=$this->getServiceLocator()->get('News\Model\CateArticleTable');
		return $this->cateArticleTable;
	}
	function articledetailAction(){
		$id_article=$this->params()->fromRoute('id');
		$res=$this->getArticleTable()->getArticle($id_article);
		return array('article'=>$res);
	}
	function addArticleAction(){		
		/* if($form->isPost()){
			$this->getArticleTable()->insertArticle($data);
		} */
	}
	function editArticleAction(){
		$id_article=$this->params()->fromRoute('id');
		$res=$this->getArticleTable()->getArticle($id_article);
		if($form->isPost()){
			$this->getArticleTable()->insertArticle($data);
		}
	}
	function publishArticleAction(){
		$id_article=$this->params()->fromRoute('id');
		$this->getArticleTable()->publish($id_article);
	}
	function unpublishArticleAction(){
		$id_article=$this->params()->fromRoute('id');
		$this->getArticleTable()->unpublish($id_article);
	}
	function deleteArticleAction(){
		$id_article=$this->params()->fromRoute('id');
		$this->getArticleTable()->delete($id_article);
	}
	function addCateArticleAction(){
	 $cate_form=new CateForm();	 
	 $request=$this->getRequest();
	 $data=$this->getCateArticleTable()->fetchAll();	 
	 $cate_option=array();
	 foreach ($data as $val){
	 	$cate_option[$val->id]=$val->title;
	 	echo $val->id."-".$val->title."<br>";
	 }	 
	 $cate_form->get('parent-id')->setValueOptions($cate_option);
	 
	 if ($request->isPost()) {
	 	$filter=new CateFilter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
	 	$cate_form->setInputFilter($filter);
	 	$cate_form->setData($request->getPost());
	 	//$cate_form->setValidationGroup('cate-name','alias-cate-name');
	 	
	 	if ($cate_form->isValid()) {
	 	    echo print_r($cate_form->getData());
	 		$cate=new CateArticle();
	 		$cate->exchangeArray($cate_form->getData());
	 		$this->getCateArticleTable()->insert($cate);
	 	}
	 }
	 return array(
	 	'form'=>$cate_form	
	 );
	}
}