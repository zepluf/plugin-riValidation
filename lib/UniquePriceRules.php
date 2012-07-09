<?php

namespace plugins\riValidation;

use plugins\riPlugin\Plugin;

class UniquePriceRules extends Rules {
	
	static function isNotEmpty($value, $options){var_dump($value);
		return Plugin::get('riValidation.DefaultRules')->isNotEmpty($value[1][1]['options_values_price'], $options);
	}
	
	static function isNumber($value, $options){
		return Plugin::get('riValidation.DefaultRules')->isNumber($value[1][1]['options_values_price'], $options);
	}
}