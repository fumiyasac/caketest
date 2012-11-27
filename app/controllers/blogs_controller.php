<?php
class BlogsController extends AppController{

    //使用モデル名の指定
    public $uses = array('Blog');
    
    //使用するレイアウト
    public $layout = 'cakephp_blog';

    //ページネーションの指定
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'timestamp',
            'name',
            'subject',
            'email',
            'message',
            'image_path',
            'created',
        ),
        'sort' => 'id',
        'limit' => 5
    );
    
    public function admin_index(){
        
        //データを変数resultへセット
        $data = $this->paginate();
        $this->set('result',$data);
        
        //表示件数の転送
        $kensu = $this->paginate['limit'];
        $this->set('kensu',$kensu);
    }
    
    public function admin_add(){
        
        //画像名用に最大IDナンバーを取得する
        $number = $this->Blog->field('id',array(),'Blog.id desc');
        if(empty($number)){
            $number = 0;
        }
        ++$number;
        
        //key別の変数に追加する
        extract($this->data['Blog']);
        
        if(!empty($name) && !empty($subject) && !empty($message)){
            
            //img_path変数の初期化
            $image_path = '';
            
            if($this->data['Blog']['imageUp']['name'] != ''){
                
                if($this->data['Blog']['imageUp']['type'] == 'image/gif'){
                    $image_name = $number.'.gif';
                }else if($this->data['Blog']['imageUp']['type'] == 'image/jpeg'){
                    $image_name = $number.'.jpg';
                }else if($this->data['Blog']['imageUp']['type'] == 'image/pjpeg'){
                    $image_name = $number.'.jpg';
                }else if($this->data['Blog']['imageUp']['type'] == 'image/png'){
                    $image_name = $number.'.png';
                }else{
                    
                    //$this->cakeError("eSyori",array("eStr" => "あれ？"));
                }
                
                //保存パスと画像パスを作成して画像を移動する
                $save_image_path = IMAGES.'pic/'.$image_name;
                $image_path = 'pic/'.$image_name;
                move_uploaded_file($this->data['Blog']['imageUp']['tmp_name'], $save_image_path);
            }
            
            //「image_path」フィールドを追加
            $this->data['Blog']['image_path'] = $image_path;
            //「timestamp」フィールドを追加
            $this->data['Blog']['timestamp'] = time();
            
            //Blogモデルへのデータ保存
            $this->Blog->save($this->data);
            //indexアクションへリダイレクト            
            $this->redirect("admin_index");
            //echo $save_image_path;
            
        }else{
            $this->cakeError("eSyori",array("eStr" => $this->data['Blog']));
        }
        
    }
    
    public function admin_delete(){
        $delelePwd = "123456";
        
        //パスワードの照合
        if($this->data['Blog']['delete_pwd'] == $delelePwd){
            
            //表示件数の取得
            $kensu = $this->paginate['limit'];
            
            //表示件数分の繰り返し
            for($i=0;$i<$kensu;++$i){
                //チェックデータの判定
                if(isset($this->data['Blog'][$i]) && $this->data['Blog'][$i] != 0){
                    $id = $this->data['Blog'][$i];
                    
                    //画像アイコンを削除
                    $ikken = $this->Blog->read('image_path',$id);
                    if($ikken['Blog']['image_path'] != ''){
                        $image_save_path = IMAGES.$ikken['Blog']['image_path'];
                        unlink($image_save_path);
                    }
                    
                    //Blogモデルのデータ削除
                    $this->Blog->delete($id);

                }
            }
            //indexアクションへリダイレクト            
            $this->redirect("admin_index");
        }else{
            $this->cakeError("eSyori",array("eStr" => "パスワードが間違っています。"));
        }
    }
}
?>
