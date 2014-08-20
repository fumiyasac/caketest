<?php
class ShowcasesController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Showcases';
    public $uses = array('Showcase');
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform');
    
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
    
    //画像格納カラム名の配列
    private $image_array = array("image_main", "image_sub1", "image_sub2", "image_sub3", "image_sub4");
    
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
                'name' => 'ショーケースの一覧',
                'link' => false
            ),
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
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            $this->Showcase->id = $id;
            
            //ステータスを変更する
            if($this->Showcase->field('flag') == 2){
                $flag_id = 1;
            } else if($this->Showcase->field('flag') == 1) {
                $flag_id = 2;
            }
            
            if($this->Showcase->saveField('flag', $flag_id)){
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
                 $this->redirect('/control/showcases');
            }
            
            //データを取得する
            $this->Showcase->id = $id;
            $this->data = $this->Showcase->read();
            if($this->data === false){
                $this->redirect('/control/showcases');
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
                    'name' => 'ショーケースの一覧',
                    'link' => array('controller' => 'showcases', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ショーケース（'.$this->data['Showcase']['title'].'）',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/showcases/');
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
                    'name' => 'ショーケースの一覧',
                    'link' => array('controller' => 'showcases', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ショーケースの追加',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/showcases/add');
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
                    'name' => 'ショーケースの一覧',
                    'link' => array('controller' => 'showcases', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ショーケース追加内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Showcase->set($this->data);
                
                //バリデーションチェック
                if($this->Showcase->validates()){
                    
                    //次の番号のIDを出力する
                    $showcase_picture_id = $this->Showcase->getNextAutoIncrement();
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Showcase", 
                        $this->image_array,
                        $showcase_picture_id,
                        7
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
                            'name' => 'ショーケースの一覧',
                            'link' => array('controller' => 'showcases', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'ショーケースの追加',
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
            $this->redirect('/control/showcases/add');
            
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
                    'name' => 'ショーケースの一覧',
                    'link' => array('controller' => 'showcases', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ショーケース追加完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Showcase", $this->image_array);
                
                //フィールドへ格納する為の値を作成
                $this->imageFieldChange("Showcase", $this->image_array);
                
                //取得データをDBへ保存する
                if($this->Showcase->save($this->data['Showcase']) !== false){
                    
                    //画像の移動と切り取り(メイン画像)
                    $saveImageResultMain = $this->addImageReplaceAndCrop(
                        "Showcase", 
                        array("image_main"), 
                        array(600,400),
                        7
                    );
                    
                    //画像の移動と切り取り(サブ画像) ※hover用の画像サイズは1024×768
                    $saveImageResultSub = $this->addImageReplaceAndCrop(
                        "Showcase", 
                        array("image_sub1","image_sub2","image_sub3","image_sub4"), 
                        array(600,400),
                        7
                    );
                    
                    //画像処理結果を出力する
                    $this->set('saveImageResult', 
                        array_merge($saveImageResultMain + $saveImageResultSub)
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
            $this->redirect('/control/showcases/add');
        }
        
    }

    //特集記事編集（管理画面）
    public function control_edit($id = null){
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/showcases');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'ショーケースの一覧',
                    'link' => array('controller' => 'showcases', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ショーケースの編集',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->Showcase->id = $id;
            $this->data = $this->Showcase->read();
            if($this->data === false){
                $this->redirect('/control/showcases');
            }
            
            //一時画像ファイルの削除
            $this->deleteTmpImage("Showcase", $this->image_array, 7);
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/showcases/edit/{$id}");
        }    
    }

    //特集記事編集確認（管理画面）
    public function control_edit_confirm($id = null){
        
        try{

            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id) && $data['Showcase']['id']){
                 $this->redirect('/control/showcases');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'ショーケースの一覧',
                    'link' => array('controller' => 'showcases', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ショーケース編集内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Showcase->find('first',
                array(
                    'conditions' => array('Showcase.id' => $id),
                    'fields' => array(
                    	'Showcase.image_main',
                    	'Showcase.image_sub1',
                    	'Showcase.image_sub2',
                    	'Showcase.image_sub3',
                    	'Showcase.image_sub4'
                    )
                )
            );
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->disableValidateForEditConfirm("Showcase", $this->image_array);
                
                //変数に値をセット
                $this->Showcase->set($this->data);
                
                //バリデーションチェック
                if($this->Showcase->validates()){
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Showcase", 
                        $this->image_array,
                        $id,
                        7
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
                            'name' => 'ショーケースの一覧',
                            'link' => array('controller' => 'showcases', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'ショーケースの編集',
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
            $this->redirect("/control/showcases/edit/{$id}");
            
        }        
    }

    //特集記事編集確認（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/showcases');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'ショーケースの一覧',
                    'link' => array('controller' => 'showcases', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ショーケース編集完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Showcase->find('first',
                array(
                    'conditions' => array('Showcase.id' => $id),
                    'fields' => array(
                    	'Showcase.image_main',
                    	'Showcase.image_sub1',
                    	'Showcase.image_sub2',
                    	'Showcase.image_sub3',
                    	'Showcase.image_sub4'
                    )
                )
            );
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Showcase", $this->image_array);
                
                $this->imageFieldChangeForEditComplete("Showcase", $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Showcase->save($this->data['Showcase']) !== false){
                    
                    //画像の移動と切り取り(メイン画像)
                    $saveImageResultMain = $this->addImageReplaceAndCrop(
                        "Showcase", 
                        array("image_main"), 
                        array(600, 400),
                        7
                    );
                    
                    //画像の移動と切り取り(サブ画像)
                    $saveImageResultSub = $this->addImageReplaceAndCrop(
                        "Showcase", 
                        array("image_sub1","image_sub2","image_sub3","image_sub4"), 
                        array(600, 400),
                        7
                    );
                    
                    //画像処理結果を出力する
                    $this->set('saveImageResult', 
                        array_merge($saveImageResultMain + $saveImageResultSub)
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
            $this->redirect("/control/showcases/edit/{$id}");
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
            $alreadyAddedImgName = $this->Showcase->find('first',
                array(
                    'conditions' => array('Showcase.id' => $id),
                    'fields' => array(
                    	'Showcase.image_main',
                    	'Showcase.image_sub1',
                    	'Showcase.image_sub2',
                    	'Showcase.image_sub3',
                    	'Showcase.image_sub4'
                    )
                )
            );
            
            //削除処理
            if($this->Showcase->delete($id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                
                //画像ファイルの削除
                $this->deleteImage("Showcase", $alreadyAddedImgName, 1);
                
                //全ての件数の取得
                $allAmount = $this->Showcase->find('count');
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
        $filename = 'ショーケース一覧'.date('Ymd');
        
        //表の1行目の作成
        $headRow = array(
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
        
        //データを取得
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
        
        if(isset($this->params['requested'])){
            $showcases = $this->paginate('Showcase', array('Showcase.flag' => 1));
            return $showcases;
        }else{
            //ページングのリミットを10にする
            $this->paginate['limit'] = 10;
        
            //showcasesテーブルからデータを持ってくる
            $showcases = $this->paginate('Showcase', array('Showcase.flag' => 1));
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
                 $this->redirect('/showcases/');
            }
            
            //データを取得する
            $this->data = $this->Showcase->find('first',
                array(
                    'conditions' => array('Showcase.id' => $id, 'Showcase.flag' => 1),
                )
            );
            if($this->data === false){
                $this->redirect('/showcases/');
            }else{
                //変数をセット
                $this->set('data', $this->data);
            }
            
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
            $this->redirect("/showcases/");
        }
            
    }
    
}
?>
