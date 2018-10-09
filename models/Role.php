<?php
namespace models;

class Role extends Model
{
    // 设置这个模型对应的表
    protected $table = 'role';
    // 设置允许接收的字段
    protected $fillable = ['role_name'];

   public function _after_write(){
       
        //exit
        $roleId = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];
        //删除原权限
        $stmt = $this->_db->prepare('delete from role_privlege where role_id=?');
        $stmt->execute([
            $roleId,
        ]);
        
        //重新添加新勾选的选项
        $sql = $this->_db->prepare('insert into role_privlege(pri_id,role_id) values(?,?)');

        foreach($_POST['pri_id'] as $v){
            $sql -> execute([
                $v,
                $roleId,
            ]);
        }
   }

   public function _after_delete(){
       $stmt = $this->_db->prepare('delete from role_privlege where role_id=?');
       $stmt->execute([
           $_GET['id']
       ]);
   }

   public function getPriId($roleId){
       //预处理
       $stmt = $this->_db->prepare('select pri_id from role_privlege where role_id=?');
       //执行
       $stmt->execute([
           $roleId,
       ]);
       //取数据
       $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
       //转成一维数组
       $_ret = [];
       foreach($data as $k=>$v){
           $_ret[] = $v['pri_id'];
       }
       return $_ret;
   }

}