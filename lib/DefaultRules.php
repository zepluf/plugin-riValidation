<?php

namespace plugins\riValidation;

class DefaultRules extends Rules {

    protected $messages = array('isNotEmpty' => "field '%field%' with value '%value%' is empty");
	public function isNotEmpty($value, $options){
		return !empty($value);
	}
	
	public function isNumber($value, $options){
		return is_numeric($value);
	}
	
	public function isPositive($value, $options){
		if($this->isNumber($value, $options))
			return (int)$value > 0;
		return false;
	}
	//YYYY-MM-DD
	public function isDate($value, $options){
		if (ereg ("([0-9]{4})-([0-9]{2})-([0-9]{2})", $value, $regs))
	    	if((int)$regs[3]<=31 && (int)$regs[3]>0) // check date
	    		 if((int)$regs[2]<=12 && (int)$regs[2]>0) // check month
	    		 	if((int)$regs[1]>0) // check year
	    		 		return true;
		return false;		
	}	
	
	public function isEmail($value, $options){
		return zen_validate_email($value);
	}
	
	public function isUrl($value, $options){
		$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
		if (eregi($urlregex, $value)) 
		return true;
		else 
		return false;  
	}
	
	public function isInValues($value, $options){
		return in_array($value, $options);
	}
}