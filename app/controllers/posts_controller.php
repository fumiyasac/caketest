<?php
class PostsController extends AppController{

	var $name = 'Posts';
        var $layout = 'hello';
        
	//indexページのActionを設定する
	function index(){
	
		//postsテーブルからすべてのレコードを持ってくる
		$posts = $this->Post->find("all"); 
		
                //$this->layout = 'hello';
		
		//ビューに変数を渡す
		$this->set('posts',$posts);
		
	}
	
	//viewページのActionを設定する
	function view($id = null){
	
		//操作したい対象のIDを決める
		$this->Post->id = $id;
		
		//postsテーブルから1件のレコードを持ってくる
		$post = $this->Post->read();
		
		//ビューに変数を渡す
		$this->set('post',$post);
		
	}
	
	//addページのActionを設定する
	function add(){
		
		//$this->dataでフォームでPOSTされたデータを取得する
		if(!empty($this->data)){
			
			//$this->dataの内容をDBへ書き込む
			if($this->Post->save($this->data)){
				
				$this->flash('データが追加されました。','/posts');
				
			}
			
		}
			
	}
	
    //deleteページのActionを設定する
    function delete($id) {
		//deleteメソッドで記事を削除
    	$this->Post->delete($id);
    	$this->flash('記事のID: '.$id.'を削除しました。', '/posts');
    }

    //editページのActionを設定する
    function edit($id = null) {
    	$this->Post->id = $id;
    	if (empty($this->data)) {
    		$this->data = $this->Post->read();
    	} else {
    	
    		if ($this->Post->save($this->data['Post'])) {
    			$this->flash('記事の編集が完了しました。','/posts');
    		}
    	}
    }


}
?>