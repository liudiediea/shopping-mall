<?php
namespace controllers;

class BlogController{

    public function index(){
        view('blog/index');
    }
    public function add(){
        view('blog/add');
    }
    public function doadd(){

        $blog = new \models\Blog;
        // 为模型填充数据
        $blog->fill($_POST);
        $blog->doadd();
    }
    public function edit(){
        view('blog/edit');
    }

    public function doedit(){

    }
    public function delete(){
        
    }
}