<?php
Class PostsController extends AppController{

	//メンバ変数の設定
	var $name = 'Posts';
    public $uses = array('Post','PostsQuestion','PostsAnswer');
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler','PostEnquete');
    public $helpers = array('Formhidden','Csv','Html','Dateform','MakeEnquete');
    
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'title',
            'description',
            'start_date',
            'end_date',
            'post_image',
            'flag',
            'created',
            'modified',
            ),
        
        'limit' => 100,
        'order' => 'Post.id DESC',
    );

	//アンケートの質問個数
	const POSTS_ANSWER_AMOUNT = 5;
	
	//アンケートマスタ追加時のデフォルト値
    private $default_question = array(
    	'required' => 1,
    	'type' => 1,
    	'question' => "アンケートの質問事項を入力して下さい",
    	'flag' => 1
    );
	
    //画像格納カラム名の配列
    private $image_array = array("post_image");
    
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
                'name' => 'アンケートコンテンツの一覧',
                'link' => false
            ),
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->Post->find('count');
        $this->set('allAmount',$allAmount);
        
        //postsテーブルからデータを持ってくる
        $posts = $this->paginate();
        $this->set('posts', $posts);
        
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
            
            $this->Post->id = $id;
            
            //ステータスを変更する
            if($this->Post->field('flag') == 2){
                $flag_id = 1;
            } else if($this->Post->field('flag') == 1) {
                $flag_id = 2;
            }
            
            if($this->Post->saveField('flag', $flag_id)){
                //関連テーブルのステータス変更
                $conditions = array('post_id' => $id);
                $fields = array('flag' => $flag_id);
                
                $this->PostsQuestion->updateAll($fields,$conditions);
                $this->PostsAnswer->updateAll($fields,$conditions);
                                
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
                 $this->redirect('/control/posts');
            }
            
            //データを取得する
            $this->Post->id = $id;
            $this->data = $this->Post->read();
            if($this->data === false){
                $this->redirect('/control/posts');
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
                    'name' => 'アンケートコンテンツの一覧',
                    'link' => array('controller' => 'posts', 'action' => 'control_index')
                ),
                array(
                    'name' => $this->data['Post']['title'],
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/posts/');
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
                    'name' => 'アンケートコンテンツの一覧',
                    'link' => array('controller' => 'posts', 'action' => 'control_index')
                ),
                array(
                    'name' => 'アンケートコンテンツの追加',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/control/posts/add');
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
                    'name' => 'アンケートコンテンツの一覧',
                    'link' => array('controller' => 'posts', 'action' => 'control_index')
                ),
                array(
                    'name' => 'アンケートコンテンツ追加内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Post->set($this->data);
                
                //バリデーションチェック
                if($this->Post->validates()){
                    
                    //次の番号のIDを出力する
                    $post_picture_id = $this->Post->getNextAutoIncrement();
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Post", 
                        $this->image_array,
                        $post_picture_id,
                        6
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
                            'name' => 'アンケートコンテンツの一覧',
                            'link' => array('controller' => 'posts', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'アンケートコンテンツの追加',
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
            $this->redirect('/control/posts/add');
            
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
                    'name' => 'アンケートコンテンツの一覧',
                    'link' => array('controller' => 'posts', 'action' => 'control_index')
                ),
                array(
                    'name' => 'アンケートコンテンツ追加完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Post", $this->image_array);
                
                //フィールドへ格納する為の値を作成
                $this->imageFieldChange("Post", $this->image_array);
                
                //取得データをDBへ保存する
                if($this->Post->save($this->data['Post']) !== false){
                    
                    //画像の移動と切り取り
                    $saveImageResult = $this->addImageReplaceAndCrop(
                        "Post", 
                        $this->image_array, 
                        array(600, 200),
                        6
                    );
                    
                    //画像処理結果を出力する
                    $this->set('saveImageResult', 
                        $saveImageResult
                    );
                    
                    //5件のデータをposts_questionsにレコードを追加しておく
                    $last_post_id = $this->Post->getLastInsertID();

                    $data_question_array = array();
                    $data_answer_array = array();
				    for($i=0; $i<self::POSTS_ANSWER_AMOUNT; $i++){
					    //質問用データ登録
					    $data_question_array['PostsQuestion'] = $this->default_question;
					    $data_question_array['PostsQuestion']['post_id'] = $last_post_id;
					    $data_question_array['PostsQuestion']['id'] = $this->PostsQuestion->getNextAutoIncrement();
						$this->PostsQuestion->save($data_question_array);
						//選択肢用データ登録
					    $data_answer_array['PostsAnswer']['flag'] = 1;
					    $data_answer_array['PostsAnswer']['post_question_id'] = $this->PostsQuestion->getLastInsertID();
					    $data_answer_array['PostsAnswer']['post_id'] = $last_post_id;
					    $data_answer_array['PostsAnswer']['id'] = $this->PostsAnswer->getNextAutoIncrement();
						$this->PostsAnswer->save($data_answer_array);						
				    }
                    
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
            $this->redirect('/control/posts/add');
        }
        
    }

    //特集記事編集（管理画面）
    public function control_edit($id = null){
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/posts');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'アンケートコンテンツの一覧',
                    'link' => array('controller' => 'posts', 'action' => 'control_index')
                ),
                array(
                    'name' => 'アンケートコンテンツの編集',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->Post->id = $id;
            $this->data = $this->Post->read();
            if($this->data === false){
                $this->redirect('/control/posts');
            }
            
            //一時画像ファイルの削除
            $this->deleteTmpImage("Post", $this->image_array, 6);
            
        } catch (Exception $e) {
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/posts/edit/{$id}");
        }    
    }

    //特集記事編集確認（管理画面）
    public function control_edit_confirm($id = null){
        
        try{

            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id) && $data['Post']['id']){
                 $this->redirect('/control/posts');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'アンケートコンテンツの一覧',
                    'link' => array('controller' => 'posts', 'action' => 'control_index')
                ),
                array(
                    'name' => 'アンケートコンテンツ編集内容の確認',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Post->find('first',
                array(
                    'conditions' => array('Post.id' => $id),
                    'fields' => array('Post.post_image'),
                )
            );
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->disableValidateForEditConfirm("Post", $this->image_array);
                
                //変数に値をセット
                $this->Post->set($this->data);
                
                //バリデーションチェック
                if($this->Post->validates()){
                    
                    //画像を一時保存場所へアップロードする                    
                    $saveTmpImageResult = $this->loopAndGenerateImages(
                        "Post", 
                        $this->image_array,
                        $id,
                        6
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
                            'name' => 'アンケートコンテンツの一覧',
                            'link' => array('controller' => 'posts', 'action' => 'control_index')
                        ),
                        array(
                            'name' => 'アンケートコンテンツの編集',
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
            $this->redirect("/control/posts/edit/{$id}");
            
        }        
    }

    //特集記事編集確認（管理画面）
    public function control_edit_complete($id = null){
        
        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/control/posts');
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array(
                    'name' => '管理画面TOP',
                    'link' => false
                ),
                array(
                    'name' => 'アンケートコンテンツの一覧',
                    'link' => array('controller' => 'posts', 'action' => 'control_index')
                ),
                array(
                    'name' => 'アンケートコンテンツ編集完了',
                    'link' => false
                ),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Post->find('first',
                array(
                    'conditions' => array('Post.id' => $id),
                    'fields' => array('Post.post_image'),
                )
            );
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                  
                //一部バリデーションを無効にする
                $this->disableValidate("Post", $this->image_array);
                
                $this->imageFieldChangeForEditComplete("Post", $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Post->save($this->data['Post']) !== false){
                    
                    //画像の移動と切り取り
                    $saveImageResultMain = $this->addImageReplaceAndCrop(
                        "Post", 
                        $this->image_array, 
                        array(600, 200),
                        6
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
            $this->redirect("/control/posts/edit/{$id}");
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
            $alreadyAddedImgName = $this->Post->find('first',
                array(
                    'conditions' => array('Post.id' => $id),
                    'fields' => array('Post.post_image'),
                )
            );
            
            //削除処理
            if($this->Post->delete($id)){
            	
            	//関連テーブルの削除
            	$this->PostsQuestion->deleteAll(array('PostsQuestion.post_id' => $id),false);
            	$this->PostsAnswer->deleteAll(array('PostsAnswer.post_id' => $id),false);
            	            
                $this->autoRender = false;
                $this->autoLayout = false;
                
                //画像ファイルの削除
                $this->deleteImage("Post", $alreadyAddedImgName, 6);
                
                //全ての件数の取得
                $allAmount = $this->Post->find('count');
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
        $filename = 'アンケート'.date('Ymd');
        
        //表の1行目の作成
        $headRow = array(
            'ID',
            'タイトル',
            '画像',
            '内容',
            '開始日',
            '終了日',
            '公開フラグ',
        );
        
        //データを取得
        $contentsRows = $this->Post->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }


	//Ajax処理を用いてアンケート項目のフォームを表示する
	function control_form_edit($id = null){
	
	    //idがなければ一覧ページへリダイレクト
	    if(!isset($id) && is_numeric($id)){
	         $this->redirect('/control/posts');
	    }
	    
	    //データを取得する
        $this->Post->id = $id;
        $this->data = $this->Post->read();
        if($this->data === false){
	    	$this->redirect('/control/posts');
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
	            'name' => 'アンケートコンテンツの一覧',
	            'link' => array('controller' => 'posts', 'action' => 'control_index')
	        ),
	        array(
	            'name' => 'アンケートコンテンツの編集',
	            'link' => false
	        ),
	    );
	    $this->set('breadcrumb', $breadcrumb);
	    
	    //posts_questions,posts_answersテーブルからアンケート質問事項をもってくる
	    $conditions = array('post_id' => $id);
	    $order = array('id ASC');
	    $posts_questions = $this->PostsQuestion->find('all',
        	array('conditions' => $conditions, 'order' => $order)
        );
	    $posts_answers = $this->PostsAnswer->find('all',
        	array('conditions' => $conditions, 'order' => $order)
	    );

	    //アンケートコンテンツの生成
		$posts_enquetes = $this->PostEnquete->mergeEnqueteElements($posts_questions, $posts_answers);
	    $this->set('posts_enquetes', $posts_enquetes);
	}

	//Ajax処理を用いてアンケート項目の変更を行う
	function control_form_change(){
		
		try{
			//Ajaxリクエスト時のみ公開ステータスの変更を行う
			if($this->RequestHandler->isAjax()){
            
            	//AjaxでPOSTされた値を取得する
				$post_id            = $this->params['form']['post_id'];
				$post_question_id   = $this->params['form']['post_question_id'];
				$post_answer_id   	= $this->params['form']['post_answer_id'];
				$type   	        = $this->params['form']['type'];
				$question   	    = $this->params['form']['question'];
				$required   	    = $this->params['form']['required'];
				$answer   	        = $this->params['form']['answer'];
				
				//saveする条件を追加する
				$this->data['PostsQuestion'] = array(
            		'id'       => $post_question_id,
					'type'     => $type,
					'required' => $required,
					'question' => $question
				);
				            
				if($this->PostsQuestion->save($this->data['PostsQuestion']) !== false){
                	$this->autoRender = false;
					$this->autoLayout = false;
					
					if($type > 2){
						$this->data['PostsAnswer'] = array('id' => $post_answer_id, 'answer' => $answer);
					}else{
						$this->data['PostsAnswer'] = array('id' => $post_answer_id, 'answer' => null);						
					}
					$this->PostsAnswer->save($this->data['PostsAnswer']);
					
				}
			}
		
		} catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect("/control/posts/index");
            
        }
		
	}

	//アンケート項目のフォームサンプルを表示する
	function control_form_sample($id = null){
	    //idがなければ一覧ページへリダイレクト
	    if(!isset($id) && is_numeric($id)){
	         $this->redirect('/control/posts');
	    }
	    
	    //データを取得する
        $this->Post->id = $id;
        $this->data = $this->Post->read();
        if($this->data === false){
	    	$this->redirect('/control/posts');
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
	            'name' => 'アンケートコンテンツの一覧',
	            'link' => array('controller' => 'posts', 'action' => 'control_index')
	        ),
	        array(
	            'name' => 'サンプル表示',
	            'link' => false
	        ),
	    );
	    $this->set('breadcrumb', $breadcrumb);
	    
	    //posts_questions,posts_answersテーブルからアンケート質問事項をもってくる
	    $conditions = array('post_id' => $id);
	    $order = array('id ASC');
	    $posts_questions = $this->PostsQuestion->find('all',
        	array('conditions' => $conditions, 'order' => $order)
        );
	    $posts_answers = $this->PostsAnswer->find('all',
        	array('conditions' => $conditions, 'order' => $order)
	    );

	    //アンケートサンプルの生成
		$posts_enquetes = $this->PostEnquete->mergeEnqueteElements($posts_questions, $posts_answers);
	    $this->set('posts_enquetes', $posts_enquetes);
	    	
	}
	
	//
	function index(){
		
	}

	//
	function view(){
		
	}
	
	//
	function confirm(){
		
	}
	
	//
	function complete(){
		
	}

}
?>