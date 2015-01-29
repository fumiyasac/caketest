<?php
/**
 *
 * MembersProfileモデルクラス
 * ※後々の機能拡張でさらに変更の可能性あり
 * Date:    2014/10/25
 * Created: Fumiya Sakai
 *
 */

//画像アップロードのライブラリインポート
App::import('Lib','ImageUpload');

class MembersProfile extends AppModel{
    
    //モデル名
    public $name = 'MembersProfile';
    
    //定数（ディレクトリ番号　デフォルト：9）    
    const DIRECTORY_NUM = 9;
    
    //定数（デフォルトの画像名）    
    const DEFAULT_FILENAME = "noimage.gif";
    
    //ライブラリ
    private $ImageUpload;
    
    //画像格納カラム名の配列
    private $image_array = array('filename');
    
    //private変数（画像サイズ　デフォルト：横150px,縦150px）   
    private $image_size = array(150,150);
    
    //バリデーション
    public $validate = array(
        
        //会員プロフィール画像のバリデーション
        'filename' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'filename'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'filename', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'filename', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),
        
        //会員プロフィールURLのバリデーション
        'link_url' => array(
	        'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'url' => array(
                'rule' => array('url', true),
                'message' => 'この項目はURL形式で入力して下さい',
                'last' => true,
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 1000), 
                'message' => 'この項目は1000文字以下で入力して下さい',
            ),
        ),
        
        //会員プロフィール本文のバリデーション
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
    
    //プロフィール部分のデータ取得を行う
	  public function getProfileByMemberId($member_id){

        $conditions = array(
        	//取得条件
        	'conditions' => array(
        		'MembersProfile.member_id' => $member_id
			    )
		    );
        $member_profile = $this->find('first', $conditions);
        return $member_profile;
	  }
	
    //新規会員仮登録時に、デフォルトのプロフィールデータを登録する
    public function saveInitialProfileByMemberId($member_id){
    	
    	//デフォルトのプロフィール値の配列を作成
    	$data_initial_array = array();
    	$data_initial_array[$this->name] = array(
    		'id'          => $this->getNextAutoIncrement(),
    		'member_id'   => $member_id,
    		'filename'    => 'no_image.gif',
    		'link_url'    => '',
    		'description' => '',
    		'flag'        => COMMON_PUBLISHED
    	);
		//データをinsertする
		$this->save($data_initial_array);
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
		$membersprofile_picture_id = $this->getNextAutoIncrement();
        
        //配列の詰め替えを行う
        $data = $this->ImageUpload->imageFieldChange(
        	$data, 
        	$this->name, 
        	$this->image_array, 
        	$membersprofile_picture_id
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
                'fields'     => array('MembersProfile.filename')
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