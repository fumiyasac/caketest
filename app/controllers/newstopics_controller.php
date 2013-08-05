<?php
class NewstopicsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Newstopics';
    public $uses = array('Newstopic');
    public $layout = 'common_format_blog';
    public $components = array('Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform');
    
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
    
    //画像格納カラム名の配列
    private $image_array = array("newstopic_image");

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
                'name' => 'ニュース&トピックの一覧',
                'link' => false
            ),
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
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            $this->Newstopic->id = $id;
            
            //ステータスを変更する
            if($this->Newstopic->field('flag') == 2){
                $flag_id = 1;
            } else if($this->Newstopic->field('flag') == 1) {
                $flag_id = 2;
            }
            
            if($this->Newstopic->saveField('flag', $flag_id)){
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
                 $this->redirect('/control/newstopics');
            }
            
            //データを取得する
            $this->Newstopic->id = $id;
            $this->data = $this->Newstopic->read();
            if($this->data === false){
                $this->redirect('/control/newstopics');
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
                    'name' => 'ニュース&トピックの一覧',
                    'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                ),
                array(
                    'name' => $this->data['Newstopic']['title'],
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/newstopics/');
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
                    'name' => 'ニュース&トピックの一覧',
                    'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ニュース&トピックの追加',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/newstopics/add');
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
                    'name' => 'ニュース&トピックの一覧',
                    'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ニュース&トピック追加内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Newstopic->set($this->data);
                
                //バリデーションチェック
                if($this->Newstopic->validates()){
                    
                    //次の番号のIDを出力する
                    $newstopic_picture_id = $this->Newstopic->getNextAutoIncrement();
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Newstopic", 
                        $this->image_array,
                        $newstopic_picture_id,
                        4
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
                            'name' => 'ニュース&トピックの一覧',
                            'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'ニュース&トピックの追加',
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
            $this->redirect('/control/newstopics/add');
            
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
                    'name' => 'ニュース&トピックの一覧',
                    'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ニュース&トピック追加完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Newstopic", $this->image_array);
                
                //フィールドへ格納する為の値を作成
                $this->imageFieldChange("Newstopic", $this->image_array);
                
                //取得データをDBへ保存する
                if($this->Newstopic->save($this->data['Newstopic']) !== false){
                    
                    //画像の移動と切り取り
                    $saveImageResult = $this->addImageReplaceAndCrop(
                        "Newstopic", 
                        $this->image_array, 
                        array(600, 200),
                        4
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
            $this->redirect('/control/newstopics/add');
        }
        
    }

    //特集記事編集（管理画面）
    public function control_edit($id = null){
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/newstopics');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'ニュース&トピックの一覧',
                    'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ニュース&トピックの編集',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->Newstopic->id = $id;
            $this->data = $this->Newstopic->read();
            if($this->data === false){
                $this->redirect('/control/newstopics');
            }
            
            //一時画像ファイルの削除
            $this->deleteTmpImage("Newstopic", $this->image_array, 4);
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/newstopics/edit/{$id}");
        }    
    }

    //特集記事編集確認（管理画面）
    public function control_edit_confirm($id = null){
        
        try{

            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id) && $data['Newstopic']['id']){
                 $this->redirect('/control/newstopics');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'ニュース&トピックの一覧',
                    'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ニュース&トピック編集内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Newstopic->find('first',
                array(
                    'conditions' => array('Newstopic.id' => $id),
                    'fields' => array('Newstopic.newstopic_image'),
                )
            );
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->disableValidateForEditConfirm("Newstopic", $this->image_array);
                
                //変数に値をセット
                $this->Newstopic->set($this->data);
                
                //バリデーションチェック
                if($this->Newstopic->validates()){
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Newstopic", 
                        $this->image_array,
                        $id,
                        4
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
                            'name' => 'ニュース&トピックの一覧',
                            'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'ニュース&トピックの編集',
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
            $this->redirect("/control/newstopics/edit/{$id}");
            
        }        
    }

    //特集記事編集確認（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/newstopics');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'ニュース&トピックの一覧',
                    'link' => array('controller' => 'newstopics', 'action' => 'control_index')
                ),
                array(
                    'name' => 'ニュース&トピック編集完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Newstopic->find('first',
                array(
                    'conditions' => array('Newstopic.id' => $id),
                    'fields' => array('Newstopic.newstopic_image'),
                )
            );
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Newstopic", $this->image_array);
                
                $this->imageFieldChangeForEditComplete("Newstopic", $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Newstopic->save($this->data['Newstopic']) !== false){
                    
                    //画像の移動と切り取り
                    $saveImageResultMain = $this->addImageReplaceAndCrop(
                        "Newstopic", 
                        array("newstopic_image"), 
                        array(600, 200),
                        4
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
            $this->redirect("/control/newstopics/edit/{$id}");
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
            $alreadyAddedImgName = $this->Newstopic->find('first',
                array(
                    'conditions' => array('Newstopic.id' => $id),
                    'fields' => array('Newstopic.newstopic_image'),
                )
            );
            
            //削除処理
            if($this->Newstopic->delete($id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                
                //画像ファイルの削除
                $this->deleteImage("Newstopic", $alreadyAddedImgName, 4);
                
                //全ての件数の取得
                $allAmount = $this->Newstopic->find('count');
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
        $filename = 'ニュース&トピック'.date('Ymd');
        
        //表の1行目の作成
        $headRow = array(
            'ID',
            'タイトル',
            '画像',
            '本文',
            'リンクURL',
            'リンクの種類',
            '公開日',
            '公開フラグ',
        );
        
        //データを取得
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
        
        if(isset($this->params['requested'])){
            $newstopics = $this->paginate('Newstopic', array('Newstopic.flag' => 1));
            return $newstopics;
        }else{
            //ページングのリミットを10にする
            $this->paginate['limit'] = 10;
        
            //newstopicsテーブルからデータを持ってくる
            $newstopics = $this->paginate('Newstopic', array('Newstopic.flag' => 1));
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
                 $this->redirect('/newstopics/');
            }
            
            //データを取得する
            $this->data = $this->Newstopic->find('first',
                array(
                    'conditions' => array('Newstopic.id' => $id, 'Newstopic.flag' => 1),
                )
            );
            if($this->data === false){
                $this->redirect('/newstopics/');
            }else{
                //変数をセット
                $this->set('data', $this->data);
            }
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','ニュース&トピック（'.$this->data['Newstopic']['title'].'）');
        
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'ニュース&トピック一覧', 'link' => array('controller' => 'newstopics', 'action' => 'index')),
                array('name' => 'ニュース&トピック（'.$this->data['Newstopic']['title'].'）','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb); 
            
        } catch (Exception $e) {
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/newstopics/");
        }
            
    }
    
}
?>
