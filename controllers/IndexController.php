<?php
namespace controllers;

class IndexController{

    public function index(){
       
        view('index/index',[
            "name" => "tom",
            "age" => 12,
        ]);
    }
}