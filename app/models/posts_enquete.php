<?php
/**
 *
 * PostsEnqueteモデルクラス
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

class PostsEnquete extends AppModel{
    
    //モデル名
    public $name = 'PostsEnquete';
    
    //データを物理削除する
    public function deleteDataById($id){
        
        //削除処理
        $this->delete($id);

        //全ての件数の取得
        $allAmount = count($this->find('all'));
		
        //変更したステータスの取得
        $response = array('id' => $id, 'allAmount' => $allAmount);
		return $response;
    }

}