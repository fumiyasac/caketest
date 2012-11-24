<?php
class C05sController extends AppController{
    public $name = "C05s";
    public $uses = null; //使用モデルの指定
    public $layout = "cakephp_kihon"; //オリジナルレイアウトの指定
    
    function index(){
        //タイトルの出力
	$this->set("title_for_layout","c05テスト");
    }
    
}
?>
