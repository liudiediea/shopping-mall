<?php
namespace controllers;

use models\Role;

class RoleController extends BaseController{
    // 列表页
    public function index()
    {
        $model = new Role;
        $data = $model->findAll([
            'fields'=>'a.*,GROUP_CONCAT(c.pri_name) pri_list',
            'join'=>' a LEFT JOIN role_privlege b ON a.id=b.role_id LEFT JOIN privilege c ON b.pri_id=c.id ',
            'groupby'=>' GROUP BY a.id ',
        ]);
       
        view('role/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        $priModel = new \Models\Privilege;
        $data = $priModel->tree();
        // echo '<pre>';
        // var_dump($data);  
        // die;

        //显示表单
        view('role/create',[
            'data'=>$data,
        ]);
    }

    // 处理添加表单
    public function insert()
    {
        $model = new Role;
        $model->fill($_POST);
        $model->insert();
        redirect('/role/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Role;
        $data=$model->findOne($_GET['id']);
        // 取出权限的数据
        $priModel = new \Models\Privilege;
        // 获取树形数据（递归排序好的）
        $pridata = $priModel->tree();
        //取出这个角色所拥有的权限ID
        $priIds = $model->getPriId($_GET['id']);

        
        view('role/edit', [
            'data' => $data, 
            'pridata'=>$pridata,
            'priIds'=>$priIds,
            
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $model = new Role;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/role/index');
    }

    // 删除
    public function delete()
    {
        $model = new Role;
        $model->delete($_GET['id']);
        redirect('/role/index');
    }
}