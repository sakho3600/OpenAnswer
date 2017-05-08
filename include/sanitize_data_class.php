<?php

class sanitize_data{
	
	public static function sanitize_array($arr_r){
		foreach ($arr_r as &$val) is_array($val) ? self::sanitize_array($val):$val = self::sanitize_string($val);
		unset($val);
		return $arr_r;
	}
	
	public static function sanitize_string($string){
		$string = filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);
		$string = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		return $string;
	}
	
	public static function sanatize_email($string){
		$string = filter_var($string, FILTER_SANITIZE_EMAIL);
		if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
			$string = $this->sanitize_string($string);
			return $string;
		}
		else{
			return "0x1 Error: $string is not a valid e-mail address.";
		}
	}
	
	public static function sanatize_url($url){
		$url = filter_var($url, FILTER_SANITIZE_URL);
		if(filter_var($url, FILTER_VALIDATE_URL)){
			$url = $this->sanitize_string($url);
			return $url;
		}
		else{
			return "0x1 Error: The URL submitted is not valid.";
		}
	}
	
	public static function desanitize_array($arr_r){
		foreach ($arr_r as &$val) is_array($val) ? self::desanitize_array($val):$val = stripslashes($val);
		unset($val);
		return $arr_r;
	}

}


?>