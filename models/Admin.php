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

            //判断是否是超级管理员
           $stmt =  $this->_db->prepare('select count(*) from admin_role where role_id = 1 and admin_id='.$_SESSION['id']);
           $stmt->execute([$_SESSION['id']]);
           $c = $stmt->fetch(\PDO::FETCH_COLUMN);
            if($c > 0){
               
                $_SESSION['root'] = true;
            }
            else{
                 //取出这个管理员有权访问的路径
                $_SESSION['url_path'] = $this->getUrlPath( $_SESSION['id']);
            }
          
        }else{
            throw new \Exception('error');
        }
    }
    public function logout(){
        $_SESSION = [];
        session_destroy();
    }

    public function getUrlPath($adminId){
        $sql = '
             select p.url_path,ar.role_id
            from admin_role ar
            left JOIN role_privlege rp on ar.role_id = rp.role_id
            left JOIN privilege p on rp.pri_id = p.id
            where ar.admin_id = ?';
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $adminId,
        ]);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        //把二维数组转为一维数组
        $ret = [];
        foreach($data as $v){
            
            if(false === strpos($v['url_path'],',')){
                $ret[] = $v['url_path'];
            }
            else{
                //如果有， 就转成数组
                $tt = explode(',',$v['url_path']);
                //把转完之后的数组合并到一维数组中
                $ret = array_merge($ret,$tt);
            }
            
        }
        return $ret;

    }
}