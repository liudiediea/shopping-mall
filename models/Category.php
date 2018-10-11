<?php
namespace models;

class Category extends Model
{
    // 设置这个模型对应的表
    protected $table = 'category';
    // 设置允许接收的字段
    protected $fillable = ['cat_name','parent_id','path'];

     // 取出一个分类的子分类
    // 参数：上级分类的ID
    public function getclass($parent_id = 0)
    {
        return $this->findAll([
            'where' => "parent_id=$parent_id"
        ]);
    }

     //递归
     public function tree(){
        $data = $this->findAll([
            'per_page'=>'99999999',

        ]);
        //递归排序
        return $this->sort($data['data']);
    }
    //递归排序
    //                   排序的数组  顶级父类id  当前分类级别
    public function sort($data, $parent_id=0, $level=0){

        static $arr=[];
        foreach($data as $v){
            if($v['parent_id'] == $parent_id){
                $v['level'] = $level;
                $arr[] = $v;

                $this->sort($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }
    
}