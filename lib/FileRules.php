<?php

namespace plugins\riValidation;

class FileRules extends Rules {
	
	static function isExtension($value, $options){
		$ext_list = explode('|',$options);
		if(!is_array($value['tmp_name'])){
			$ext = end(explode('.', $value['tmp_name']));
			return in_array($ext, $ext_list);
		}
		else{
			foreach($value['tmp_name'] as $name){
				$ext = end(explode('.', $name));
				if(!in_array($ext, $ext_list))
					return false;
			}
		}
		return true;
	}

    /**
     * @static
     * @param $value: $_FILES['something']
     * @param $options array("image/gif", "image/png", "image/jpeg", "image/pjpeg")
     */
    static function isMimeType($value, $options){
        $files = is_array($value['tmp_name']) ? $value['tmp_name'] : array($value['tmp_name']);

        foreach ($files as $file){
            if(!file_exists($file)) return false;

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file);

            if(!in_array($mime, $options))
                return false;
        }
        return true;
    }
	
	static function isNotEmpty($value, $options){
		return !empty($value['name']);
	}
}