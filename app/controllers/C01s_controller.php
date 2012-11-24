<?php
class C01sController extends AppController{
    public $name = "C01s";
    public $uses = null; //使用モデルの指定
    public $autoRender = false; //自動レンダリングの無効
    
    function index(){
      echo '<html><body>';
      echo 'Hello! CakePHP!';
      echo '<br /><a href="../C02s/">C01s';
      echo '</body></html>';
    }
    
}
?>
