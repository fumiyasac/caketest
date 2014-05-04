<?php
class CatalogsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Catalogs';
    public $uses = array('Catalog'/*,'CatalogComment'*/);
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler','GourmetMap');
    public $helpers = array('Formhidden','Csv','Html','Dateform','GourmetMap');
    
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
    
    //画像格納カラム名の配列
    private $image_array = array("catalog_image");
    
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
            array(
                'name' => '管理画面TOP',
                'link' => false
            ),
            array(
                'name' => 'カタログコンテンツの一覧',
                'link' => false
            ),
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
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            $this->Catalog->id = $id;
            
            //ステータスを変更する
            if($this->Catalog->field('flag') == 2){
                $flag_id = 1;
            } else if($this->Catalog->field('flag') == 1) {
                $flag_id = 2;
            }
            
            if($this->Catalog->saveField('flag', $flag_id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                //変更したステータスの取得
                $response = array('id' => $id, 'flagStatus' => Configure::read("FLAG_CONF.flag.{$flag_id}"));                
                $this->header('Content-type: application/json');
                //debugKitのAjax対策
                Configure::write('debug', 0);
                echo json_encode($response);
                exit();
            }
        }
        $this->redirect(array('action' => 'control_index'));
    }

    //特集閲覧（管理画面）
    public function control_view($id = null){
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/catalogs');
            }
            
            //データを取得する
            $this->Catalog->id = $id;
            $this->data = $this->Catalog->read();
            if($this->data === false){
                $this->redirect('/control/catalogs');
            }else{
                //変数をセット
                $this->set('data', $this->data);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'カタログコンテンツの一覧',
                    'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                ),
                array(
                    'name' => $this->data['Catalog']['title'],
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/catalogs/');
        }
    }    
    
    //特集記事追加（管理画面）
    public function control_add(){
        
        try {
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'カタログコンテンツの一覧',
                    'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                ),
                array(
                    'name' => 'カタログコンテンツの追加',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/catalogs/add');
        }
    }
    
    //特集記事追加確認（管理画面）
    public function control_add_confirm(){
        
        try{
           
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'カタログコンテンツの一覧',
                    'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                ),
                array(
                    'name' => 'カタログコンテンツ追加内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Catalog->set($this->data);
                
                //バリデーションチェック
                if($this->Catalog->validates()){
                    
                    //次の番号のIDを出力する
                    $catalog_picture_id = $this->Catalog->getNextAutoIncrement();
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Catalog", 
                        $this->image_array,
                        $catalog_picture_id,
                        5
                    );
                    
                    //画像処理結果を出力する
                    $this->set('saveTmpImageResult', 
                        $saveTmpImageResult
                    );
                   
                    //変数をセット
                    $this->set('data', $this->data);
                                        
                    //ビューのレンダリング
                    $this->render('control_add_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array(
                            'name' => '管理画面TOP',
                            'link' => false
                        ),
                        array(
                            'name' => 'カタログコンテンツの一覧',
                            'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'カタログコンテンツの追加',
                            'link' => false
                        ),
                    );
                    $this->set('breadcrumb', $breadcrumb);
                    $this->set('error_announce','入力内容に誤りがあります。もう一度入力内容を確認して下さい');
                    
                    //ビューのレンダリング
                    $this->render('control_add');
                    
                }
                
            }else{
                
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__('不正アクセスが行われた可能性があります', true));
            }
            
            
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/catalogs/add');
            
        }        
        
    }

    //特集記事追加完了（管理画面）
    public function control_add_complete(){
        
        try{
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'カタログコンテンツの一覧',
                    'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                ),
                array(
                    'name' => 'カタログコンテンツ追加完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Catalog", $this->image_array);
                
                //フィールドへ格納する為の値を作成
                $this->imageFieldChange("Catalog", $this->image_array);
                
                //取得データをDBへ保存する
                if($this->Catalog->save($this->data['Catalog']) !== false){
                    
                    //画像の移動と切り取り
                    $saveImageResult = $this->addImageReplaceAndCrop(
                        "Catalog", 
                        $this->image_array, 
                        array(600, 200),
                        5
                    );
                    
                    //画像処理結果を出力する
                    $this->set('saveImageResult', 
                        $saveImageResult
                    );
                    
                    //ビューのレンダリング
                    $this->render('control_add_complete');
               }
                
            }else{
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__('不正アクセスが行われた可能性があります', true));                                    
            }
            
            
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/catalogs/add');
        }
        
    }

    //特集記事編集（管理画面）
    public function control_edit($id = null){
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/catalogs');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'カタログコンテンツの一覧',
                    'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                ),
                array(
                    'name' => 'カタログコンテンツの編集',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->Catalog->id = $id;
            $this->data = $this->Catalog->read();
            if($this->data === false){
                $this->redirect('/control/catalogs');
            }
            
            //一時画像ファイルの削除
            $this->deleteTmpImage("Catalog", $this->image_array, 5);
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/catalogs/edit/{$id}");
        }    
    }

    //特集記事編集確認（管理画面）
    public function control_edit_confirm($id = null){
        
        try{

            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id) && $data['Catalog']['id']){
                 $this->redirect('/control/catalogs');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'カタログコンテンツの一覧',
                    'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                ),
                array(
                    'name' => 'カタログコンテンツ編集内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Catalog->find('first',
                array(
                    'conditions' => array('Catalog.id' => $id),
                    'fields' => array('Catalog.catalog_image'),
                )
            );
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->disableValidateForEditConfirm("Catalog", $this->image_array);
                
                //変数に値をセット
                $this->Catalog->set($this->data);
                
                //バリデーションチェック
                if($this->Catalog->validates()){
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Catalog", 
                        $this->image_array,
                        $id,
                        5
                    );
                    
                    //画像処理結果を出力する
                    $this->set('saveTmpImageResult', 
                        $saveTmpImageResult
                    );
                   
                    //変数をセット
                    $this->set('data', $this->data);
                                        
                    //ビューのレンダリング
                    $this->render('control_edit_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array(
                            'name' => '管理画面TOP',
                            'link' => false
                        ),
                        array(
                            'name' => 'カタログコンテンツの一覧',
                            'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'カタログコンテンツの編集',
                            'link' => false
                        ),
                    );
                    $this->set('breadcrumb', $breadcrumb);
                    $this->set('error_announce','入力内容に誤りがあります。もう一度入力内容を確認して下さい');
                    
                    //ビューのレンダリング
                    $this->render('control_edit');
                    
                }
                
            }else{
                
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__('不正アクセスが行われた可能性があります', true));
            }
            
            
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/catalogs/edit/{$id}");
            
        }        
    }

    //特集記事編集確認（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/catalogs');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'カタログコンテンツの一覧',
                    'link' => array('controller' => 'catalogs', 'action' => 'control_index')
                ),
                array(
                    'name' => 'カタログコンテンツ編集完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Catalog->find('first',
                array(
                    'conditions' => array('Catalog.id' => $id),
                    'fields' => array('Catalog.catalog_image'),
                )
            );
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Catalog", $this->image_array);
                
                $this->imageFieldChangeForEditComplete("Catalog", $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Catalog->save($this->data['Catalog']) !== false){
                    
                    //画像の移動と切り取り
                    $saveImageResultMain = $this->addImageReplaceAndCrop(
                        "Catalog", 
                        array("catalog_image"), 
                        array(600, 200),
                        5
                    );
                    
                    //画像処理結果を出力する
                    $this->set('saveImageResult', 
                        $saveImageResultMain
                    );
                    
                    //ビューのレンダリング
                    $this->render('control_edit_complete');
               }
                
            }else{
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__('不正アクセスが行われた可能性があります', true));                                    
            }
            
            
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/catalogs/edit/{$id}");
        }
    }    
    
    
    //特集記事削除（管理画面）
    public function control_delete($id = null){
        
        //URLの直アクセスの禁止  
        if($this->RequestHandler->isGet()){
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ削除を行う
        if($this->RequestHandler->isAjax()){
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Catalog->find('first',
                array(
                    'conditions' => array('Catalog.id' => $id),
                    'fields' => array('Catalog.catalog_image'),
                )
            );
            
            //削除処理
            if($this->Catalog->delete($id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                
                //画像ファイルの削除
                $this->deleteImage("Catalog", $alreadyAddedImgName, 5);
                
                //全ての件数の取得
                $allAmount = $this->Catalog->find('count');
                $response = array('id' => $id, 'allAmount' => $allAmount);                
                $this->header('Content-type: application/json');
                //debugKitのAjax対策
                Configure::write('debug', 0);
                echo json_encode($response);
                exit();
            }
        }
        $this->redirect(array('action' => 'control_index'));
    }
    
    //CSVファイルのダウンロード（管理画面）
    public function control_csvdownload(){
        Configure::write('debug', 0);
        
        //レイアウトを使用しない
        $this->layout = false;
        
        //ファイル名
        $filename = 'カタログコンテンツ'.date('Ymd');
        
        //表の1行目の作成
        $headRow = array(
            'ID',
            'タイトル',
            'キャッチコピー',
            '本文',
            'コンテンツURL',
            '画像',
            '公開日',
            '公開フラグ',
        );
        
        //データを取得
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
        
        if(isset($this->params['requested'])){
            $catalogs = $this->paginate('Catalog', array('Catalog.flag' => 1));
            return $catalogs;
        }else{
            //ページングのリミットを10にする
            $this->paginate['limit'] = 10;
        
            //catalogsテーブルからデータを持ってくる
            $catalogs = $this->paginate('Catalog', array('Catalog.flag' => 1));
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
                 $this->redirect('/catalogs/');
            }
            
            //データを取得する
            $this->data = $this->Catalog->find('first',
                array( 'conditions' => array('Catalog.id' => $id, 'Catalog.flag' => 1) )
            );
            
            //該当データがなければリダイレクト
            if($this->data === false){
                $this->redirect('/catalogs/');
            }else{
                //変数をセット
                $this->set('data', $this->data);
            }
            
            //タイトルメッセージのセット
            $this->set('title_for_layout', $this->data['Catalog']['title'].'のご紹介');
        
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '大塚Catalogs', 'link' => array('controller' => 'catalogs', 'action' => 'index')),
                array('name' => $this->data['Catalog']['title'].'のご紹介','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb); 
            
        } catch (Exception $e) {
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/catalogs/");
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
        $this->data = $this->Catalog->find('first',
            array( 'conditions' => array('Catalog.id' => $catalog_id, 'Catalog.flag' => 1) )
        );
        
        //該当データがなければリダイレクト
        if($this->data === false){
            $this->redirect('/catalogs/');
        }

        //タイトルメッセージのセット
        $this->set('title_for_layout', $this->data['Catalog']['title']);
        
        //カタログ用のフォーマットへ変更する
        $this->layout = 'common_format_catalog';
        
        //パンくずリストの設定 
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => '大塚Catalogs', 'link' => array('controller' => 'catalogs', 'action' => 'index')),
            array('name' => $this->data['Catalog']['title'], 'link' => false),
        );
        $this->set('breadcrumb', $breadcrumb); 
        
        //ページング変数がなければデフォルト1に
        $page = (!empty($this->params['url']['page'])) ? $this->params['url']['page'] : 1;
        
        //API経由のデータを取得し加工する(ぐるなびAPI>Hotpepperだったのでぐるなびを基準に)
        $shop_data = $this->GourmetMap->mergeDataFromAPI($page, $this->params['url']);
        $hit_max_count = $this->GourmetMap->maxCountFromAPI($this->params['url']);
        $category_list = $this->GourmetMap->getDataFromGnaviCategoryLargeAPI();
        $this->set('shop_data', $shop_data);
        $this->set('page', $page);
        $this->set('hit_max_count', $hit_max_count);
        $this->set('category_list', $category_list);
        
        //検索用の表示パラメータの設定
        $keywords = (!empty($this->params['url']['keywords'])) ? $this->params['url']['keywords'] : null;
        $category_l = (!empty($this->params['url']['category_l'])) ? $this->params['url']['category_l'] : null;
        $range = (!empty($this->params['url']['range'])) ? $this->params['url']['range'] : null;
        $this->set( compact('keywords', 'category_l', 'range') );
        
        //コメント投稿用にIDを渡す
        $this->set('catalog_id', $catalog_id);
        
    }
    
}
?>
