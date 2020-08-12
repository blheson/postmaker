<?php
Namespace Controller;

class Factory{
    public function __construct(){
        
    }
    public function get_factory($class){
        require $class.'.php';
        $namespace = '\\'.$class;
        return new $namespace;
    }
    public function get_unique_id(){
        return preg_replace('|[^0-9]|', '', session_id());
    }
   
}