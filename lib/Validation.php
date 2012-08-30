<?php

/*
    $validation = Plugin::get('riValidation.Validation'); // get the validation object

    // set the rules
    $validation->setRules(array('fieldname1' => array(
        'required', // if required, then this field MUST always have a value
        'isNotEmpty', // if not specified, the default rule (in DefaultRules.php) will be used
        'isInValues' => array(
            'options' => array('zip', 'rar'), // some rule can accept additional parameters
            'message' => 'we need ZIP' // and you can always use your custom message
        ),
        'File::isNotEmpty' // here we use the isNotEmpty rule in FileRules.php instead of the default one
    ));

    $validation->setData(array('fieldname1' => 'abcedd'));

    $validation->validate(); // return true/false. Error, if any, will be in riLog
*/

namespace plugins\riValidation;

use plugins\riPlugin\Plugin;

class Validation{
    private $rules,
    $data = array(),
    $error_count = 0,
    $validation_errors = array(),
    $class = 'validation';

    // class is used to later display error message
    // if ($messageStack->size(class) > 0) echo $messageStack->output(class);
    public function setErrorClass($class){
        $this->class = $class;
    }

    /**
     * @param $rules
     * array('field_name' => array('rule' => 'message'))
     */
    public function setRules($rules){
        $this->rules = $rules;
    }

    public function addRules($rules){
        foreach($rules as $key => $value){
            if(array_key_exists($key, $this->rules))
                $this->rules[$key][] = $value;
            else
                $this->rules[$key] = $value;
        }
    }

    public function setData($params){
        $this->data = array_merge($this->data, $params);
    }

    public function validate(){
        $validated = true;
        // we loop by the rule to make sure if the the field is not passed we still check for it
        foreach($this->rules as $field => $rules){

            if((!in_array('required', $rules) && !isset($rules['required'])) && empty($this->data[$field])){
                continue;
            }

            foreach($rules as $key => $value){
                if(is_numeric($key)){
                    $validator = $value;
                    $parameters = array();
                }
                else{
                    $validator = $key;
                    $parameters = $value;
                }

                if(strpos($validator, '::') != false){
                    list($class, $method) = explode('::', $validator);
                }
                else{
                    $class = 'Default';
                    $method = $validator;
                }

                $class .= 'Rules';

                if(($validation_object = Plugin::get('riValidation.' . $class)) !== false){
                    if(method_exists($validation_object, $method)){
                        if(!$validation_object->$method($this->data[$field], $parameters['options'])){
                            $message = (is_array($parameters) && isset($parameters['message'])) ? $parameters['message'] : $validation_object->getMessage($method);

                            Plugin::get('riLog.Logs')->add(array('message' => ri($message, array(
                                '%field%' => $field,
                                '%value%' => $this->data[$field],
                                '%method%' => $method
                            ))));
                            $validated = false;
                        }
                    }
                    else{
                        Plugin::get('riLog.Logs')->add(array(
                            'message' => ri("Could not call method '%method%' to validate field '%field%' with the value '%value%'", array(
                                '%method%' => $method,
                                '%field%' => $field,
                                '%value%' => $this->data[$field]
                                )
                            ))
                        );
                        $validated = false;
                    }
                }
            }
        }

        return $validated;
    }
}
