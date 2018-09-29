<?php
namespace models;

class Brand extends Model
{
    // 设置这个模型对应的表
    protected $table = 'brand';
    // 设置允许接收的字段
    protected $fillable = ['brand_name','logo'];

    //添加修改之前被调用
    public function _before_write(){

        $this->delete_img();
        //添加上传图片的代码
        $upload = \libs\Upload::getInstance();
        $logo = '/uploads/'.$upload->upload('logo','brand');

        //吧logo加到数组中 插入数据库
        $this->data['logo'] = $logo;

    }
    
    //删除之前被调用
    public function _before_delete(){
       $this->delete_img();
    }
     public function delete_img(){
         
        //如果修改就删除原图片
        if(isset($_GET['id'])){
            
            //取出原来logo 并且删除
            $oldlogo = $this->findOne($_GET['id']);
            @unlink(ROOT.'public'.$oldlogo['logo']);
        }
     }
}