<?php
namespace controllers;

class BlogsController{

    public function index(){
        view('blogs/index');
    }
    public function add(){
        view('blogs/add');
    }
    public function doadd(){

    }
    public function edit(){
        view('blogs/edit');
    }

    public function doedit(){

    }
    public function delete(){
        
    }
}