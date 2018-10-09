<?php
namespace controllers;

use models\Admin;

class LoginController{

    public function test(){
        $model = new \models\Admin;
        $model -> getUrlPath(17);       
    }
    public function login(){
        view('login/login');
    }

    public function dologin(){
        $username = $_POST['username'];
        $pass = $_POST['password'];

        // var_dump($pass);die;
        $model = new \models\Admin;
        try{
            $model->login($username,$pass);
            redirect('/');
        }
        catch(\Exception $e){
            //如果登陆失败就走到这
            redirect('/login/login');
        }

        
        
    }
    public function logout(){
        $model = new \models\Admin;
        $model->logout();
        redirect('/login/login');
    }
   
}