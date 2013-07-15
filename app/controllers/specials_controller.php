<?php
class SpecialsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Specials';
    public $uses = array('Special');
    public $layout = 'common_format_blog';
    public $components = array('Session','RequestHandler');
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
    
    //画像格納カラム名の配列
    private $image_array = array("image_main", "image_sub1", "image_sub2", "image_sub3");

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
                'name' => '特集記事の一覧',
                'link' => false
            ),
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
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            $this->Special->id = $id;
            
            //ステータスを変更する
            if($this->Special->field('flag') == 1){
                $flag_id = 0;
            } else if($this->Special->field('flag') == 0) {
                $flag_id = 1;
            }
            
            if($this->Special->saveField('flag', $flag_id)){
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
                 $this->redirect('/control/specials');
            }
            
            //データを取得する
            $this->Special->id = $id;
            $this->data = $this->Special->read();
            if($this->data === false){
                $this->redirect('/control/specials');
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
                    'name' => '特集記事の一覧',
                    'link' => array('controller' => 'specials', 'action' => 'control_index')
                ),
                array(
                    'name' => '特集記事（'.$this->data['Special']['title'].'）',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/specials/');
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
                    'name' => '特集記事の一覧',
                    'link' => array('controller' => 'specials', 'action' => 'control_index')
                ),
                array(
                    'name' => '特集記事の追加',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/specials/add');
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
                    'name' => '特集記事の一覧',
                    'link' => array('controller' => 'specials', 'action' => 'control_index')
                ),
                array(
                    'name' => '特集記事追加内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Special->set($this->data);
                
                //バリデーションチェック
                if($this->Special->validates()){
                    
                    //次の番号のIDを出力する
                    $special_picture_id = $this->Special->getNextAutoIncrement();
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Special", 
                        $this->image_array,
                        $special_picture_id,
                        1
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
                            'name' => '特集記事の一覧',
                            'link' => array('controller' => 'specials', 'action' => 'control_index')
                        ),
                        array(
                            'name' => '特集記事の追加',
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
            $this->redirect('/control/specials/add');
            
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
                    'name' => '特集記事の一覧',
                    'link' => array('controller' => 'specials', 'action' => 'control_index')
                ),
                array(
                    'name' => '特集記事追加完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Special", $this->image_array);
                
                //フィールドへ格納する為の値を作成
                $this->imageFieldChange("Special", $this->image_array);
                
                //取得データをDBへ保存する
                if($this->Special->save($this->data['Special']) !== false){
                    
                    //画像の移動と切り取り(メイン画像)
                    $saveImageResultMain = $this->addImageReplaceAndCrop(
                        "Special", 
                        array("image_main"), 
                        array(600, 400),
                        1
                    );
                    
                    //画像の移動と切り取り(サブ画像)
                    $saveImageResultSub = $this->addImageReplaceAndCrop(
                        "Special", 
                        array("image_sub1","image_sub2","image_sub3"), 
                        array(300, 200),
                        1
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
            $this->redirect('/control/specials/add');
        }
        
    }

    //特集記事編集（管理画面）
    public function control_edit($id = null){
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/specials');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => '特集記事の一覧',
                    'link' => array('controller' => 'specials', 'action' => 'control_index')
                ),
                array(
                    'name' => '特集記事の編集',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->Special->id = $id;
            $this->data = $this->Special->read();
            if($this->data === false){
                $this->redirect('/control/specials');
            }
            
            //一時画像ファイルの削除
            $this->deleteTmpImage("Special", $this->image_array, 1);
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/specials/edit/{$id}");
        }    
    }

    //特集記事編集確認（管理画面）
    public function control_edit_confirm($id = null){
        
        try{

            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id) && $data['Special']['id']){
                 $this->redirect('/control/specials');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => '特集記事の一覧',
                    'link' => array('controller' => 'specials', 'action' => 'control_index')
                ),
                array(
                    'name' => '特集記事編集内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Special->find('first',
                array(
                    'conditions' => array('Special.id' => $id),
                    'fields' => array('Special.image_main','Special.image_sub1','Special.image_sub2','Special.image_sub3'),
                )
            );
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->disableValidateForEditConfirm("Special", $this->image_array);
                
                //変数に値をセット
                $this->Special->set($this->data);
                
                //バリデーションチェック
                if($this->Special->validates()){
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Special", 
                        $this->image_array,
                        $id,
                        1
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
                            'name' => '特集記事の一覧',
                            'link' => array('controller' => 'specials', 'action' => 'control_index')
                        ),
                        array(
                            'name' => '特集記事の編集',
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
            $this->redirect("/control/specials/edit/{$id}");
            
        }        
    }

    //特集記事編集確認（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/specials');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => '特集記事の一覧',
                    'link' => array('controller' => 'specials', 'action' => 'control_index')
                ),
                array(
                    'name' => '特集記事編集完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Special->find('first',
                array(
                    'conditions' => array('Special.id' => $id),
                    'fields' => array('Special.image_main','Special.image_sub1','Special.image_sub2','Special.image_sub3'),
                )
            );
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Special", $this->image_array);
                
                $this->imageFieldChangeForEditComplete("Special", $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Special->save($this->data['Special']) !== false){
                    
                    //画像の移動と切り取り(メイン画像)
                    $saveImageResultMain = $this->addImageReplaceAndCrop(
                        "Special", 
                        array("image_main"), 
                        array(600, 400),
                        1
                    );
                    
                    //画像の移動と切り取り(サブ画像)
                    $saveImageResultSub = $this->addImageReplaceAndCrop(
                        "Special", 
                        array("image_sub1","image_sub2","image_sub3"), 
                        array(300, 200),
                        1
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
            $this->redirect("/control/specials/edit/{$id}");
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
            $alreadyAddedImgName = $this->Special->find('first',
                array(
                    'conditions' => array('Special.id' => $id),
                    'fields' => array('Special.image_main','Special.image_sub1','Special.image_sub2','Special.image_sub3'),
                )
            );
            
            //削除処理
            if($this->Special->delete($id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                
                //画像ファイルの削除
                $this->deleteImage("Special", $alreadyAddedImgName, 1);
                
                //全ての件数の取得
                $allAmount = $this->Special->find('count');
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
        $filename = '特集記事一覧'.date('Ymd');
        
        //表の1行目の作成
        $headRow = array(
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
        
        //データを取得
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
        
        //ページングのリミットを10にする
        $this->paginate['limit'] = 10;
        
        //specialsテーブルからデータを持ってくる
        $specials = $this->paginate('Special', array('Special.flag' => 0));
        $this->set('specials', $specials);
        
        //ビューのレンダリング
        $this->render('index');
    }

    //特集記事閲覧
    public function view($id = null){
        
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/specials/');
            }
            
            //データを取得する
            $this->data = $this->Special->find('first',
                array(
                    'conditions' => array('Special.id' => $id, 'Special.flag' => 0),
                )
            );
            if($this->data === false){
                $this->redirect('/specials/');
            }else{
                //変数をセット
                $this->set('data', $this->data);
            }
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','特集記事（'.$this->data['Special']['title'].'）');
        
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '特集記事一覧', 'link' => array('controller' => 'specials', 'action' => 'index')),
                array('name' => '特集記事（'.$this->data['Special']['title'].'）','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb); 
            
        } catch (Exception $e) {
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/specials/");
        }
            
    }
    
}
?>
