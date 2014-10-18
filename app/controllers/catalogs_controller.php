<?php
/**
 *
 * Catalogsコントローラークラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */
 
class CatalogsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Catalogs';
    public $uses = array('Catalog');
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler','GourmetMap');
    public $helpers = array('Formhidden','Csv','Html','Dateform','GourmetMap','DisplayImage');
    
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'title',
            'kcpy',
            'description',
            'template',
            'catalog_image',
            'published',
            'created',
            'modified',
            'flag',
            ),
        
        'limit' => 100,
        'order' => 'Catalog.id DESC',
    );
    
    //URL遷移先のページ
    private $uri_index         = '/catalogs/index';
    private $uri_control_index = '/control/catalogs/index';
    private $uri_control_add   = '/control/catalogs/add';
    private $uri_control_edit  = '/control/catalogs/edit';
    
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
            array('name' => 'カタログコンテンツの一覧','link' => false)
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->Catalog->find('count');
        $this->set('allAmount',$allAmount);
        
        //catalogsテーブルからデータを持ってくる
        $catalogs = $this->paginate();
        $this->set('catalogs', $catalogs);
        
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
			$response = $this->Catalog->changeFlagStatus($id);
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
			$response = $this->Catalog->deleteImageAndDataById($id);
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
            
            //データを取得する
            $this->data = $this->Catalog->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
                        
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => $this->data['Catalog']['title'],'link' => false)
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
                array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => 'カタログコンテンツの追加','link' => false)
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
                $this->Catalog->set($this->data);
                
                //バリデーションチェック
                if($this->Catalog->validates()){

                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Catalog->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
                    
                    //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
		                array('name' => 'カタログコンテンツ追加内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('control_add_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
                        array('name' => 'カタログコンテンツの追加','link' => false)
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
                array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => 'カタログコンテンツ追加完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                
                //画像アップロード前の準備を行う
                $this->data = $this->Catalog->beforeUploadImageAdd($this->data);
                
                //取得データをDBへ保存する
                if($this->Catalog->save($this->data['Catalog']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Catalog->getSaveImageResult($this->data, false);
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
                array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => 'カタログコンテンツの編集','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->data = $this->Catalog->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //一時画像ファイルの削除
            $this->Catalog->deleteTmpImageById($this->data);
            
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
            if(!isset($id) && is_numeric($id) && $data['Catalog']['id']){
                 $this->redirect($this->uri_control_index);
            }
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Catalog->getAlreadyImageName($id);
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->Catalog->disableImageValidation();
                
                //変数に値をセット
                $this->Catalog->set($this->data);
                
                //バリデーションチェック
                if($this->Catalog->validates()){
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->Catalog->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult);
                                       
                    //変数をセット
                    $this->set('data', $this->data);
					
					//パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
		                array('name' => 'カタログコンテンツ編集内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
					
                    //ビューのレンダリング
                    $this->render('control_edit_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
                        array('name' => 'カタログコンテンツの編集','link' => false)
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
                array('name' => 'カタログコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => 'カタログコンテンツ編集完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Catalog->getAlreadyImageName($id);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                
                //画像アップロード前の準備を行う
                $this->data = $this->Catalog->beforeUploadImageEdit($this->data, $id, $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Catalog->save($this->data['Catalog']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Catalog->getSaveImageResult($this->data, false);
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
        
        $filename     = 'カタログコンテンツ'.date('Ymd');
        $headRow      = array('ID','タイトル','キャッチコピー','本文','コンテンツURL','画像','公開日','公開フラグ');
        $contentsRows = $this->Catalog->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }
    
    //カタログコンテンツ表示
    public function index(){
        
        //タイトルメッセージのセット
        $this->set('title_for_layout','大塚Catalogs一覧');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => '大塚Catalogs一覧','link' => false),
        );
        $this->set('breadcrumb', $breadcrumb);
        
        $condition = array('Catalog.flag' => COMMON_PUBLISHED);
        if(isset($this->params['requested'])){
            $catalogs = $this->paginate('Catalog', $condition);
            return $catalogs;
        }else{
            //ページングのリミットを10にする
            $this->paginate['limit'] = 10;
        
            //catalogsテーブルからデータを持ってくる
            $catalogs = $this->paginate('Catalog', $condition);
            $this->set('catalogs', $catalogs);
        }
        //ビューのレンダリング
        $this->render('index');
    }

    //カタログコンテンツ閲覧
    public function view($id = null){
        
        try {
        	
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_index);
            }
            
            //データを取得する
            $this->data = $this->Catalog->getDetailDataById($id);
            if($this->data === false){
                $this->redirect($this->uri_index);
            }
            
            //変数をセット
            $this->set('data', $this->data);
            
            //タイトルメッセージのセット
            $this->set('title_for_layout', $this->data['Catalog']['title'].'のご紹介');
        
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '大塚Catalogs', 'link' => $this->uri_index),
                array('name' => $this->data['Catalog']['title'].'のご紹介','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb); 
            
        } catch (Exception $e) {
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
        
    }
    
    /**
     * カタログコンテンツ用アクション
     * （注意1）コントローラーの肥大化を防ぐため、アクション名のコンポーネントに処理をできるだけまとめる
     * （注意2）コンテンツ独自のビュー処理はヘルパーに出来るだけまとめる
     */
    
    //第1回：大塚グルメマップ
    public function gourmet_map(){
        
        //データを取得する
        $catalog_id = 1;
        
        $this->data = $this->Catalog->getDetailDataById($catalog_id);
        if($this->data === false){
            $this->redirect($this->uri_index);
        }

        //タイトルメッセージのセット
        $this->set('title_for_layout', $this->data['Catalog']['title']);
        
        //カタログ用のフォーマットへ変更する
        $this->layout = 'common_format_catalog';
        
        //パンくずリストの設定 
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => '大塚Catalogs', 'link' => $this->uri_index),
            array('name' => $this->data['Catalog']['title'], 'link' => false),
        );
        $this->set('breadcrumb', $breadcrumb); 
        
        //ページング変数がなければデフォルト1に
        $page = (!empty($this->params['url']['page'])) ? $this->params['url']['page'] : 1;
        
        //API経由のデータを取得し加工する(ぐるなびAPI>Hotpepperだったのでぐるなびを基準に)
        $shop_data     = $this->GourmetMap->mergeDataFromAPI($page, $this->params['url']);
        $hit_max_count = $this->GourmetMap->maxCountFromAPI($this->params['url']);
        $category_list = $this->GourmetMap->getDataFromGnaviCategoryLargeAPI();
        $this->set('shop_data', $shop_data);
        $this->set('page', $page);
        $this->set('hit_max_count', $hit_max_count);
        $this->set('category_list', $category_list);
        
        //検索用の表示パラメータの設定
        $keywords   = (!empty($this->params['url']['keywords'])) ? $this->params['url']['keywords'] : null;
        $category_l = (!empty($this->params['url']['category_l'])) ? $this->params['url']['category_l'] : null;
        $range      = (!empty($this->params['url']['range'])) ? $this->params['url']['range'] : null;
        $this->set( compact('keywords', 'category_l', 'range') );
        
        //コメント投稿用にIDを渡す
        $this->set('catalog_id', $catalog_id);
        
    }
    
}