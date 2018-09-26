<?php
namespace controllers;

class CodeController{

    public function make(){

        
        //1.接收参数
        $tableName = $_GET['name'];

        

        //2.生成控制器
        $cname = ucfirst($tableName).'Controller';
        
        ob_start();
        include(ROOT.'copy/controller.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.'Controllers/'.$cname.'.php', "<?php\r\n".$str);


        //生成模型
        $mname = ucfirst($tableName);
        
        ob_start();
        include(ROOT.'copy/model.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.'models/'.$mname.'.php', "<?php\r\n".$str);

        //生成视图
        @mkdir(ROOT.'views/'.$tableName,0777);
        

        //取出这个表中所有的字段信息
        $sql = "show full fields from $tableName";
        $db = \libs\Db::make();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $fields = $stmt->fetchAll( \PDO::FETCH_ASSOC);

        // echo '<pre>';
        // var_dump($fields);
        // die;


        ob_start();
        include(ROOT.'copy/add.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/add.html',$str);
        
        ob_start();
        include(ROOT.'copy/edit.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/edit.html',$str);
        
        ob_start();
        include(ROOT.'copy/index.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/index.html',$str);


    }
}