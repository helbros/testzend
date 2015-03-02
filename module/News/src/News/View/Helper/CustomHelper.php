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
	function getCate($id){
		
	}
}