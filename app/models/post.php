<?php
/**
 *
 * Postモデルクラス
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

//画像アップロードのライブラリインポート
App::import('Lib','ImageUpload');

//モデルクラスのインポート
App::Import('Model','PostsAnswer');
App::Import('Model','PostsQuestion');
App::Import('Model','PostsEnquete');

class Post extends AppModel{

	var $name = 'Post';
	
	//定数（ディレクトリ番号　デフォルト：6）    
    const DIRECTORY_NUM = 6;
	
	//定数（アンケートの質問個数　デフォルト：5）
	const POSTS_ANSWER_AMOUNT = 5;
	
	//ライブラリ
    private $ImageUpload;
	
	//インポートしたモデル
    private $PostsAnswer;
    private $PostsQuestion;
    private $PostsEnquete;
    
    //画像格納カラム名の配列
    private $image_array = array("post_image");
    
    //private変数（画像サイズ　デフォルト：横600px,縦200px）   
    private $image_size = array(600,200);
    
    //アンケートマスタ追加時のデフォルト値
    private $default_question = array(
    	'required' => 1,
    	'type'     => 1,
    	'question' => "アンケートの質問事項を入力して下さい",
    	'flag'     => ADMIN_ONLY
    );
    
	//バリデーションの設定
	var $validate = array(
		
		//アンケートタイトルのバリデーション
        'title' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 256), 
                'message' => 'この項目は256文字以下で入力して下さい',
            ),
        ),
		
        //アンケート本文のバリデーション
        'description' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 1000), 
                'message' => 'この項目は1000文字以下で入力して下さい',
            ),
        ),		
		
        //カタログメイン画像のバリデーション
        //バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'post_image' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'post_image'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'post_image', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'post_image', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),

        //開始日
        'start_date' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
            ),
        ),

        //終了日
        'end_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'correctCheck' => array(            
            	'rule' => array('dateCorrectCheck'),
				'message' => '終了日の設定を開始日より後にして下さい',
			),            
        ),
	);
	
    //コンストラクタ
    public function __construct(){
    	parent::__construct();
    	//ライブラリを読み込む場合はインスタンスを作成
    	$this->ImageUpload = new ImageUpload();
    	
    	//インポートモデルを読み込む場合はインスタンスを作成
    	$this->PostsAnswer   = new PostsAnswer();
    	$this->PostsQuestion = new PostsQuestion();
    	$this->PostsEnquete  = new PostsEnquete();
	}
		
	//バリデーション：開始日＜終了日になっているかのチェック
    public function dateCorrectCheck($data){        
        $start_date = $this->data['Post']['start_date'];
        $end_date = array_shift($data);
        
        if(strtotime($start_date) < strtotime($end_date)){
            return true;
        }else{
            return false;
        }
    }
	
    //コンテンツ部分のデータ取得を行う
	public function getDetailDataById($id){
		
		//start_dateとend_dateではさみうち用に現在時刻を設定
		$now = date('Y-m-d',strtotime('now'));
		$conditions = array(
			'Post.id'            => $id,
			'Post.flag'          => COMMON_PUBLISHED,
			'Post.start_date <=' => $now,
            'Post.end_date >='   => $now
		);
		
		$detail = $this->find('first',
            array('conditions' => $conditions)
        );
        return $detail;
	}
    
    //$id(各テーブルのプライマリキー)を元にデータを取得する
    public function findByPrimaryKey($id){
        $this->id   = $id;
        $this->data = $this->read();
        return $this->data;
    }
    
    //公開フラグの設定を行う
    public function changeFlagStatus($id){
            
        //ステータスを変更する
        $this->id = $id;
        $flag = $this->field('flag') == COMMON_PUBLISHED ? ADMIN_ONLY : COMMON_PUBLISHED;
        
        if($this->saveField('flag', $flag)){
        	
        	//Postに紐づくモデルのステータスも変更する
            $this->PostsQuestion->updateStatusByPostId($id, $flag);
            $this->PostsAnswer->updateStatusByPostId($id, $flag);
        }
                
        //変更したステータスの取得
        $response = array('id' => $id, 'flagStatus' => Configure::read("FLAG_CONF.flag.{$flag}"));
		return $response;
    }

    //一時保存ディレクトリ内に画像データをアップロードする
    public function getSaveTmpImageResult(){
				
		$tmpImageResult = $this->ImageUpload->loopAndGenerateImages(
			$this->data,
			$this->name, 
			$this->image_array, 
			self::DIRECTORY_NUM
		);		
		return $tmpImageResult;
    }
    
    //画像アップロード用の値を作成する（追加用）
    public function beforeUploadImageAdd($data){
				
    	//一部バリデーションを無効にする
    	$this->disableImageValidation();
        
        //次のIDを取得する
		$post_picture_id = $this->getNextAutoIncrement();
        
        //配列の詰め替えを行う
        $data = $this->ImageUpload->imageFieldChange(
        	$data, 
        	$this->name, 
        	$this->image_array, 
        	$post_picture_id
        );
        return $data;
    }

    //画像アップロード用の値を作成する（編集用）
    public function beforeUploadImageEdit($data, $id, $alreadyAddedImgName){
				
    	//一部バリデーションを無効にする
    	$this->disableImageValidation();
                
        //配列の詰め替えを行う
        $data = $this->ImageUpload->imageFieldChangeForEditComplete(
        	$data, 
        	$this->name, 
        	$this->image_array, 
        	$id,
        	$alreadyAddedImgName
        );
        return $data;
    }
    
    //正規保存ディレクトリ内に画像データをアップロードする
    public function getSaveImageResult($data, $ratio_flag){
		
		//画像の切り取り＋ディレクトリ移動を行う
		$imageResult = $this->ImageUpload->addImageReplaceAndCrop(
			$data, 
			$this->name, 
			$this->image_array, 
			$this->image_size, 
			$ratio_flag, 
			self::DIRECTORY_NUM
		);
		return $imageResult;
    }
    
    //データを物理削除する
    public function deleteImageAndDataById($id){

        //既に登録されている元画像名を抽出
        $alreadyAddedImgName = $this->getAlreadyImageName($id);
        
        //削除処理
        if($this->delete($id)){
                
            //画像ファイルの削除
            $this->ImageUpload->deleteImage(
            	$this->name,
            	$alreadyAddedImgName, 
            	self::DIRECTORY_NUM
            );
            
            $this->PostsQuestion->deleteDataByPostId($id);
            $this->PostsAnswer->deleteDataByPostId($id);
        }

        //全ての件数の取得
        $allAmount = count($this->find('all'));
		
        //変更したステータスの取得
        $response = array('id' => $id, 'allAmount' => $allAmount);
		return $response;
    }
    
    //一時ファイルを削除する
    public function deleteTmpImageById($data){

        $this->ImageUpload->deleteTmpImage(
        	$data,
        	$this->name,
            $this->image_array, 
            self::DIRECTORY_NUM
        );
    }
    
    //既に登録されている元画像名を抽出
    public function getAlreadyImageName($id){
	    
	    $alreadyAddedImgName = $this->find('first',
            array(
            	'conditions' => array('id' => $id),
                'fields'     => array('Post.post_image')
            )
        );
        return $alreadyAddedImgName;
    }
    
    //PostsQuestionの値を保存（更新）する
    public function savePostsQuestionRecord($data){
	    $this->PostsQuestion->save($data);
    }
    
    //PostsAnswerの値を保存（更新）する    
    public function savePostsAnswerRecord($data){
	    $this->PostsAnswer->save($data);
    }

    //PostsEnqueteの値を保存する    
    public function savePostsEnqueteRecord($data){
	    $this->PostsEnquete->save($data);
    }

    //画像フィールドのバリデーションを無効にする
    public function disableImageValidation(){
	    foreach ($this->image_array as $value){
            unset($this->validate[$value]);            
        }
    }

    //$post_idに紐づくPostsQuestionの値を取得する
    public function getPostsQuestionByPostId($id){
	    
	    $conditions = array('post_id' => $id);
	    $order      = array('id ASC');
	    
	    $posts_questions = $this->PostsQuestion->find('all',
        	array(
        		'conditions' => $conditions, 
        		'order'      => $order
        	)
        );
        return $posts_questions;
    }
    
    //$post_idに紐づくPostsAnswerの値を取得する    
    public function getPostsAnswerByPostId($id){
    	
	    $conditions = array('post_id' => $id);
	    $order      = array('id ASC');
	    
	    $posts_answers = $this->PostsAnswer->find('all',
        	array(
        		'conditions' => $conditions, 
        		'order'      => $order
        	)
        );
        return $posts_answers;	    
    }
	    
    //アンケートマスタをインサートしたタイミングで5件のデータをposts_questionsにレコードを追加しておく
	public function makePostQuestionsData($last_post_id){
		
		//posts_questions,posts_answerに格納するデータを作成する
		$data_question_array = array();
        $data_answer_array   = array();
		
		for($i=0; $i<self::POSTS_ANSWER_AMOUNT; $i++){
		
			//質問用データ登録
			$data_question_array['PostsQuestion']            = $this->default_question;
			$data_question_array['PostsQuestion']['post_id'] = $last_post_id;
			$data_question_array['PostsQuestion']['id']      = $this->PostsQuestion->getNextAutoIncrement();
			$this->PostsQuestion->save($data_question_array);
			
			//選択肢用データ登録
			$data_answer_array['PostsAnswer']['flag']             = ADMIN_ONLY;
			$data_answer_array['PostsAnswer']['post_question_id'] = $this->PostsQuestion->getLastInsertID();
			$data_answer_array['PostsAnswer']['post_id']          = $last_post_id;
			$data_answer_array['PostsAnswer']['id']               = $this->PostsAnswer->getNextAutoIncrement();
			$this->PostsAnswer->save($data_answer_array);						
		}
	}
	
}