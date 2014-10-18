<?php
/**
 *
 * MembersTopicsコントローラークラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */
 
class MembersTopicsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'MembersTopics';
    public $uses = array('MembersTopic');
    public $layout = 'common_format_blog';
    public $components = array('Session','Email','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform','DisplayImage');
    
    //デフォルト設定
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'title',
            'kcpy',
            'description',
            'member_topic_image',
            'published',
            'flag',
            'created',
            'modified',
            ),
        'limit' => 10,
        'order' => 'MembersTopic.id DESC',
    );
    
    //URL遷移先のページ
    private $uri_control_index = '/control/members_topics/index';
    private $uri_control_add   = '/control/members_topics/add';
    private $uri_control_edit  = '/control/members_topics/edit';
	
    //認証関連の設定
    public function beforeFilter() {
        parent::beforeFilter();       
    }

    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
    
    //特集記事TOP（管理画面）
    public function control_index(){
        
        //パンくずリストの設定
        $breadcrumb = array(
            array('name' => '管理画面TOP', 'link' => false),
            array('name' => '会員専用情報の一覧', 'link' => false)
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->MembersTopic->find('count');
        $this->set('allAmount',$allAmount);
        
        //memberstopicsテーブルからデータを持ってくる
        $memberstopics = $this->paginate();
        $this->set('memberstopics', $memberstopics);
        
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
			$response = $this->MembersTopic->changeFlagStatus($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }
    
    //会員専用記事削除（管理画面）
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
			$response = $this->MembersTopic->deleteImageAndDataById($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }
    
    //会員専用記事閲覧（管理画面）
    public function control_view($id = null){
    	
        try {
        
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }

            //データを取得する
            $this->data = $this->MembersTopic->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP', 'link' => false),
                array('name' => '会員専用情報の一覧', 'link' => $this->uri_control_index),
                array('name' => $this->data['MembersTopic']['title'],'link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //表示データを取得
            $this->set('data', $this->data);
            
        } catch (Exception $e) {
        
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_index);
        }
    }    
    
    //会員専用記事追加（管理画面）
    public function control_add(){
    	
        try {
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP', 'link' => false),
                array('name' => '会員専用情報の一覧', 'link' => $this->uri_control_index),
                array('name' => '会員専用情報の追加', 'link' => false)
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
    
    //特集記事追加確認（管理画面）
    public function control_add_confirm(){
        
        try{
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->MembersTopic->set($this->data);
                
                //バリデーションチェック
                if($this->MembersTopic->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->MembersTopic->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
                    
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => '会員専用情報の一覧','link' => $this->uri_control_index),
		                array('name' => '会員専用情報追加内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('control_add_confirm');
                    
                }else{
                    
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP', 'link' => false),
		                array('name' => '会員専用情報の一覧', 'link' => $this->uri_control_index),
		                array('name' => '会員専用情報の追加', 'link' => false)
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
    
    //会員専用記事追加完了（管理画面）
    public function control_add_complete(){
        
        try{
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => '会員専用情報の一覧','link' => $this->uri_control_index),
                array('name' => '会員専用情報追加完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                
                //画像アップロード前の準備を行う
                $this->data = $this->MembersTopic->beforeUploadImageAdd($this->data);
                              
                //取得データをDBへ保存する
                if($this->MembersTopic->save($this->data['MembersTopic']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->MembersTopic->getSaveImageResult($this->data, false);
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
        
    //特集記事編集（管理画面）
    public function control_edit($id = null){
    	
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => '会員専用情報の一覧','link' => $this->uri_control_index),
                array('name' => '会員専用情報の編集','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->data = $this->MembersTopic->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //一時画像ファイルの削除
            $this->MembersTopic->deleteTmpImageById($this->data);
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_edit.DS.$id);
        }    
    }

    //特集記事編集確認（管理画面）
    public function control_edit_confirm($id = null){
        
        try{

            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id) && $data['MembersTopic']['id']){
                 $this->redirect($this->uri_control_index);
            }
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->MembersTopic->getAlreadyImageName($id);
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->MembersTopic->disableImageValidation();
                
                //変数に値をセット
                $this->MembersTopic->set($this->data);
                
                //バリデーションチェック
                if($this->MembersTopic->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->MembersTopic->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
					
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => '会員専用情報の一覧','link' => $this->uri_control_index),
		                array('name' => '会員専用情報編集内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
					
                    //ビューのレンダリング
                    $this->render('control_edit_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => '会員専用情報の一覧','link' => $this->uri_control_index),
                        array('name' => '会員専用情報の編集','link' => false),
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

    //特集記事編集確認（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => '会員専用情報の一覧','link' => $this->uri_control_index),
                array('name' => '会員専用情報編集完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->MembersTopic->getAlreadyImageName($id);

            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                
                //画像アップロード前の準備を行う
                $this->data = $this->MembersTopic->beforeUploadImageEdit($this->data, $id, $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->MembersTopic->save($this->data['MembersTopic']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->MembersTopic->getSaveImageResult($this->data, false);
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
        
        $filename     = '会員専用情報'.date('Ymd'); //ファイル名
        $headRow      = array('ID','タイトル','キャッチコピー','本文','画像','公開日','公開フラグ'); //表の1行目の作成
        $contentsRows = $this->MembersTopic->find('all'); //データを取得
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }    
    
}