<?php

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use News\Form\CateForm;
use News\Form\CateFilter;
use News\Model\CateArticle;
use Zend\Db\ResultSet\ResultSet;
use News\Form\ArticleForm;
use News\Form\ArticleFilter;
use News\Model\Article;

class ArticleController extends AbstractActionController {
	protected $articleTable;
	protected $cateArticleTable;
	function testAction() {
		$id = 34;
		$article = $this->getArticleTable ()->getArticle ( $id );
		foreach ($article as $val){
			echo $val->title."<br>";
			
			echo $val->cate_article_id;
		}
		
		echo print_r($article);
		
	}
	function getArticleTable() {
		$this->articleTable = $this->getServiceLocator ()->get ( 'News\Model\ArticleTable' );
		return $this->articleTable;
	}
	function getCateArticleTable() {
		$this->cateArticleTable = $this->getServiceLocator ()->get ( 'News\Model\CateArticleTable' );
		return $this->cateArticleTable;
	}	
	function addArticleAction() {
		$username = $this->getServiceLocator ()->get ( 'getAuth' )->username;
		$article_form = new ArticleForm ();
		$request = $this->getRequest ();
		$data = $this->getCateArticleTable ()->getCateArticleList ();
		$cate_option = array ();
		foreach ( $data as $val ) {
			$cate_option [$val->id] = $this->getCateArticleTable ()->add_prefixCate ( $val->id ) . $val->title;
			// echo $val->id."-".$this->getCateArticleTable()->add_prefixCate($val->id).$val->title."-".$val->path."<br>";
		}
		$article_form->get ( 'cate_article_id' )->setValueOptions ( $cate_option );
		
		if ($request->isPost ()) {
			
			$filter = new ArticleFilter ( $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' ) );
			$article_form->setInputFilter ( $filter );
			$article_form->setData ( $request->getPost () );
			if ($article_form->isValid ()) {
				//echo print_r ( $article_form->getData () );
				$article = new Article ();
				$article->exchangeArray ( $article_form->getData () );
				$article->created_by = $username;
				$article->modified = $this->getCurrentTime ();
				$article->modified_by = $username;
				$article->hits = '0';
				$article->publish = '1';
				$article->featured = '0';
				$this->getArticleTable ()->insert ( $article );
			} else
				echo print_r ( $article_form->getMessages () );
		}
		return array (
				'form' => $article_form 
		);
	}
	function editArticleAction() {
		$id_article = $this->params ()->fromRoute ( 'id' );
		$res = $this->getArticleTable ()->getArticle ( $id_article );
		if ($form->isPost ()) {
			$this->getArticleTable ()->insertArticle ( $data );
		}
	}
	function detailArticleAction() {
		$id = $this->params ()->fromRoute ( 'id' );
		$article = $this->getArticleTable ()->getArticle ( $id );
		
		$relate_news = $this->getArticleTable ()->getRelateArticle ($article->cate_article_id);
		
		return array (
				'article' => $article,
				'relate_news' => $relate_news 
		);
	}
	function publishArticleAction() {
		$id_article = $this->params ()->fromRoute ( 'id' );
		$this->getArticleTable ()->publish ( $id_article );
	}
	function unpublishArticleAction() {
		$id_article = $this->params ()->fromRoute ( 'id' );
		$this->getArticleTable ()->unpublish ( $id_article );
	}
	function deleteArticleAction() {
		$id_article = $this->params ()->fromRoute ( 'id' );
		$this->getArticleTable ()->delete ( $id_article );
	}
	
	/**
	 * HIEN THI ARTICLE THEO CATE
	 */
	
	function listArticleAction(){
		$cate_id=$this->params()->fromRoute('id');
		$list_article=$this->getArticleTable()->listArticlebyCate($cate_id);
		$list_article->setItemCountPerPage(5);
		$list_article->setCurrentPageNumber((int) $this->params()->fromRoute('page', 1));
		return array(
			'list_article'=>$list_article	
		);
	}
	
	
	
	
	
	/*
	 *
	 * PHAN XU LY CATE ARTICLE
	 */
	function addCateArticleAction() {
		$cate_form = new CateForm ();
		$request = $this->getRequest ();
		$data = $this->getCateArticleTable ()->getCateArticleList ();
		$cate_option = array ();
		foreach ( $data as $val ) {
			$cate_option [$val->id] = $this->getCateArticleTable ()->add_prefixCate ( $val->id ) . $val->title;
			// echo $val->id."-".$this->getCateArticleTable()->add_prefixCate($val->id).$val->title."-".$val->path."<br>";
		}
		$cate_form->get ( 'parent_id' )->setValueOptions ( $cate_option );
		
		if ($request->isPost ()) {
			$filter = new CateFilter ( $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' ) );
			$cate_form->setInputFilter ( $filter );
			$cate_form->setData ( $request->getPost () );
			// $cate_form->setValidationGroup('cate-name','alias-cate-name');
			
			if ($cate_form->isValid ()) {
				echo print_r ( $cate_form->getData () );
				$cate = new CateArticle ();
				$cate->exchangeArray ( $cate_form->getData () );
				$this->getCateArticleTable ()->insert ( $cate );
			}
		}
		return array (
				'form' => $cate_form 
		);
	}
	
	/**
	 * CÁC HÀM HỖ TRỢ KHÁC
	 */
	function getCurrentTime() {
		date_default_timezone_set ( 'Asia/Ho_Chi_Minh' );
		return (new \DateTime ())->format ( 'Y-m-d H:i:s' );
	}
	function getFirstLink($text){
		if (preg_match('/http:\/\/(.+)(jpg|png)/', $text, $matches)){
			return $matches[0];
		}else return null;
	
	}
}