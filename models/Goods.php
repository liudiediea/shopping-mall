<?php
namespace models;

class Goods extends Model
{
    // 设置这个模型对应的表
    protected $table = 'goods';
    // 设置允许接收的字段
    protected $fillable = ['goods_name','logo','is_on_sale','description','cat1_id','cat2_id','cat3_id','brand_id'];

    public function _before_write(){
        
        // echo '<pre>';
        // var_dump($_FILES);
        //判断是否上传新图片
       if($_FILES['logo']['error'] == 0){
            $this->delete_img();
            //添加上传图片的代码
            $upload = \libs\Upload::getInstance();
            $logo = '/uploads/'.$upload->upload('logo','goods');

            //吧logo加到数组中 插入数据库
            $this->data['logo'] = $logo;
       }
      
        
    }

    //添加：获取商品id  用 $this->data['id']
    //修改时： 获取商品id  用 $_GET['id']
    public function _after_write(){
        //判断是添加还是修改
        $goodId = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];
        /*
            处理商品属性
        */
        // var_dump($_POST);
        // 先删除原来的属性
        $stmt = $this->_db->prepare("DELETE FROM goods_attribute WHERE goods_id=?");
        $stmt->execute([$goodId]);

        $stmt = $this->_db->prepare("INSERT INTO goods_attribute
        (attr_name,attr_value,goods_id) VALUES(?,?,?)");
        foreach($_POST['attr_name'] as $k=>$v){
              $stmt->execute([
                $v,
                $_POST['attr_value'][$k],
                $goodId,
            ]);
        
        }
         /*
            处理商品图片
        */
        $upload = \libs\Upload::getInstance();

        $stmt = $this->_db->prepare("INSERT INTO goods_image(goods_id,path) VALUES(?,?)");

        $_tmp = [];
        
       
        foreach($_FILES['image']['name'] as $k=>$v){
            //判断图片是否为空
            if($_FILES['image']['error'] == 0){
                // 拼出每张图片需要的数组
                $_tmp['name'] = $v;
                $_tmp['type'] = $_FILES['image']['type'][$k];
                $_tmp['tmp_name'] = $_FILES['image']['tmp_name'][$k];
                $_tmp['error'] = $_FILES['image']['error'][$k];
                $_tmp['size'] = $_FILES['image']['size'][$k];
                
                $_FILES['tmp'] = $_tmp;
                //           tmp： 图片在 $_FILES中的名字  
                $path = '/uploads/'.$upload->upload('tmp','goods');

                $stmt->execute([
                    $this->data['id'],
                    $path,
            ]);
            }    

        }
         /*
            处理商品sku
        */
        // 先删除原来的sku
        $stmt = $this->_db->prepare("DELETE FROM goods_sku WHERE goods_id=?");
        $stmt->execute([$goodId]);
        $stmt = $this->_db->prepare("INSERT INTO goods_sku
                (goods_id,sku_name,stock,price) VALUES(?,?,?,?)");
       
        foreach($_POST['sku_name'] as $k => $v)
        {
            $stmt->execute([
               $goodId,
                $v,
                $_POST['stock'][$k],
                $_POST['price'][$k],
            ]);
        } 
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

     
    public function getfullInfo($id){
        $info = $this->findOne($id);
        //获取商品属性信息
        $stmt = $this->_db->prepare('select * from goods_attribute where goods_id=?');
        $stmt->execute([
            $id,
        ]);
        $attr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //获取商品图片
        $stmt = $this->_db->prepare('select * from goods_image where goods_id=?');
         $stmt->execute([
            $id,
        ]);
        $image = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //获取商品SKU
        $stmt = $this->_db->prepare('select * from goods_sku where goods_id=?');
         $stmt->execute([
            $id,
        ]);
        $sku = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        //返回所有的数据
        return[
            'info'=>$info,
            'attr'=>$attr,
            'image'=>$image,
            'sku'=>$sku,
        ];
    }
}