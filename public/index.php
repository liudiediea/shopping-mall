<?php

    define('ROOT',__DIR__.'/../');


    session_start();

    //引入视图文件
    require(ROOT.'libs/view.php');
    //类的自动加载
     function auload($class){

        $path = str_replace('\\','/',$class).'.php';
        // var_dump(ROOT.$path);
        require(ROOT.$path);
        
      

    }
    spl_autoload_register('auload');

    //解析路由
    $controller = '\controllers\IndexController';
    $action = 'index';

    if(isset($_SERVER['PATH_INFO'])){

        $route = explode('/', $_SERVER['PATH_INFO']);
        $controller = '\controllers\\'.ucfirst($route[1]).'Controller';
        $action = $route[2];
    }

    $c = new $controller;
    $c->$action();
    
    