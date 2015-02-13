<?php
namespace News\Form;
use Zend\Validator\AbstractValidator;
class MyValid extends AbstractValidator {
	const VIP='vip';
	protected $messageTemplates=array(
			self::VIP=>"'%value% không phải VIP"
	);
	public function isValid($value) {
		$this->setValue($value);
		if ($value!='tuan'){
			$this->error(self::VIP);
			return false;
		}
		return true;
	}
}