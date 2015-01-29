<?php
/**
 *
 * Postsコントローラークラス
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

class PostsController extends AppController{

	//メンバ変数の設定
	var $name = 'Posts';
    public $uses = array('Post');
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler','PostEnqueteAccess');
    public $helpers = array('Formhidden','Csv','Html','Dateform','MakeEnquete','DisplayImage');
    
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
	
		//URL遷移先のページ
    private $uri_index         = '/posts/index';
    private $uri_control_index = '/control/posts/index';
    private $uri_control_add   = '/control/posts/add';
    private $uri_control_edit  = '/control/posts/edit';
	
    //認証関連の設定
    public function beforeFilter() {
       parent::beforeFilter();
    }
    
    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
    
    //アンケートマスタTOP（管理画面）
    public function control_index(){
                
        //パンくずリストの設定
        $breadcrumb = array(
            array('name' => '管理画面TOP','link' => false),
            array('name' => 'アンケートコンテンツの一覧','link' => false)
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
            $this->redirect($this->uri_control_index);
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            //レイアウトを使用しない
            $this->autoRender = false;
            $this->autoLayout = false;
            
			//レスポンスを取得する
			$response = $this->Post->changeFlagStatus($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }

    //アンケートマスタ削除（管理画面）
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
			$response = $this->Post->deleteImageAndDataById($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }

    //アンケートマスタ閲覧（管理画面）
    public function control_view($id = null){
    	
        try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_control_index);
            }
            
            $this->data = $this->Post->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => $this->data['Post']['title'],'link' => false)
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
    
    //アンケートマスタ追加（管理画面）
    public function control_add(){
        
        try {
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => '管理画面TOP','link' => false),
                array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => 'アンケートコンテンツの追加','link' => false)
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
    
    //アンケートマスタ追加確認（管理画面）
    public function control_add_confirm(){
        
        try{
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Post->set($this->data);
                
                //バリデーションチェック
                if($this->Post->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Post->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
					
                    //変数をセット
                    $this->set('data', $this->data);
                    
                     //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
		                array('name' => 'アンケートコンテンツ追加内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('control_add_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
                        array('name' => 'アンケートコンテンツの追加','link' => false)
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
                array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => 'アンケートコンテンツ追加完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
            	
                //画像アップロード前の準備を行う
                $this->data = $this->Post->beforeUploadImageAdd($this->data);
                
                //取得データをDBへ保存する
                if($this->Post->save($this->data['Post']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Post->getSaveImageResult($this->data, false);
                    $this->set('saveImageResult', $saveImageResult);
                    
                    //5件のデータをposts_questionsにレコードを追加しておく
                    $last_post_id = $this->Post->getLastInsertID();
					$this->Post->makePostQuestionsData($last_post_id);
                    
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
                array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => 'アンケートコンテンツの編集','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //データを取得する
            $this->data = $this->Post->findByPrimaryKey($id);
            if($this->data === false){
                $this->redirect($this->uri_control_index);
            }
            
            //一時画像ファイルの削除
            $this->Post->deleteTmpImageById($this->data);
            
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
            if(!isset($id) && is_numeric($id) && $data['Post']['id']){
                 $this->redirect($this->uri_control_index);
            }
            
            //既に登録されている元画像名を抽出
            $alreadyAddedImgName = $this->Post->getAlreadyImageName($id);
            $this->set('alreadyAddedImgName', $alreadyAddedImgName);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //一部バリデーションを無効にする
                $this->Post->disableImageValidation();
                
                //変数に値をセット
                $this->Post->set($this->data);
                
                //バリデーションチェック
                if($this->Post->validates()){
                    
                    //画像を一時保存場所へアップロードする
                    $saveTmpImageResult = $this->Post->getSaveTmpImageResult();
                    $this->set('saveTmpImageResult',$saveTmpImageResult); 
                   
                    //変数をセット
                    $this->set('data', $this->data);
                    
                    //パンくずリストの設定
		            $breadcrumb = array(
		                array('name' => '管理画面TOP','link' => false),
		                array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
		                array('name' => 'アンケートコンテンツ編集内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('control_edit_confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加
                    $breadcrumb = array(
                        array('name' => '管理画面TOP','link' => false),
                        array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
                        array('name' => 'アンケートコンテンツの編集','link' => false)
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
                array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
                array('name' => 'アンケートコンテンツ編集完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //フィールドへ格納する為の値を作成
            $alreadyAddedImgName = $this->Post->getAlreadyImageName($id);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
            	
                //画像アップロード前の準備を行う
                $this->data = $this->Post->beforeUploadImageEdit($this->data, $id, $alreadyAddedImgName);
                
                //取得データをDBへ保存する
                if($this->Post->save($this->data['Post']) !== false){
                    
                    //画像の移動と切り取りを行い画像処理結果を出力する
                    $saveImageResult = $this->Post->getSaveImageResult($this->data, false);
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
        
        $filename     = 'アンケート'.date('Ymd');
        $headRow      = array('ID','タイトル','画像','内容','開始日','終了日','公開フラグ');
        $contentsRows = $this->Post->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }


	//Ajax処理を用いてアンケート項目のフォームを表示する
	function control_form_edit($id = null){
	
	    //idがなければ一覧ページへリダイレクト
	    if(!isset($id) && is_numeric($id)){
	         $this->redirect($this->uri_control_index);
	    }
	    
	    //データを取得する
        $this->data = $this->Post->findByPrimaryKey($id);
        if($this->data === false){
        	$this->redirect($this->uri_control_index);
        }

        //変数をセット
        $this->set('data', $this->data);
	    
	    //パンくずリストの設定
	    $breadcrumb = array(
	        array('name' => '管理画面TOP','link' => false),
	        array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
	        array('name' => 'アンケートコンテンツの編集','link' => false)
	    );
	    $this->set('breadcrumb', $breadcrumb);
	    
	    //posts_questions,posts_answersテーブルからアンケート質問事項をもってくる
	    $posts_questions = $this->Post->getPostsQuestionByPostId($id);
	    $posts_answers   = $this->Post->getPostsAnswerByPostId($id);

	    //アンケートコンテンツの生成
		$posts_enquetes = $this->PostEnqueteAccess->mergeEnqueteElements($posts_questions, $posts_answers);
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
				
				//フォームの項目を変更する
				if($this->Post->savePostsQuestionRecord($this->data['PostsQuestion']) !== false){
                	
                	//レイアウトを使用しない
                	$this->autoRender = false;
					$this->autoLayout = false;
					
					//ラジオボタン or チェックボックスの際はPostsAnswerテーブルへ値を格納
					if($type > 2){
						$this->data['PostsAnswer'] = array('id' => $post_answer_id, 'answer' => $answer);
					}else{
						$this->data['PostsAnswer'] = array('id' => $post_answer_id, 'answer' => null);						
					}
					$this->Post->savePostsAnswerRecord($this->data['PostsAnswer']);
				}
			}
		
		} catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_control_index);
        }
	}

	//アンケート項目のフォームサンプルを表示する
	function control_form_sample($id = null){

	    //idがなければ一覧ページへリダイレクト
	    if(!isset($id) && is_numeric($id)){
	         $this->redirect($this->uri_control_index);
	    }
	    
	    //データを取得する
        $this->data = $this->Post->findByPrimaryKey($id);
        if($this->data === false){
        	$this->redirect($this->uri_control_index);
        }

        //変数をセット
        $this->set('data', $this->data);
	    
	    //パンくずリストの設定
	    $breadcrumb = array(
	        array('name' => '管理画面TOP','link' => false),
	        array('name' => 'アンケートコンテンツの一覧','link' => $this->uri_control_index),
	        array('name' => 'サンプル表示','link' => false)
	    );
	    $this->set('breadcrumb', $breadcrumb);
	    
	    //posts_questions,posts_answersテーブルからアンケート質問事項をもってくる
	    $posts_questions = $this->Post->getPostsQuestionByPostId($id);
	    $posts_answers   = $this->Post->getPostsAnswerByPostId($id);

	    //アンケートサンプルの生成
		$posts_enquetes = $this->PostEnqueteAccess->mergeEnqueteElements($posts_questions, $posts_answers);
	    $this->set('posts_enquetes', $posts_enquetes);
	}
	
	//アンケート一覧表示
	function index(){
		//タイトルメッセージのセット
        $this->set('title_for_layout','アンケート一覧');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => 'アンケート一覧','link' => false),
        );
        $this->set('breadcrumb', $breadcrumb);
        
        //start_dateとend_dateではさみうち用に現在時刻を設定
        $now = date('Y-m-d',strtotime('now'));
        $condition = array(
        	'Post.flag'          => COMMON_PUBLISHED,
            'Post.start_date <=' => $now,
            'Post.end_date >='   => $now
        );
                
        if(isset($this->params['requested'])){
            $posts = $this->paginate('Post', $condition);
            return $posts;
        }else{
            //ページングのリミットを10にする
            $this->paginate['limit'] = 10;
        
            //newstopicsテーブルからデータを持ってくる
            $posts = $this->paginate('Post', $condition);
            $this->set('posts', $posts);
            
        }
        //ビューのレンダリング
        $this->render('index');
	}

	//アンケート一覧
	function view($id = null){
		
		try {
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_index);
            }
            
            //データを取得する
            $this->data = $this->Post->getDetailDataById($id);
            if($this->data === false){
                $this->redirect($this->uri_index);
            }

            //変数をセット
            $this->set('data', $this->data);
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','アンケート（'.$this->data['Post']['title'].'）');
        
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'アンケート一覧', 'link' => $this->uri_index),
                array('name' => 'アンケート（'.$this->data['Post']['title'].'）','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb); 

            //トークンの生成
            $this->Session->write('token', String::uuid());
            
            //posts_questions,posts_answersテーブルからアンケート質問事項をもってくる
		    $posts_questions = $this->Post->getPostsQuestionByPostId($id);
		    $posts_answers   = $this->Post->getPostsAnswerByPostId($id);
	
		    //アンケートサンプルの生成
			$posts_enquetes = $this->PostEnqueteAccess->mergeEnqueteElements($posts_questions, $posts_answers);
		    $this->set('posts_enquetes', $posts_enquetes);
            $this->set('post_data', array());
            
        } catch (Exception $e) {
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
	}
	
	//アンケート内容確認
	function confirm(){
		
		//フォームでPOSTされた値を取得する
		$post_data = $this->params['form'];
		$id 	   = $this->params['form']['post_id'];
		$username  = $this->params['form']['username'];
		
		try{			
			//idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_index);
            }
			
			if(!empty($post_data) && $this->Session->check('token')){
				
				$error_msg_array = $this->PostEnqueteAccess->enqueteValidate($post_data);

				//バリデーションチェック				
                if( empty($error_msg_array) ){
					
					$post_data = $this->PostEnqueteAccess->enqueteMigrateForHidden($post_data);
					$post_data = $this->PostEnqueteAccess->mergeElementsForConfirm($post_data);
                    $this->set('post_data', $post_data);
					
                    $base_data = $this->Post->getDetailDataById($id);
                    $this->set('base_data', $base_data);
                    $this->set('post_id'  , $id);
                    $this->set('username',  $username);
                    
                    //タイトルメッセージのセット
		            $this->set('title_for_layout','アンケート内容の確認');
		            $breadcrumb = array(
		                array('name' => 'HOME', 'link' => '/'),
		                array('name' => 'アンケート一覧', 'link' => $this->uri_index),
		                array('name' => 'アンケート内容の確認','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb);
                                        
                    //ビューのレンダリング
                    $this->render('confirm');
                    
                }else{
                    
                    //エラーメッセージを表示する
                    $this->set('error_announce', ERROR_ANNOUNCE_VALIDATE);
                    $this->set('error_msg_array',$error_msg_array);                    
                    
                    //postしたデータ配列を取得する
                    $this->set('post_data', $post_data);
                    		            
		            //データを取得する
		            $this->data = $this->Post->getDetailDataById($id);
		            if($this->data === false){
		                $this->redirect($this->uri_index);
		            }

		            //変数をセット
		            $this->set('data', $this->data);

		            //タイトルメッセージのセット
		            $this->set('title_for_layout','アンケート（'.$this->data['Post']['title'].'）');
		        
		            //パンくずリストの設定 
		            $breadcrumb = array(
		                array('name' => 'HOME', 'link' => '/'),
		                array('name' => 'アンケート一覧', 'link' => $this->uri_index),
		                array('name' => 'アンケート（'.$this->data['Post']['title'].'）','link' => false)
		            );
		            $this->set('breadcrumb', $breadcrumb); 

		            //posts_questions,posts_answersテーブルからアンケート質問事項をもってくる
					$posts_questions = $this->Post->getPostsQuestionByPostId($id);
					$posts_answers   = $this->Post->getPostsAnswerByPostId($id);
			
				    //アンケートサンプルの生成
					$posts_enquetes = $this->PostEnqueteAccess->mergeEnqueteElements($posts_questions, $posts_answers);
				    $this->set('posts_enquetes', $posts_enquetes);

                    //ビューのレンダリング
                    $this->render('view');
                }
				
				
			}else{
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__(ERROR_ANNOUNCE_ILLIGAL_ACCESS, true));
			}
			
		}catch(Exception $e){
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
		}
	}
	
	//アンケート回答完了
	function complete(){

        //postされたデータを受け取る
        $id = $this->params['form']['post_id'];            
        $this->data['PostsEnquete'] = $this->params['form'];

        try{
            
            //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_index);
            }
            
            //パンくずリストの設定
            $this->set('title_for_layout','アンケートの回答完了');
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'アンケート一覧', 'link' => $this->uri_index),
                array('name' => 'アンケートの回答完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                            
                //取得データをDBへ保存する
                if($this->Post->savePostsEnqueteRecord($this->data['PostsEnquete']) !== false){
                    
                    $base_data = $this->Post->getDetailDataById($id);
                    $this->set('base_data', $base_data);
                    $this->set('form_description', ENQUETE_SUCCESS);
                        
                    //ビューのレンダリング
                    $this->render('complete');

                }else{
                    $this->flash("エラーが発生しました。\nお手数ではありますが再度入力をお願いします。",array('controller' => 'posts', 'aciton' => 'index')); 
                }
                
                //トークンの消去(CSRF対策)
                $this->Session->destroy();
                
            }else{
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__(ERROR_ANNOUNCE_ILLIGAL_ACCESS, true));                                    
            }
            
        } catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
	}

}