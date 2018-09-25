<?php
namespace libs;
class Db{
    private static $obj = null;
    private $pdo;
    private function __construct(){
        self::$obj = new \PDO("mysql:dbhost=127.0.0.1;dbname=shop","root","");
        $this->pdo->exec("set names utf8");
    }
    private function __clone(){}

    public function make(){
        if(self::$obj == null){
            self::$obj = new self;
        }
        return self::$obj;
    }
}