<?php

namespace News\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CustomHelper extends AbstractHelper {
	function getFirstLink($text) {
		if (preg_match ( '/http:\/\/(.+)(jpg|png)/', $text, $matches )) {
			return $matches [0];
		} else
			return null;
	}
	function getCate($id) {
	}
	function cutReadmore($text, $limit) {
		$s = explode ( ' ', $text );
		$count = 0;
		$result = '';
		for($count; $count <= $limit; $count ++) {
			$result .= $s [$count] . ' ';
		}
		return $result;
	}
	function baseUrl() {
		$uri = $this->getRequest ()->getUri ();
		$scheme = $uri->getScheme ();
		$host = $uri->getHost ();
		$base = sprintf ( '%s://%s', $scheme, $host );
		return $base_url = $uri->getScheme () . '://' . $uri->getHost () . $uri->getPath ();
	}
	function add_prefixCate($path){
		$prefix_level='';
		$level=substr_count($path, '-');
		for ($i=1;$i<=$level;$i++){
			$prefix_level.='---------/';
		}
		return $prefix_level;
	}
}