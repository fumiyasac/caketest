<?php
/**
 *
 * Showcasesコントローラークラス
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

class ShowcasesController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Showcases';
    public $uses = array('Showcase');
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform','DisplayImage');
    
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'title',
            'kcpy',
            'image_main',
            'description_main',
            'image_sub1',
            'caption_sub1',
            'image_sub2',
            'caption_sub2',
            'image_sub3',
            'caption_sub3',
            'image_sub4',
            'caption_sub4',
            'api_id_gurunabi',
            'api_id_hotpepper',
            'api_id_rakuten',
            'api_id_jaran',
            'other_description',
            'other_description',
            'price',
            'published',
            'created',
            'modified',
            'flag',
            ),
        
        'limit' => 100,
        'order' => 'Showcase.id DESC',
    );
    
    //URL遷移先のページ
    private $uri_control_index = '/control/showcases/index';
    private $uri_control_add   = '/control/showcases/add';
    private $uri_control_edit  = '/control/showcases/edit';
    
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
            array('name' => '管理画面TOP','link' => false),
            array('name' => 'ショーケースの一覧','link' => false)
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->Showcase->find('count');
        $this->set('allAmount',$allAmount);
        
        //showcasesテーブルからデータを持ってくる
        $showcases = $this->paginate();
        $this->set('showcases', $showcases);
        
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
			$response = $this->Showcase->changeFlagStatus($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }

    //特集記事削除（管理画面）
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
			$response = $this->Showcase->deleteImageAndDataById($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }

    //特集閲覧（管理画面）
    public function control_view($id = null){
        
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            $this->data = $this->Showcase->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
                array('name' => 'ショーケース（'.$this->data['Showcase']['title'].'）','link' => false)
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
    
    //特集記事追加（管理画面）
    public function control_add(){
        
        try {
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
                array('name' => 'ショーケースの追加','link' => false)
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
                $this->Showcase->set($this->data);
                
                //バリデーションチェック
                if($this->Showcase->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Showcase->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
                    
                    //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
		                array('name' => 'ショーケース追加内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('control_add_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
                        array('name' => 'ショーケースの追加','link' => false)
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

    //特集記事追加完了（管理画面）
    public function control_add_complete(){
        
        try{
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
                array('name' => 'ショーケース追加完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //画像アップロード前の準備を行う
                $this->data = $this->Showcase->beforeUploadImageAdd($this->data);
                
                //取得データをDBへ保存する
                if($this->Showcase->save($this->data['Showcase']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Showcase->getSaveImageResult($this->data, false);
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
                array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
                array('name' => 'ショーケースの編集','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->data = $this->Showcase->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //一時画像ファイルの削除
            $this->Showcase->deleteTmpImageById($this->data);
            
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
            if(!isset($id) && is_numeric($id) && $data['Showcase']['id']){
                 $this->redirect($this->uri_control_index);
            }
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Showcase->getAlreadyImageName($id);
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->Showcase->disableImageValidation();
                
                //変数に値をセット
                $this->Showcase->set($this->data);
                
                //バリデーションチェック
                if($this->Showcase->validates()){
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->Showcase->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult);
                                       
                    //変数をセット
                    $this->set('data', $this->data);

		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
		                array('name' => 'ショーケース編集内容の確認','link' => false),
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('control_edit_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
                        array('name' => 'ショーケースの編集','link' => false)
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
                array('name' => 'ショーケースの一覧','link' => $this->uri_control_index),
                array('name' => 'ショーケース編集完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Showcase->getAlreadyImageName($id);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                
                //画像アップロード前の準備を行う
                $this->data = $this->Showcase->beforeUploadImageEdit($this->data, $id, $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Showcase->save($this->data['Showcase']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Showcase->getSaveImageResult($this->data, false);
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
        
        $filename = 'ショーケース一覧'.date('Ymd');
        $headRow  = array(
            'ID',
            'ショーケースタイトル',
            'ショーケースキャッチコピー',
            'ショーケースメイン画像',
            'ショーケース本文',
            'サブ1画像画像',            
            'サブ1キャプション',
            'サブ2画像画像',
            'サブ2キャプション',
            'サブ3画像画像',
            'サブ3キャプション',
            'サブ4画像画像',
            'サブ4キャプション',
            'ぐるなび API ID',
            'ホットペッパー API ID',
            '楽天 API ID',
            'じゃらん API ID',
            '価格',
            '自由記入項目タイトル',
            '自由記入項目本文',
            '公開日',
            '公開フラグ',
        );
        $contentsRows = $this->Showcase->find('all');
		
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }
    
    //特集記事表示
    public function index(){
        
        //タイトルメッセージのセット
        $this->set('title_for_layout','ショーケース一覧');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => 'ショーケース一覧','link' => false),
        );
        $this->set('breadcrumb', $breadcrumb);
        
        $condition = array('Showcase.flag' => COMMON_PUBLISHED);
        if(isset($this->params['requested'])){
            $showcases = $this->paginate('Showcase', $condition);
            return $showcases;
        }else{
            //ページングのリミットを10にする
            $this->paginate['limit'] = 10;
        
            //showcasesテーブルからデータを持ってくる
            $showcases = $this->paginate('Showcase', $condition);
            $this->set('showcases', $showcases);
        }
        //ビューのレンダリング
        $this->render('index');
    }

    //特集記事閲覧
    public function view($id = null){
        
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_index);
            }
            
            //データを取得する
            $this->data = $this->Showcase->getDetailDataById($id);
            if($this->data === false){
                $this->redirect($this->uri_index);
            }
            
            //変数をセット
            $this->set('data', $this->data);
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','ショーケース（'.$this->data['Showcase']['title'].'）');
        
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'ショーケース一覧', 'link' => array('controller' => 'showcases', 'action' => 'index')),
                array('name' => 'ショーケース（'.$this->data['Showcase']['title'].'）','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb); 
            
        } catch (Exception $e) {
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
    }
    
}