<?php
/**
 *
 * Bannersコントローラークラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */

class BannersController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Banners';
    public $uses = array('Banner');
    public $layout = 'common_format_blog';
    public $components = array('Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform','DisplayImage');
    
    //ページャーの設定
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'title',
            'description',
            'banner_image',
            'link_url',
            'published',
            'blank_flag',
            'flag',
            'created',
            'modified',
            ),
        
        'limit' => 100,
        'order' => 'Banner.id DESC',
    );
    
	//URL遷移先のページ
    private $uri_control_index = '/control/banners/index';
    private $uri_control_add   = '/control/banners/add';
    private $uri_control_edit  = '/control/banners/edit';
	    
    //認証関連の設定
    public function beforeFilter() {
       parent::beforeFilter();
    }
    
    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
    
    //登録バナー管理TOP（管理画面）
    public function control_index(){
        
        //パンくずリストの設定
        $breadcrumb = array(
            array('name' => '管理画面TOP','link' => false),
            array('name' => '登録バナーの一覧','link' => false)
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->Banner->find('count');
        $this->set('allAmount',$allAmount);
        
        //bannersテーブルからデータを持ってくる
        $banners = $this->paginate();
        $this->set('banners', $banners);
        
        //表示数を取得
        $limitAmount = $this->paginate['limit'];
        $this->set('limitAmount',$limitAmount);
        
        //ビューのレンダリング
        $this->render('control_index'); 
        
    }
    
    //公開ステータス変更（管理画面）
    public function control_change($id = null){
        
        //URLの直アクセスの禁止  
        if($this->RequestHandler->isGet()){
            $this->redirect($this->uri_control_index);
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            //レイアウトを使用しない
            $this->autoRender = false;
            $this->autoLayout = false;
            
			//レスポンスを取得する
			$response = $this->Banner->changeFlagStatus($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }

    //登録バナー削除（管理画面）
    public function control_delete($id = null){
        
        //URLの直アクセスの禁止  
        if($this->RequestHandler->isGet()){
            $this->redirect($this->uri_control_index);
        }
        
        //Ajaxリクエスト時のみ削除を行う
        if($this->RequestHandler->isAjax()){
            
            //レイアウトを使用しない
            $this->autoRender = false;
            $this->autoLayout = false;

			//レスポンスを出力する
			$response = $this->Banner->deleteImageAndDataById($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }
	
    //登録バナー記事追加（管理画面）
    public function control_add(){
    	
        try {
        	
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => '登録バナーの一覧','link' => $this->uri_control_index),
                array('name' => '登録バナーの追加','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_add);
        }
    }
    
    //登録バナー追加確認（管理画面）
    public function control_add_confirm(){
        
        try{
			
        	if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Banner->set($this->data);
                
                //バリデーションチェック
                if($this->Banner->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Banner->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
                    
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => '登録バナーの一覧','link' => $this->uri_control_index),
		                array('name' => '登録バナー追加内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('control_add_confirm');
                    
                }else{
                    
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP', 'link' => false),
		                array('name' => '登録バナーの一覧', 'link' => $this->uri_control_index),
		                array('name' => '登録バナーの追加', 'link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                    $this->set('error_announce', ERROR_ANNOUNCE_VALIDATE);
                    
                    //ビューのレンダリング
                    $this->render('control_add');                    
                }
                
            }else{
                
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__(ERROR_ANNOUNCE_ILLIGAL_ACCESS, true));
            }
                
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_add);
        }        
    }

    //登録バナー記事追加完了（管理画面）
    public function control_add_complete(){
        
        try{
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => '登録バナーの一覧','link' => $this->uri_control_index),
                array('name' => '登録バナー追加完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //画像アップロード前の準備を行う
                $this->data = $this->Banner->beforeUploadImageAdd($this->data);
                
                //取得データをDBへ保存する
                if($this->Banner->save($this->data['Banner']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Banner->getSaveImageResult($this->data, false);
                    $this->set('saveImageResult', $saveImageResult);
                    
                    //ビューのレンダリング
                    $this->render('control_add_complete');
				}
                
            }else{
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__(ERROR_ANNOUNCE_ILLIGAL_ACCESS, true));                                    
            }
            
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_add);
        }
    }

    //登録バナー記事編集（管理画面）
    public function control_edit($id = null){
    	
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => '登録バナーの一覧','link' => $this->uri_control_index),
                array('name' => '登録バナーの編集','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->data = $this->Banner->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //一時画像ファイルの削除
            $this->Banner->deleteTmpImageById($this->data);
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_edit.DS.$id);
        }
    }

    //登録バナー編集確認（管理画面）
    public function control_edit_confirm($id = null){
        
        try{

            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id) && $data['Banner']['id']){
                 $this->redirect($this->uri_control_index);
            }
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Banner->getAlreadyImageName($id);
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->Banner->disableImageValidationForEdit($this->data);
                        
                //変数に値をセット
                $this->Banner->set($this->data);
                
                //バリデーションチェック
                if($this->Banner->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Banner->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
					
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => '登録バナーの一覧','link' => $this->uri_control_index),
		                array('name' => '登録バナー編集内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
					
                    //ビューのレンダリング
                    $this->render('control_edit_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => '登録バナーの一覧','link' => $this->uri_control_index),
                        array('name' => '登録バナーの編集','link' => false),
                    );
                    $this->set('breadcrumb', $breadcrumb);
                    $this->set('error_announce', ERROR_ANNOUNCE_VALIDATE);
                    
                    //ビューのレンダリング
                    $this->render('control_edit');
                }
                
            }else{
                
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__(ERROR_ANNOUNCE_ILLIGAL_ACCESS, true));
            }
            
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_edit.DS.$id);
        }        
    }

    //登録バナー記事編集確認（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => '登録バナーの一覧','link' => $this->uri_control_index),
                array('name' => '登録バナー編集完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Banner->getAlreadyImageName($id);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                
                //画像アップロード前の準備を行う
                $this->data = $this->Banner->beforeUploadImageEdit($this->data, $id, $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Banner->save($this->data['Banner']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Banner->getSaveImageResult($this->data, false);
                    $this->set('saveImageResult', $saveImageResult);
                    
                    //ビューのレンダリング
                    $this->render('control_edit_complete');
               }
                
            }else{
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__(ERROR_ANNOUNCE_ILLIGAL_ACCESS, true));                                    
            }
            
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_edit.DS.$id);
        }
    }
    
    //CSVファイルのダウンロード（管理画面）
    public function control_csvdownload(){
        
        //レイアウトを使用しない
        Configure::write('debug', 0);
        $this->layout = false;
        
        $filename     = '登録バナーの一覧'.date('Ymd');
        $headRow      = array('ID','タイトル','本文','バナー画像','リンクURL','リンクの種類','公開日','公開フラグ');        
        $contentsRows = $this->Banner->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }

    //登録バナー（エレメント出力のみ）
    public function index(){
    	$condition = array('Banner.flag' => COMMON_PUBLISHED);
        $banners = $this->paginate('Banner', $condition);
        if(isset($this->params['requested'])){
            return $banners;
        }
    }
    
}