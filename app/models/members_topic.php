<?php
/**
 *
 * MembersTopicモデルクラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */

//画像アップロードのライブラリインポート
App::import('Lib','ImageUpload');

class MembersTopic extends AppModel{
    
    //モデル名
    public $name = 'MembersTopic';
    
    //定数（マイページ用取得件数　デフォルト：5）
	const COUNT_MEMBER_TOPIC_NUM = 5;
    
    //定数（ディレクトリ番号　デフォルト：8）    
    const DIRECTORY_NUM = 8;
    
    //ライブラリ
    private $ImageUpload;
    
    //画像格納カラム名の配列
    private $image_array = array('member_topic_image');
    
    //private変数（画像サイズ　デフォルト：横600px,縦300px）   
    private $image_size = array(600,300);
    
    //バリデーション
    public $validate = array(
        //会員専用情報タイトルのバリデーション
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

        //会員専用情報キャッチコピーのバリデーション
        'kcpy' => array(
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
                
        //会員専用情報画像のバリデーション
        'member_topic_image' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'member_topic_image'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'member_topic_image', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'member_topic_image', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),
                
        //会員専用情報本文のバリデーション
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
        )
    );
    
    //コンストラクタ
    public function __construct(){
    	parent::__construct();
    	//ライブラリを読み込む場合はインスタンスを作成
    	$this->ImageUpload = new ImageUpload();
	}
    
    //$id(各テーブルのプライマリキー)を元にデータを取得する
    public function findByPrimaryKey($id){
        $this->id   = $id;
        $this->data = $this->read();
        return $this->data;
    }
    
    //会員専用情報を最新を取得する
    public function getNewestMemberTopic(){
        
        $conditions = array(
        	//取得条件
        	'conditions' => array(
        		'MemberTopic.flag' => COMMON_PUBLISHED
			),
			//ソート順
			'order' => 'MemberTopic.id DESC',
			//件数
			'limit' => self::COUNT_MEMBER_TOPIC_NUM			
        );
        $newestMemberTopics = $this->find('all', $conditions);
        return $newestMemberTopics;
    }
    
    //公開フラグの設定を行う
    public function changeFlagStatus($id){
            
        //ステータスを変更する
        $this->id = $id;
        $flag = $this->field('flag') == COMMON_PUBLISHED ? ADMIN_ONLY : COMMON_PUBLISHED;
        $this->saveField('flag', $flag);
        
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
		$memberstopic_picture_id = $this->getNextAutoIncrement();
        
        //配列の詰め替えを行う
        $data = $this->ImageUpload->imageFieldChange(
        	$data, 
        	$this->name, 
        	$this->image_array, 
        	$memberstopic_picture_id
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
                'fields'     => array('MembersTopic.member_topic_image')
            )
        );
        return $alreadyAddedImgName;
    }
    
    //画像フィールドのバリデーションを無効にする
    public function disableImageValidation(){
	    foreach ($this->image_array as $value){
            unset($this->validate[$value]);            
        }
    }
    
}