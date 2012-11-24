<?php
class C06sController extends AppController{
    public $name = "C06s";
    public $uses = null; //使用モデルの指定
    public $layout = "cakephp_kihon"; //オリジナルレイアウトの指定
    
    function index(){
        //タイトルの出力
	$this->set("title_for_layout","c06テスト");
        
        //変数の初期化と条件分岐
        $result = "";
        
        if(!empty($this->params["url"]["shimei"])){
            extract($this->params["url"]);
            $result = "あなたの名簿情報は{$shimei}、{$jyusho}、{$denwa}です。<br />";
        }else{
            $result = "あなたの名簿情報を入力して下さい。";
        }
        
        $this->set("result", $result);
    }
    
}
?>
