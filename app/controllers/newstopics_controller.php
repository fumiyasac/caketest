<?php
/**
 *
 * Newstopicsコントローラークラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */
 
class NewstopicsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Newstopics';
    public $uses = array('Newstopic');
    public $layout = 'common_format_blog';
    public $components = array('Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform','DisplayImage');
    
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'title',
            'description',
            'newstopic_image',
            'link_url',
            'blank_flag',
            'flag',
            'published',
            'created',
            'modified',
            ),
        
        'limit' => 100,
        'order' => 'Newstopic.id DESC',
    );
    
    //URL遷移先のページ
    private $uri_index         = '/newstopics/index';
    private $uri_control_index = '/control/newstopics/index';
    private $uri_control_add   = '/control/newstopics/add';
    private $uri_control_edit  = '/control/newstopics/edit';

    //認証関連の設定
    public function beforeFilter() {
       parent::beforeFilter();
    }
    
    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
    
    //ニュース&トピックTOP（管理画面）
    public function control_index(){
        
        //パンくずリストの設定
        $breadcrumb = array(
            array('name' => '管理画面TOP','link' => false),
            array('name' => 'ニュース&トピックの一覧','link' => false),
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->Newstopic->find('count');
        $this->set('allAmount',$allAmount);
        
        //newstopicsテーブルからデータを持ってくる
        $newstopics = $this->paginate();
        $this->set('newstopics', $newstopics);
        
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
			$response = $this->Newstopic->changeFlagStatus($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }

    //ニュース&トピック削除（管理画面）
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
			$response = $this->Newstopic->deleteImageAndDataById($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }

    //ニュース&トピック閲覧（管理画面）
    public function control_view($id = null){
    	
        try {
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            //データを取得する
           $this->data = $this->Newstopic->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'ニュース&トピックの一覧','link' => $this->uri_control_index),
                array('name' => $this->data['Newstopic']['title'],'link' => false)
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
    
    //ニュース&トピック記事追加（管理画面）
    public function control_add(){ 
        try {
        	
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'ニュース&トピックの一覧','link' => $this->uri_control_index),
                array('name' => 'ニュース&トピックの追加','link' => false)
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
    
    //ニュース&トピック記事追加確認（管理画面）
    public function control_add_confirm(){
        
        try{
			
        	if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Newstopic->set($this->data);
                
                //バリデーションチェック
                if($this->Newstopic->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Newstopic->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
                    
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => 'ニュース＆トピックの一覧','link' => $this->uri_control_index),
		                array('name' => 'ニュース＆トピック追加内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('control_add_confirm');
                    
                }else{
                    
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP', 'link' => false),
		                array('name' => 'ニュース＆トピックの一覧', 'link' => $this->uri_control_index),
		                array('name' => 'ニュース＆トピックの追加', 'link' => false)
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

    //ニュース&トピック記事追加完了（管理画面）
    public function control_add_complete(){
        
        try{
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'ニュース&トピックの一覧','link' => $this->uri_control_index),
                array('name' => 'ニュース&トピック追加完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //画像アップロード前の準備を行う
                $this->data = $this->Newstopic->beforeUploadImageAdd($this->data);
                
                //取得データをDBへ保存する
                if($this->Newstopic->save($this->data['Newstopic']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Newstopic->getSaveImageResult($this->data, false);
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

    //ニュース&トピック記事編集（管理画面）
    public function control_edit($id = null){
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'ニュース&トピックの一覧','link' => $this->uri_control_index),
                array('name' => 'ニュース&トピックの編集','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->data = $this->Newstopic->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //一時画像ファイルの削除
            $this->Newstopic->deleteTmpImageById($this->data);
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_edit.DS.$id);
        }    
    }

    //ニュース&トピック記事編集確認（管理画面）
    public function control_edit_confirm($id = null){
        
        try{

            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id) && $data['Newstopic']['id']){
                 $this->redirect($this->uri_control_index);
            }
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Newstopic->getAlreadyImageName($id);
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->Newstopic->disableImageValidation();
                                
                //変数に値をセット
                $this->Newstopic->set($this->data);
                
                //バリデーションチェック
                if($this->Newstopic->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Newstopic->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
					
		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => 'ニュース&トピックの一覧','link' => $this->uri_control_index),
		                array('name' => 'ニュース&トピック編集内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
					
                    //ビューのレンダリング
                    $this->render('control_edit_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => 'ニュース&トピックの一覧','link' => $this->uri_control_index),
                        array('name' => 'ニュース&トピックの編集','link' => false),
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

    //ニュース&トピック記事編集確認（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'ニュース&トピックの一覧','link' => $this->uri_control_index),
                array('name' => 'ニュース&トピック編集完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Newstopic->getAlreadyImageName($id);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                
                //画像アップロード前の準備を行う
                $this->data = $this->Newstopic->beforeUploadImageEdit($this->data, $id, $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Newstopic->save($this->data['Newstopic']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Newstopic->getSaveImageResult($this->data, false);
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

        $filename     = 'ニュース&トピック'.date('Ymd');
        $headRow      = array('ID','タイトル','画像','本文','リンクURL','リンクの種類','公開日','公開フラグ');
        $contentsRows = $this->Newstopic->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }
    
    //ニュース&トピック表示
    public function index(){
        
        //タイトルメッセージのセット
        $this->set('title_for_layout','ニュース&トピック一覧');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => 'ニュース&トピック一覧','link' => false),
        );
        $this->set('breadcrumb', $breadcrumb);
        
        $condition = array('Newstopic.flag' => COMMON_PUBLISHED);
        if(isset($this->params['requested'])){
            $newstopics = $this->paginate('Newstopic', $condition);
            return $newstopics;
        }else{
            //ページングのリミットを10にする
            $this->paginate['limit'] = 10;
        
            //newstopicsテーブルからデータを持ってくる
            $newstopics = $this->paginate('Newstopic', $condition);
            $this->set('newstopics', $newstopics);
        }
        //ビューのレンダリング
        $this->render('index');
    }

    //ニュース&トピック閲覧
    public function view($id = null){
        
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_index);
            }
            
            //データを取得する
            $this->data = $this->Newstopic->getDetailDataById($id);
            if($this->data === false){
                $this->redirect($this->uri_index);
            }
            
            //変数をセット
            $this->set('data', $this->data);
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','ニュース&トピック（'.$this->data['Newstopic']['title'].'）');
        
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'ニュース&トピック一覧', 'link' => $this->uri_index),
                array('name' => 'ニュース&トピック（'.$this->data['Newstopic']['title'].'）','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb); 
            
        } catch (Exception $e) {
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }      
    }
    
}