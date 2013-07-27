<?php
Class SlidesController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Slides';
    public $uses = array('Slide');
    public $layout = 'common_format_blog';
    public $components = array('Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform');
    
    //ページャーの設定
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'title',
            'description',
            'slide_image',
            'link_url',
            'published',
            'blank_flag',
            'flag',
            'created',
            'modified',
            ),
        
        'limit' => 100,
        'order' => 'Slide.id DESC',
    );
    
    //画像格納カラム名の配列
    private $image_array = array("slide_image");
    
    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
    
    //スライドショー画像管理TOP（管理画面）
    public function control_index(){
        
        //パンくずリストの設定
        $breadcrumb = array(
            array(
                'name' => '管理画面TOP',
                'link' => false
            ),
            array(
                'name' => 'スライドショー画像の一覧',
                'link' => false
            ),
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->Slide->find('count');
        $this->set('allAmount',$allAmount);
        
        //slidesテーブルからデータを持ってくる
        $slides = $this->paginate();
        $this->set('slides', $slides);
        
        //表示数を取得
        $limitAmount = $this->paginate['limit'];
        $this->set('limitAmount',$limitAmount);
        
        //ビューのレンダリング
        $this->render('control_index'); 
        
    }
    
    //登録表示ステータス変更（管理画面）
    public function control_change($id = null){
        //URLの直アクセスの禁止  
        if($this->RequestHandler->isGet()){
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            $this->Slide->id = $id;
            
            //ステータスを変更する
            if($this->Slide->field('flag') == 2){
                $flag_id = 1;
            } else if($this->Slide->field('flag') == 1) {
                $flag_id = 2;
            }
            
            if($this->Slide->saveField('flag', $flag_id)){
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
    
    //登録スライドショー追加（管理画面）
    public function control_add(){
        try {
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'スライドショー画像の一覧',
                    'link' => array('controller' => 'slides', 'action' => 'control_index')
                ),
                array(
                    'name' => 'スライドショー画像の追加',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            $this->set('sizeValidateFlag', 1);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/slides/add');
        }
    }

    //スライドショー画像追加完了（管理画面）
    public function control_add_complete(){

       try{
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Slide->set($this->data);
                
                //次の番号のIDを出力する
                $slide_picture_id = $this->Slide->getNextAutoIncrement();

                //画像を一時保存場所へアップロードする                    
                $saveTmpImageResult = $this->loopAndGenerateImages(
                    "Slide", 
                    $this->image_array,
                    $slide_picture_id,
                    3
                );
                
                //画像処理結果を出力する
                $this->set('saveTmpImageResult', 
                    $saveTmpImageResult
                );
                
                //サイズバリデーションのフラグ（0:NG,1:OK,2:画像なし）
                $sizeValidateFlag = false;
                
                //画像のアップロードが正しく成功したら、画像サイズチェックをする
                if($saveTmpImageResult['slide_image'] === 1){
                    
                    $sizeValidateResult = $this->imagePixelCheck(
                        "Slide",
                        $this->image_array,
                        3,
                        array(360, 250),
                        array(true, true)
                    );
                    
                    if($sizeValidateResult['slide_image'] === 1){
                        $sizeValidateFlag = 1;
                    }else{
                        $sizeValidateFlag = 0;
                    }
                    

                //対象の画像がない場合、正しい画像がtmpフォルダにあるかを確認
                }else if($saveTmpImageResult['slide_image'] === 2){
                    
                    //現在紐づいている画像ファイル名を取得する
                    $r = $this->isTmpImageExistSingle("slide_image_".$slide_picture_id, 3);
                    
                    //tmp_slideに正しい画像がある場合
                    if($r !== ''){
                        //すでに正しい画像がtmp_slide内にあるので画像バリデーションをキャンセル
                        $this->disableValidate('Slide', $this->image_array);
                        $this->data['Slide']['slide_image']['name'] = $r;
                        $sizeValidateFlag = 1;
                    } else {
                        $sizeValidateFlag = 2;
                    }
                    
                //アップロードミスの場合は何もしない
                }else{    
                    $sizeValidateFlag = 2;
                }
                
                $this->set('sizeValidateFlag', 
                    $sizeValidateFlag
                );
                
                //バリデーションチェック
                if($this->Slide->validates() && $sizeValidateFlag === 1){
                    
                    //フィールドへ格納する為の値を作成
                    $this->imageFieldChange("Slide", $this->image_array);
                    
                    //バリデーションを無効にする
                    $this->disableValidate("Slide", $this->image_array);
                    
                    //取得データをDBへ保存する
                    if($this->Slide->save($this->data['Slide']) !== false){
                        
                        //画像の移動と切り取り
                        $saveImageResult = $this->addImageReplaceOnly(
                            "Slide", 
                            array("slide_image"),
                            3
                        );
                        
                        //画像処理結果を出力する
                        $this->set('saveImageResult', 
                            $saveImageResult
                        );
                        
                        //残った画像は削除
                        $this->deleteTmpImageAllPattern("slide_image_".$slide_picture_id, 2);
                        
                        //パンくずリストの設定
                        $breadcrumb = array(
                            array(
                                'name' => '管理画面TOP',
                                'link' => false
                            ),
                            array(
                                'name' => 'スライドショー画像の一覧',
                                'link' => array('controller' => 'slides', 'action' => 'control_index')
                            ),
                            array(
                                'name' => 'スライドショー画像の追加完了',
                                'link' => false
                            ),
                        );
                        $this->set('breadcrumb', $breadcrumb);

                        //ビューのレンダリング
                        $this->render('control_add_complete');
                        
                    }
                    
                }else{
                    
                    //フィールドへ格納する為の値を作成
                    $this->imageFieldChange("Slide", $this->image_array);
                    
                    //正しい画像がアップされていない場合は、画像(ファイル)を削除
                    if($sizeValidateFlag == 0){
                        
                        //新しくアップロードしたファイルを削除
                        $this->deleteTmpImage('Slide', $this->image_array, 2);
                        //tmp_slide内に残っている該当IDの画像を全て削除
                        $this->deleteTmpImageAllPattern("slide_image_".$slide_picture_id, 2);
                        //slide_imageフィールドを空にする
                        $this->data['Slide']['slide_image'] = "";
                        
                    }
                   
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array(
                            'name' => '管理画面TOP',
                            'link' => false
                        ),
                        array(
                            'name' => 'スライドショー画像の一覧',
                            'link' => array('controller' => 'slides', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'スライドショー画像の追加',
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
            $this->redirect('/control/slides/add');
            
        }
        
    }

    //スライドショー画像の編集（管理画面）
    public function control_edit($id = null){
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/slides');
            }
            
            //データを取得する
            $this->Slide->id = $id;
            $this->data = $this->Slide->read();
            if($this->data === false){
                $this->redirect('/control/slides');
            }
            
            //残った画像は削除
            $this->deleteTmpImageAllPattern("slide_image_".$id, 2);
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'スライドショー画像の一覧',
                    'link' => array('controller' => 'slides', 'action' => 'control_index')
                ),
                array(
                    'name' => 'スライドショー画像の編集',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            $this->set('sizeValidateFlag', null);
            $this->set('alreadyAddedImgName', null);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/slides/');
        }
    }

    //スライドショー画像編集完了（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/slides/');
            }
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Slide->find('first',
                array(
                    'conditions' => array('Slide.id' => $id),
                    'fields' => array('Slide.slide_image'),
                )
            );
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
             
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->disableValidateForEditConfirm("Slide", $this->image_array);
                
                //変数に値をセット
                $this->Slide->set($this->data);
                    
                //画像を一時保存場所へアップロードする                    
                $saveTmpImageResult = $this->loopAndGenerateImages(
                    "Slide", 
                    $this->image_array,
                    $id,
                    3
                );

                //画像処理結果を出力する
                $this->set('saveTmpImageResult', 
                    $saveTmpImageResult
                );

                //サイズバリデーションのフラグ（0:NG,1:OK,2:画像なし）
                $sizeValidateFlag = false;

                //画像のアップロードが正しく成功したら、画像サイズチェックをする
                if($saveTmpImageResult['slide_image'] === 1){

                    $sizeValidateResult = $this->imagePixelCheck(
                        "Slide",
                        $this->image_array,
                        3,
                        array(360, 250),
                        array(true, true)
                    );

                    if($sizeValidateResult['slide_image'] === 1){
                        $sizeValidateFlag = 1;
                    }else{
                        $sizeValidateFlag = 0;
                    }


                //対象の画像がない場合、正しい画像がtmpフォルダにあるかを確認
                }else if($saveTmpImageResult['slide_image'] === 2){

                    //現在紐づいている画像ファイル名を取得する
                    $r = $this->isImageExistSingle("slide_image_".$id, 3);

                    //tmp_slideに正しい画像がある場合
                    if($r !== ''){
                        //すでに正しい画像がtmp_slide内にあるので画像バリデーションをキャンセル
                        $this->disableValidate('Slide', $this->image_array);
                        $this->data['Slide']['slide_image']['name'] = $r;
                        $sizeValidateFlag = 1;
                    } else {
                        $sizeValidateFlag = 2;
                    }

                //アップロードミスの場合は何もしない
                }else{    
                    $sizeValidateFlag = 2;
                }
                
                $this->set('sizeValidateFlag', 
                    $sizeValidateFlag
                );
                
                //バリデーションチェック
                if($this->Slide->validates() && $sizeValidateFlag === 1){
                    
                    //フィールドへ格納する為の値を作成                                        
                    if(!empty($this->data['Slide']['slide_image']['name'])){
                        $this->imageFieldChange("Slide", $this->image_array);
                    }
                    
                    //バリデーションを無効にする
                    $this->disableValidate("Slide", $this->image_array);
                    
                    //取得データをDBへ保存する
                    if($this->Slide->save($this->data['Slide']) !== false){
                        
                        //現状でアップロードされている画像を削除
                        if($saveTmpImageResult['slide_image'] === 1){
                            $this->deleteImageAllPattern("slide_image_".$id, 3);
                        }
                        
                        //画像の移動と切り取り
                        $saveImageResult = $this->addImageReplaceOnly(
                            "Slide", 
                            array("slide_image"),
                            3
                        );

                        //画像処理結果を出力する
                        $this->set('saveImageResult', 
                            $saveImageResult
                        );

                        //残った画像は削除
                        if($sizeValidateFlag === 1){
                            $this->deleteTmpImageAllPattern("slide_image_".$id, 2);
                        }
                        
                        //パンくずリストの設定
                        $breadcrumb = array(
                            array(
                                'name' => '管理画面TOP',
                                'link' => false
                            ),
                            array(
                                'name' => 'スライドショー画像の一覧',
                                'link' => array('controller' => 'slides', 'action' => 'control_index')
                            ),
                            array(
                                'name' => 'スライドショー画像の編集完了',
                                'link' => false
                            ),
                        );
                        $this->set('breadcrumb', $breadcrumb);

                        //ビューのレンダリング
                        $this->render('control_edit_complete');
                        
                    }
                    
                }else{
                    
                    //フィールドへ格納する為の値を作成
                    $this->imageFieldChange("Slide", $this->image_array);

                    //新しくアップロードしたファイルを削除
                    $this->deleteTmpImage('Slide', $this->image_array, 2);
                    //tmp_slide内に残っている該当IDの画像を全て削除
                    $this->deleteTmpImageAllPattern("slide_image_".$id, 2);
                    //slide_imageフィールドを空にする
                    $this->data['Slide']['slide_image'] = "";
                   
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array(
                            'name' => '管理画面TOP',
                            'link' => false
                        ),
                        array(
                            'name' => 'スライドショー画像の一覧',
                            'link' => array('controller' => 'slides', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'スライドショー画像の編集',
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
            
            
        }catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/slides/edit/{$id}");
        }
        
    }

    //スライドショー画像削除（管理画面）
    public function control_delete($id = null){
        //URLの直アクセスの禁止  
        if($this->RequestHandler->isGet()){
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ削除を行う
        if($this->RequestHandler->isAjax()){
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Slide->find('first',
                array(
                    'conditions' => array('Slide.id' => $id),
                    'fields' => array('Slide.slide_image'),
                )
            );
            
            //削除処理
            if($this->Slide->delete($id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                
                //画像ファイルの削除
                $this->deleteImage("Slide", $alreadyAddedImgName, 3);
                
                //全ての件数の取得
                $allAmount = $this->Slide->find('count');
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
        $filename = 'TOPスライドショー画像の一覧'.date('Ymd');
        
        //表の1行目の作成
        $headRow = array(
            'ID',
            'タイトル',
            'スライド画像',
            'スライドショー画像',
            'リンクURL',
            'リンクの種類',
            '公開日',
            '公開フラグ',
        );
        
        //データを取得
        $contentsRows = $this->Slide->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }

    //登録バナー（エレメント出力のみ）
    public function index(){
        $posts = $this->paginate('Slide', array('Slide.flag' => 1));
        if(isset($this->params['requested'])){
            return $posts;
        }
    }
}
?>