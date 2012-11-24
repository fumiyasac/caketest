<?php
class C02sController extends AppController{
    public $name = "C02s";
    public $uses = null; //使用モデルの指定
    public $autoRender = false; //自動レンダリングの無効
    
    function index(){
      echo '<html><body>';
      echo 'Hello! CakePHP!';
      echo '<br /><a href="../C02s/nextPage">C02s nextPage<a>';
      echo '</body></html>';
    }
    
    function nextPage(){
      echo '<html><body>';
      echo 'Hello! CakePHP!';
      echo '<br /><a href="../C02s/index">C02s index<a>';
      echo '</body></html>';
    }
}
?>
