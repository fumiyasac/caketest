<?php
/**
 *
 * PostsQuestionモデルクラス
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

class PostsQuestion extends AppModel{
    
    //モデル名
    public $name = 'PostsQuestion';
    
    //$post_idに合致するデータのステータスを更新する
    public function updateStatusByPostId($id, $flag){
		
		//Postに紐づくモデルのステータスも変更する
	    $conditions = array('post_id' => $id);
		$fields     = array('flag'    => $flag);
		
		$this->updateAll($fields, $conditions);
    }
    
    //$post_idに合致するデータを物理削除する
    public function deleteDataByPostId($id){
        
		//Postに紐づくデータも削除する
	    $conditions = array('PostsQuestion.post_id' => $id);
		
        $this->deleteAll($conditions, false);
    }
    
}