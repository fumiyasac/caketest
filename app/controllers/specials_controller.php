<?php
/**
 *
 * Specialsコントローラークラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */

class SpecialsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Specials';
    public $uses = array('Special');
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
            'title_sub1',
            'image_sub1',
            'description_sub1',
            'title_sub2',
            'image_sub2',
            'description_sub2',
            'title_sub3',
            'image_sub3',
            'description_sub3',
            'other_description',
            'published',
            'created',
            'modified',
            'flag',
            ),
        
        'limit' => 100,
        'order' => 'Special.id DESC',
    );
    
    //URL遷移先のページ
    private $uri_index         = '/specials/index';
    private $uri_control_index = '/control/specials/index';
    private $uri_control_add   = '/control/specials/add';
    private $uri_control_edit  = '/control/specials/edit';
    
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
            array('name' => '特集記事の一覧','link' => false)
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->Special->find('count');
        $this->set('allAmount',$allAmount);
        
        //specialsテーブルからデータを持ってくる
        $specials = $this->paginate();
        $this->set('specials', $specials);
        
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
			$response = $this->Special->changeFlagStatus($id);
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
			$response = $this->Special->deleteImageAndDataById($id);
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
            
            $this->data = $this->Special->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => '特集記事の一覧','link' => $this->uri_control_index),
                array('name' => '特集記事（'.$this->data['Special']['title'].'）','link' => false)
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
                array('name' => '特集記事の一覧','link' => $this->uri_control_index),
                array('name' => '特集記事の追加','link' => false)
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
                $this->Special->set($this->data);
                
                //バリデーションチェック
                if($this->Special->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Special->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);

		            //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => '特集記事の一覧','link' => $this->uri_control_index),
		                array('name' => '特集記事追加内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);

                    //ビューのレンダリング
                    $this->render('control_add_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => '特集記事の一覧','link' => $this->uri_control_index),
                        array('name' => '特集記事の追加','link' => false)
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
                array('name' => '特集記事の一覧','link' => $this->uri_control_index),
                array('name' => '特集記事追加完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){

                //画像アップロード前の準備を行う
                $this->data = $this->Special->beforeUploadImageAdd($this->data);
                
                //取得データをDBへ保存する
                if($this->Special->save($this->data['Special']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Special->getSaveImageResult($this->data, false);
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
                array('name' => '特集記事の一覧','link' => $this->uri_control_index),
                array('name' => '特集記事の編集','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->data = $this->Special->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //一時画像ファイルの削除
            $this->Special->deleteTmpImageById($this->data);
            
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
            if(!isset($id) && is_numeric($id) && $data['Special']['id']){
                 $this->redirect($this->uri_control_index);
            }
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Special->getAlreadyImageName($id);
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->Special->disableImageValidation();
                
                //変数に値をセット
                $this->Special->set($this->data);
                
                //バリデーションチェック
                if($this->Special->validates()){
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->Special->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult);
                   
                    //変数をセット
                    $this->set('data', $this->data);
                    
                    //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => '特集記事の一覧','link' => $this->uri_control_index),
		                array('name' => '特集記事編集内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                   
                    //ビューのレンダリング
                    $this->render('control_edit_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => '特集記事の一覧','link' => $this->uri_control_index),
                        array('name' => '特集記事の編集','link' => false)
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
                array('name' => '特集記事の一覧','link' => $this->uri_control_index),
                array('name' => '特集記事編集完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Special->getAlreadyImageName($id);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
            	
                //画像アップロード前の準備を行う
                $this->data = $this->Special->beforeUploadImageEdit($this->data, $id, $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Special->save($this->data['Special']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Special->getSaveImageResult($this->data, false);
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
        
        $filename = '特集記事一覧'.date('Ymd');
        $headRow  = array(
            'ID',
            '特集記事タイトル',
            '特集記事キャッチコピー',
            '特集記事メイン画像',
            '特集記事本文',
            '見出し(サブ1)',
            '画像(サブ1)',            
            '本文(サブ1)',
            '見出し(サブ2）',
            '画像(サブ2)',            
            '本文(サブ2)',
            '見出し(サブ3)',
            '画像(サブ3)',            
            '本文(サブ3)',
            '本文(その他)',
            '公開日',
            '公開フラグ',
        );
        $contentsRows = $this->Special->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }
    
    //特集記事表示
    public function index(){
        
        //タイトルメッセージのセット
        $this->set('title_for_layout','特集記事一覧');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => '特集記事一覧','link' => false),
        );
        $this->set('breadcrumb', $breadcrumb);
        
        $condition = array('Special.flag' => COMMON_PUBLISHED);
        if(isset($this->params['requested'])){
            $specials = $this->paginate('Special', $condition);
            return $specials;
        }else{
            //ページングのリミットを10にする
            $this->paginate['limit'] = 10;
        
            //specialsテーブルからデータを持ってくる
            $specials = $this->paginate('Special', $condition);
            $this->set('specials', $specials);
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
            $this->data = $this->Special->getDetailDataById($id);
            if($this->data === false){
                $this->redirect($this->uri_index);
            }
            
            //変数をセット
            $this->set('data', $this->data);
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','特集記事（'.$this->data['Special']['title'].'）');
        
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '特集記事一覧', 'link' => $this->uri_index),
                array('name' => '特集記事（'.$this->data['Special']['title'].'）','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb); 
            
        } catch (Exception $e) {
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
    }
    
}