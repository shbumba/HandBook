<?php
namespace HandBook;

class Exception extends \Exception {
    protected $errors = array();

    public function __construct($message = '', array $errors = array(), $code = 0, Exception $previous = null)
    {
        $this->setErrors($errors);
        parent::__construct($message, $code, $previous);
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}