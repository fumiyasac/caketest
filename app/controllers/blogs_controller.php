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
            'name',
            'subject',
            'email',
            'message',
            'created',
        ),
        'sort' => 'id',
        'limit' => 5
    );
    
    function admin_index(){
        
        //データを変数resultへセット
        $data = $this->paginate();
        $this->set('result',$data);
        
        //表示件数の転送
        $kensu = $this->paginate['limit'];
        $this->set('kensu',$kensu);
    }
    
    function admin_add(){
        
        //key別の変数に追加する
        extract($this->data['Meibo']);
        
        if(!empty($name) && !empty($subject) && !empty($message)){
            
            //「img_path」フィールドを追加
            $this->data['Blog']['img_path'] = '';
            //「timestamp」フィールドを追加
            $this->data['Blog']['timestamp'] = time();
            
            //Blogモデルへのデータ保存
            $this->Meibo->save($this->data);
            //indexアクションへリダイレクト            
            $this->redirect("index");
            
        }else{
            $this->cakeError("eSyori",array("eStr" => "必須項目を全て入力して下さい。（投稿者・タイトル・内容）"));
        }
        
    }
}
?>
