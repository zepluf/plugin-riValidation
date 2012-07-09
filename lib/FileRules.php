<?php

namespace plugins\riValidation;

class FileRules extends Rules {
	
	static function isExtension($value, $options){
		$ext_list = explode('|',$options);
		if(!is_array($value['name'])){
			$ext = end(explode('.', $value['name']));
			return in_array($ext, $ext_list);
		}
		else{
			foreach($value['name'] as $name){
				$ext = end(explode('.', $name));
				if(!in_array($ext, $ext_list))
					return false;
			}
		}
		return true;
	}
	
	static function isNotEmpty($value, $options){
		return !empty($value['name']);
	}
}