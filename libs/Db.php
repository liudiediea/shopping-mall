<?php
/*
    执行预处理 用prepare
    非预处理用 exec
*/
namespace libs;
class Db{
    private static $obj = null;
    private $pdo;
    private function __construct(){
        $this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=shop','root','');
        $this->pdo->exec("set names utf8");
    }
    private function __clone(){}

    public static function make(){
        if(self::$obj == null){
            self::$obj = new self;
        }
        return self::$obj;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);        
    }

    public function exec($sql){
        return $this->pdo->exec($sql);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}