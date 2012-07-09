<?php
/**
 * Created by RubikIntegration Team.
 * User: vunguyen
 * Date: 7/9/12
 * Time: 2:24 PM
 * Question? Come to our website at http://rubikintegration.com
 */

namespace plugins\riValidation;

abstract class Rules{
    protected $messages = array();
    public function getMessage($method){
        return isset($this->messages[$method]) ? $this->messages[$method] : "field '%field%' with value '%value%' does not pass the validation rule '%method%'";
    }
}