<?php
class HelloController extends AppController{

	public $name = 'Hello';
	public $uses = null;
	public $layout = "hello";
	
	function index(){
		//タイトルの出力
		$this->set("title_for_layout","CakePHPテスト");
	}

}
?>