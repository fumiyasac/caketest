<?php
/**
 *
 * CatalogsCommentモデルクラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */

//モデルクラスのインポート
App::Import('Model','Catalog');

class CatalogsComment extends AppModel{
    
    //モデル名
    public $name = 'CatalogsComment';
    
    //インポートしたモデル
    private $Catalog;
    
    //コンストラクタ
    public function __construct(){
    	parent::__construct();
    	//インポートモデルを読み込む場合はインスタンスを作成
    	$this->Catalog = new Catalog();
	}
    
    //コンテンツ部分のデータ取得を行う
	public function getDetailDataById($id){
		
		$conditions = array(
			'CatalogsComment.id'   => $id,
			'CatalogsComment.flag' => COMMON_PUBLISHED
		);
		
		$detail = $this->find('first',
            array('conditions' => $conditions)
        );
        return $detail;
	}
    
    //管理画面用カタログデータモデルよりタイトルデータを取得する
    public function getControlCatalogTitleList(){
    	
    	//カタログデータ用の配列を作成
	    $catalogTitleList = array();
	    $catalogDatas     = $this->Catalog->find('all');
	    
	    if(!empty($catalogDatas)){
	        foreach($catalogDatas as $value){
	        	$catalog_id                    = $value['Catalog']['id'];
		        $catalogTitleList[$catalog_id] = $value['Catalog']['title'];
	        }
	    }
		return $catalogTitleList;
    }

    //カタログデータモデルよりタイトルデータを取得する
    public function getCatalogDataList(){
    	
    	$conditions = array('Catalog.flag' => COMMON_PUBLISHED);
    	
    	//カタログデータ用の配列を作成
	    $catalogTitleList = array();
	    $catalogDatas = $this->Catalog->find('all', 
	    	array('conditions' => $conditions)
	    );        
	    
	    if(!empty($catalogDatas)){
			foreach($catalogDatas as $value){
				$catalog_id                                     = $value['Catalog']['id'];
				$catalogTitleList[$catalog_id]['title']         = $value['Catalog']['title'];
				$catalogTitleList[$catalog_id]['template']      = $value['Catalog']['template'];
				$catalogTitleList[$catalog_id]['catalog_image'] = $value['Catalog']['catalog_image'];
			}
	    }
		return $catalogTitleList;
    }

    //公開フラグの設定を行う
    public function changeFlagStatus($id){
            
        //ステータスを変更する
        $this->id = $id;
        $flag = $this->field('flag') == COMMON_PUBLISHED ? ADMIN_ONLY : COMMON_PUBLISHED;
        $this->saveField('flag', $flag);
        
        //変更したステータスの取得
        $response = array('id' => $id, 'flagStatus' => Configure::read("FLAG_CONF.flag.{$flag}"));
		return $response;
    }

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