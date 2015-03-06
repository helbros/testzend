<?php

namespace News\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ArticleTable{
	protected $tableGateway;
	protected $serviceLocator;
	function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}	
	
	function fetchAll($paginated=false,$cate) {
		if ($paginated) {
			$select=new Select('article');
			$select->where(array('cate_article_id'=>$cate));
			$resultSetPrototype=new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Article());
			$paginatorAdapter=new DbSelect($select, $this->tableGateway->getAdapter(),$resultSetPrototype);
			$paginator=new Paginator($paginatorAdapter);
			return $paginator;
		}
		return $this->tableGateway->select ();
	}
	function listArticlebyCate($cate_id){		
		$select=new Select();
		$select->from('article')->where("cate_article_id = $cate_id")->order('created DESC');
		$resultSetPrototype=new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new Article());
		$paginatorAdapter=new DbSelect($select, $this->tableGateway->getAdapter(),$resultSetPrototype);
		$paginator=new Paginator($paginatorAdapter);
		
		return $paginator;
	}
	function getArticle($id) {
		$select=new Select();
		$select->from('article')->join('categories_article', 'article.cate_article_id = categories_article.id',array('cate_title'=>'title'))->where("article.id = $id");
		return $this->tableGateway->selectWith($select)->current();
		
		//$rowset=$this->tableGateway->select (array('id'=>$id));
		//return $rowset->current();		
	}
	function getRelateArticle($cate_article_id){
		$select=new Select();
		$select->from('article')->where("cate_article_id = $cate_article_id")->limit(5);
		return $this->tableGateway->selectWith($select);
	}
	function getLastArticle(){
		$select=new Select();
		$select->from('article')->limit('5')->order('created ASC');
		return $this->tableGateway->selectWith($select);
	}
	function insert(Article $data) {
		$data = array (
				'cate_article_id' => $data->cate_article_id,
				'title' => $data->title,
				'alias' => $this->change_alias($data->title),
				'intro_text' => $this->getIntroText($data->full_text),
				'full_text' => $data->full_text,
				'created' => $data->created,
				'created_by' => $data->created_by,				
				'modified' => $data->modified,
				'modified_by' => $data->modified_by,
				'publish' => $data->publish,
				'hits' => $data->hits,
				'featured' => $data->featured 
		);
		return $this->tableGateway->insert ( $data );
	}
	function delete($id) {
		return $this->tableGateway->delete ( array (
				'id' => $id 
		) );
	}
	function update(Article $set,$where) {
		$data = array (
				'cate_article_id' => $set->cate_article_id,
				'title' => $set->title,
				'alias' => $set->alias,
				'intro_text' => $this->getIntroText($set->full_text),
				'full_text' => $set->full_text,				
				'modified' => $set->modified,
				'modified_by' => $set->modified_by,				
		);
		return $this->tableGateway->update($data,$where);
	}
	function publish($id){
		$select=new Select();
		$select->columns(array('publish'))->from('article')->where(array('id'=>$id));			
		$publish=$this->tableGateway->selectWith($select);		
		foreach ($publish as $val){
			$publish_value=$val->publish;
		}		
		if ($publish_value==0) {
			return $this->tableGateway->update(array('publish'=>1),array('id'=>$id));
		}else return$this->tableGateway->update(array('publish'=>0),array('id'=>$id)); 		
	}
	function featured($id){
		$select=new Select();
		$select->columns(array('featured'))->from('article')->where(array('id'=>$id));			
		$featured=$this->tableGateway->selectWith($select);		
		foreach ($featured as $val){
			$featured_value=$val->featured;
		}		
		if ($featured_value==0) {
			return $this->tableGateway->update(array('featured'=>1),array('id'=>$id));
		}else return$this->tableGateway->update(array('featured'=>0),array('id'=>$id)); 	
	}
	
	/**
	 * CÁC HÀM HỖ TRỢ KHÁC
	 *
	 */
	function getIntroText($text)
	{
		$intro_text=explode('@--Readmore--@',$text);
		if ($intro_text[0]) {
			return $intro_text[0];
		}
	}
	function change_alias ( $alias )
	{
		$alias = $this->nv_EncString( $alias );
		//thêm trường hợp các kí tự đặc biệt
		$alias = preg_replace( "/(!|\"|#|$|%|'|̣)/", '', $alias );
		$alias = preg_replace( "/(̀|́|̉|$|>)/", '', $alias );
		$alias = preg_replace( "'<[\/\!]*?[^<>]*?>'si", "", $alias );
	
		$alias = str_replace( "----", " ", $alias );
		$alias = str_replace( "---", " ", $alias );
		$alias = str_replace( "--", " ", $alias );
	
		$alias = preg_replace( '/(\W+)/i', '-', $alias );
		$alias = str_replace( array(
				'-8220-', '-8221-', '-7776-'
		), '-', $alias );
		$alias = preg_replace( '/[^a-zA-Z0-9\-]+/e', '', $alias );
		$alias = str_replace( array(
				'dAg', 'DAg', 'uA', 'iA', 'yA', 'dA', '--', '-8230'
		), array(
				'dong', 'Dong', 'uon', 'ien', 'yen', 'don', '-', ''
		), $alias );
		$alias = preg_replace( '/(\-)$/', '', $alias );
		$alias = preg_replace( '/^(\-)/', '', $alias );
		return $alias;
	}
	
	function nv_EncString ( $text )
	{
		$text = html_entity_decode( $text );
		//thay thế chữ thuong
		$text = preg_replace( "/(å|ä|ā|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|ä|ą)/", 'a', $text );
		$text = preg_replace( "/(ß|ḃ)/", "b", $text );
		$text = preg_replace( "/(ç|ć|č|ĉ|ċ|¢|©)/", 'c', $text );
		$text = preg_replace( "/(đ|ď|ḋ|đ)/", 'd', $text );
		$text = preg_replace( "/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ę|ë|ě|ė)/", 'e', $text );
		$text = preg_replace( "/(ḟ|ƒ)/", "f", $text );
		$text = str_replace( "ķ", "k", $text );
		$text = preg_replace( "/(ħ|ĥ)/", "h", $text );
		$text = preg_replace( "/(ì|í|î|ị|ỉ|ĩ|ï|î|ī|¡|į)/", 'i', $text );
		$text = str_replace( "ĵ", "j", $text );
		$text = str_replace( "ṁ", "m", $text );
	
		$text = preg_replace( "/(ö|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ö|ø|ō)/", 'o', $text );
		$text = str_replace( "ṗ", "p", $text );
		$text = preg_replace( "/(ġ|ģ|ğ|ĝ)/", "g", $text );
		$text = preg_replace( "/(ü|ù|ú|ū|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|ü|ų|ů)/", 'u', $text );
		$text = preg_replace( "/(ỳ|ý|ỵ|ỷ|ỹ|ÿ)/", 'y', $text );
		$text = preg_replace( "/(ń|ñ|ň|ņ)/", 'n', $text );
		$text = preg_replace( "/(ŝ|š|ś|ṡ|ș|ş|³)/", 's', $text );
		$text = preg_replace( "/(ř|ŗ|ŕ)/", "r", $text );
		$text = preg_replace( "/(ṫ|ť|ț|ŧ|ţ)/", 't', $text );
	
		$text = preg_replace( "/(ź|ż|ž)/", 'z', $text );
		$text = preg_replace( "/(ł|ĺ|ļ|ľ)/", "l", $text );
	
		$text = preg_replace( "/(ẃ|ẅ)/", "w", $text );
	
		$text = str_replace( "æ", "ae", $text );
		$text = str_replace( "þ", "th", $text );
		$text = str_replace( "ð", "dh", $text );
		$text = str_replace( "£", "pound", $text );
		$text = str_replace( "¥", "yen", $text );
	
		$text = str_replace( "ª", "2", $text );
		$text = str_replace( "º", "0", $text );
		$text = str_replace( "¿", "?", $text );
	
		$text = str_replace( "µ", "mu", $text );
		$text = str_replace( "®", "r", $text );
	
		//thay thế chữ hoa
		$text = preg_replace( "/(Ä|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|Ą|Å|Ā)/", 'A', $text );
		$text = preg_replace( "/(Ḃ|B)/", 'B', $text );
		$text = preg_replace( "/(Ç|Ć|Ċ|Ĉ|Č)/", 'C', $text );
		$text = preg_replace( "/(Đ|Ď|Ḋ)/", 'D', $text );
		$text = preg_replace( "/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|Ę|Ë|Ě|Ė|Ē)/", 'E', $text );
		$text = preg_replace( "/(Ḟ|Ƒ)/", "F", $text );
		$text = preg_replace( "/(Ì|Í|Ị|Ỉ|Ĩ|Ï|Į)/", 'I', $text );
		$text = preg_replace( "/(Ĵ|J)/", "J", $text );
	
		$text = preg_replace( "/(Ö|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|Ø)/", 'O', $text );
		$text = preg_replace( "/(Ü|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|Ū|Ų|Ů)/", 'U', $text );
		$text = preg_replace( "/(Ỳ|Ý|Ỵ|Ỷ|Ỹ|Ÿ)/", 'Y', $text );
		$text = str_replace( "Ł", "L", $text );
		$text = str_replace( "Þ", "Th", $text );
		$text = str_replace( "Ṁ", "M", $text );
	
		$text = preg_replace( "/(Ń|Ñ|Ň|Ņ)/", "N", $text );
		$text = preg_replace( "/(Ś|Š|Ŝ|Ṡ|Ș|Ş)/", "S", $text );
		$text = str_replace( "Æ", "AE", $text );
		$text = preg_replace( "/(Ź|Ż|Ž)/", 'Z', $text );
	
		$text = preg_replace( "/(Ř|R|Ŗ)/", 'R', $text );
		$text = preg_replace( "/(Ț|Ţ|T|Ť)/", 'T', $text );
		$text = preg_replace( "/(Ķ|K)/", 'K', $text );
		$text = preg_replace( "/(Ĺ|Ł|Ļ|Ľ)/", 'L', $text );
	
		$text = preg_replace( "/(Ħ|Ĥ)/", 'H', $text );
		$text = preg_replace( "/(Ṗ|P)/", 'P', $text );
		$text = preg_replace( "/(Ẁ|Ŵ|Ẃ|Ẅ)/", 'W', $text );
		$text = preg_replace( "/(Ģ|G|Ğ|Ĝ|Ġ)/", 'G', $text );
		$text = preg_replace( "/(Ŧ|Ṫ)/", 'T', $text );
	
		return $text;
	}
}