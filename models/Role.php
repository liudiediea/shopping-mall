<?php
namespace models;

class Role extends Model
{
    // 设置这个模型对应的表
    protected $table = 'role';
    // 设置允许接收的字段
    protected $fillable = ['role_name'];

   public function _after_write(){
       
        $sql = $this->_db->prepare('insert into role_privlege(pri_id,role_id) values(?,?)');

        foreach($_POST['pri_id'] as $v){

            $sql -> execute([
                $v,
                $this->data['id'],
            ]);
        }
   }

   public function _after_delete(){
       $stmt = $this->_db->prepare('delete from role_privlege where role_id=?');
       $stmt->execute([
           $_GET['id']
       ]);
   }
}