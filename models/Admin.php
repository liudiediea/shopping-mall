<?php
namespace models;

class Admin extends Model
{
    // 设置这个模型对应的表
    protected $table = 'admin';
    // 设置允许接收的字段
    protected $fillable = ['username','password'];
    
    //添加之前自动被执行
    public function _before_write(){
        $this->data['password'] = md5($this->data['password']);
    }

    //添加 修改管理员之后自动被执行
    //添加时候  获取新添加的管理员ID $this->data['id']
    //修改时 获取要修改的管理员Id： $_GET['id']
    public function _after_write(){
    
        $id = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];

        // 删除原数据
        $stmt = $this->_db->prepare('DELETE FROM admin_role WHERE admin_id=?');
        $stmt->execute([
            $id
        ]);

        // 重新添加新勾选的数据
        $stmt = $this->_db->prepare("INSERT INTO admin_role(admin_id,role_id) VALUES(?,?)");
        // 循环所有勾选的角色ID插入到中间表
        foreach($_POST['role_id'] as $v)
        {
            $stmt->execute([
                $id,
                $v,
            ]);
        }
    }

    public function login($username,$pass){
        $stmt = $this->_db->prepare('select * from admin where username=? and password=?');
        $stmt->execute([
            $username,
            md5($pass),
        ]);
        $info = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($info){
            $_SESSION['id'] = $info['id'];
            $_SESSION['username'] = $info['username'];
        }else{
            throw new \Exception('error');
        }
    }
    public function logout(){
        $_SESSION = [];
        session_destroy();
    }
}