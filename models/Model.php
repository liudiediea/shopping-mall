<?php
namespace models;
/*
    所有的模型的父模型
    在这里实现所有的  添加 修改 删除 翻页等功能
*/

class Model{

    protected $db;
    //操作的表名 具体由子模型决定
    protected $table;
    //表单中的数组 借由子类来设置
    protected $data;

    public function __construct(){

        $this->db = \libs\Db::make();
    }
   
    public function doadd(){
        
        $keys=[];
        $values=[];
        $token=[];

        foreach($this->data as $k => $v)
        {
            $keys[] = $k;
            $values[] = $v;
            $token[] = '?';
        }
        //取出数组中所有的键  组成一个新的数组
        // $keys = array_keys($this->data);
        //转为字符串
        $keys = implode(',', $keys);
        // var_dump($keys);
        // die;
        $token = implode(',', $token);
        //取出数组中所有的值  组成一个新的数组
        // $values = array_values($this->data);
        // var_dump($keys,$token,$values);
        // die;
        // $sql = "INSERT INTO {$this->table}($keys) VALUES($token)";
        $sql = "insert into {$this->table}($keys) values($token)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    public function edit(){

    }
    public function delete(){

    }
    public function findAll(){

    }
    public function findOne(){

    }

   // 接收表单中的数据
   public function fill($data)
   {
    //    var_dump($data);
    //    die;
      // 判断是否在白名单 中
      foreach($data as $k => $v)
      {
          if(!in_array($k, $this->fillable))
          {
              unset($data[$k]);
          }
      }
      $this->data = $data;
   }
}