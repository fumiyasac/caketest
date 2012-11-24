<?php
class C07sController extends AppController{
    public $name = "C07s";
    public $uses = null; //使用モデルの指定
    public $layout = "cakephp_kihon"; //オリジナルレイアウトの指定
    
    function index(){
        //タイトルの出力
	$this->set("title_for_layout","c07テスト");
        
        //変数の初期化と条件分岐
        $result = "";
        
        if(!empty($this->params["form"]["shimei"])){
            extract($this->params["form"]);
            $result = "あなたの名簿情報は{$shimei}、{$jyusho}、{$denwa}です。<br />";
        }else{
            $result = "あなたの名簿情報を入力して下さい。";
        }
        
        $this->set("result", $result);
    }
    
}
?>