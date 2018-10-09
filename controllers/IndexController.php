<?php
namespace controllers;

class IndexController extends BaseController{

    public function index(){
       
        view('index/index');
    }
    public function main(){
        view('index/main');
    }
    public function left(){
        view('index/left');
    }
    public function top(){
        view('index/top');
    }
}