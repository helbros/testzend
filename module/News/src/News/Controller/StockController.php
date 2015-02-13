<?php
namespace News\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Dom\Query;
use Zend\View\Model\JsonModel;
class StockController extends AbstractActionController {
	function indexAction() {
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://www.fpts.com.vn/ListFile/_1_1.xml');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$xml_stock_increase=curl_exec($ch);				
		curl_setopt($ch, CURLOPT_URL, 'http://www.fpts.com.vn/ListFile/_1_2.xml');
		$xml_stock_down=curl_exec($ch);
		curl_setopt($ch, CURLOPT_URL, 'http://www.fpts.com.vn/ListFile/_1_3.xml');
		$stock_match=curl_exec($ch);
		$stock_increase=simplexml_load_string($xml_stock_increase) or die("Error: Cannot create object");
		$stock_down=simplexml_load_string($xml_stock_down) or die("Error: Cannot create object");
		$stock_match=simplexml_load_string($stock_match) or die("Error: Cannot create object");							
		return new ViewModel(array(
				'stock_most_increase'=>$stock_increase,
				'stock_most_down'=>$stock_down,
				'stock_most_match'=>$stock_match,				
		));	
	}
	function getstockinfoAction(){
		$stock_name=strtoupper($this->params()->fromRoute('stockname'));
		$stock_name? :die();	
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://priceboard.fpts.com.vn/ho4/VN/get.asp?a=1');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$hose_temp=curl_exec($ch);
		$hose=new Query($hose_temp);
		$hose->setEncoding('utf-8');
		$results = $hose->execute("#tr$stock_name td");
		//$res=$hose->queryXpath('/html/body/table[4]/tr[3]/td');		
		$stock=new \stdClass();
		$stock->name='3';
		$stock->tran='';
		$stock->san='';
		$stock->tham_chieu='';			
		$stock->du_mua_gia_3='';
		$stock->du_mua_KL_3='';				
		$stock->du_mua_gia_2='';
		$stock->du_mua_KL_2='';
		$stock->du_mua_gia_1='';
		$stock->du_mua_KL_1='';		
		$stock->gia_khop='';
		$stock->khoi_luong_khop='';
		$stock->tang_giam='';
		$stock->du_ban_gia_1='';
		$stock->du_ban_KL_1='';
		$stock->du_ban_gia_2='';
		$stock->du_ban_KL_2='';
		$stock->du_ban_gia_3='';
		$stock->du_ban_KL_3='';	
		$stock->tong_KL='';
		$stock->mo_cua='';
		$stock->cao_nhat='';
		$stock->thap_nhat='';
		$stock->nn_mua='';		
		
		$temp_arr=array();
		$count_index=0;
		foreach ($results as $val){
			if ($count_index!=20 and $count_index!=25){
				$temp_arr[]=$val->textContent;			
			}
			$count_index++;			
		}				
		$count=0;
		foreach ($stock as $key=>$s){			
			$stock->{$key}=$temp_arr[$count];
			$count++;
		}
		/* echo '<br>';		
		echo print_r($stock);
		echo '<br>';
		echo $stock_name;
		echo '<br>';	
		echo print_r($temp_arr); */
		return new JsonModel(array('stock_info'=>$stock));
	}
	function viewstockAction(){
		//http://ajax.vietstock.vn/GetChart.ashx
		$stock_name=strtoupper($this->params()->fromRoute('stockname'));
		$stock_name? :die();
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://priceboard.fpts.com.vn/ho4/VN/get.asp?a=1');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$hose_temp=curl_exec($ch);
		$hose=new Query($hose_temp);
		$hose->setEncoding('utf-8');
		$results = $hose->execute("#tr$stock_name td");
		//$res=$hose->queryXpath('/html/body/table[4]/tr[3]/td');
		$stock=new \stdClass();
		$stock->name='3';
		$stock->tran='';
		$stock->san='';
		$stock->tham_chieu='';
		$stock->du_mua_gia_3='';
		$stock->du_mua_KL_3='';
		$stock->du_mua_gia_2='';
		$stock->du_mua_KL_2='';
		$stock->du_mua_gia_1='';
		$stock->du_mua_KL_1='';
		$stock->gia_khop='';
		$stock->khoi_luong_khop='';
		$stock->tang_giam='';
		$stock->du_ban_gia_1='';
		$stock->du_ban_KL_1='';
		$stock->du_ban_gia_2='';
		$stock->du_ban_KL_2='';
		$stock->du_ban_gia_3='';
		$stock->du_ban_KL_3='';
		$stock->tong_KL='';
		$stock->mo_cua='';
		$stock->cao_nhat='';
		$stock->thap_nhat='';
		$stock->nn_mua='';
		
		$temp_arr=array();
		$count_index=0;
		foreach ($results as $val){
			if ($count_index!=20 and $count_index!=25){
				$temp_arr[]=$val->textContent;
			}
			$count_index++;
		}
		$count=0;
		foreach ($stock as $key=>$s){
			$stock->{$key}=$temp_arr[$count];
			$count++;
		}
		return array('stock_info'=>$stock);
	}
}