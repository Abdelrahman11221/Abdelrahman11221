<?php

class validation{
    private $_passed = false,
            $_error = array(),
            $_db = null;
    
    public function __construct() {
        $this->_db = DB::getinstanceof();  // get the instance of database class.
    }

    public function  check($data , $fields = array()) {

        foreach ($fields as $field => $rules) {
            foreach( $rules as $rule => $rule_value){
                
                $value = trim($data[$field]);
                $field = escape($field);
                
                if($rule === 'required' && empty($value)){
                    $this->addError("{$field} is required");
                }
                
                elseif (!empty($value)){
                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$field} mustn't be minimum {$rule_value}");
                            }
                        break;

                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("the maximum of {$field} is 50");
                            }
                        break;

                        case 'matches':
                            if($value != $data[$rule_value]){
                                $this->addError("the {$rule_value} must be match the {$field}");
                            }
                        break;

                        case 'unique':
                            $check = $this->_db->get($rule_value , array($field , '=' , $value));
                            if($check->count()){
                                $this->addError("The {$field} already exists.");
                            }
                        break;
                        
                        default:

                    }
                }
            }
    }
    
    if(empty($this->_error)){
        $this->_passed = true;
    }
    return $this;
}

    private function addError($error){
        $this->_error[]=$error;
    }

    public function error()
    {
        return $this->_error;
    }

    public function passed()
    {
        return $this->_passed;
    }

}

?>