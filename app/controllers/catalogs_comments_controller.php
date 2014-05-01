<?php
class CatalogsCommentsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'CatalogsComments';
    public $uses = array('Catalog','CatalogsComment');
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform');
    
    //認証関連の設定
    public function beforeFilter() {
       parent::beforeFilter();
    }
    
    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
    
    public function index(){
        
    }
    
}
?>
