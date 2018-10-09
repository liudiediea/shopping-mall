<?php
namespace controllers;

class BaseController{

    public function __construct(){

        if(!isset($_SESSION['id'])){
            
            redirect('/login/login');
        }
        //是否是超级管理员
        if(isset($_SESSION['root'])){
            return;
        }
        //获取要访问的路径
        $path = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'],'/') : 'index/index';
        //设置一个白名单
        $list = ['index/index','index/left','index/top','index/main'];
        //判断是否有权访问
        if(!in_array($path,array_merge($list,$_SESSION['url_path']))){
            die("无权访问");
        }
    }
}