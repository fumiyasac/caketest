<?php
Class AboutsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Abouts';
    public $uses = array();
    public $layout = 'common_format_blog';
    public $components = array();
    public $helpers = array();
    
    //このサイトに関して
    public function index(){
        
        try{
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','このブログについて');
            $this->set('breadcrumb_name','&nbsp;&gt;&nbsp;このブログについて');

            
        }catch(Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/abouts');
        }
        
    }
}
?>