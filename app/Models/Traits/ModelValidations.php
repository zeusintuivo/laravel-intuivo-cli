<?php namespace PHIWeb\Models\Traits;

trait ModelValidations {
    public $errors;

    public function validate($data) {
        $Reflection = new \ReflectionClass(__CLASS__);
        $ReflectionClass = $Reflection->newInstance();
        if(empty($ReflectionClass->rules)) return TRUE;

        $v = Validator::make($data, $ReflectionClass->rules);

        if($v->fails()) {
            $this->errors = $v->failed();
            return FALSE;
        }

        return TRUE;
    }

    public function validationErrors() {
        return $this->errors;
    }
}