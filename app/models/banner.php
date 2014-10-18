<?php
/**
 *
 * Bannerモデルクラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */

//画像アップロードのライブラリインポート
App::import('Lib','ImageUpload');

class Banner extends AppModel{
    
    //モデル名
    public $name = 'Banner';
    
    //定数（ディレクトリ番号　デフォルト：2）    
    const DIRECTORY_NUM = 2;
    
    //ライブラリ
    private $ImageUpload;
    
    //画像格納カラム名の配列
    private $image_array = array('banner_image');
    
    //private変数（画像サイズ　デフォルト：横300px,縦80px）   
    private $image_size = array(300,80);
    
    //バリデーション
    public $validate = array(
    	
        //登録バナータイトルのバリデーション
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
        
        //登録バナー本文のバリデーション
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
        
        //登録バナー画像のバリデーション
        //バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'banner_image' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'banner_image'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'banner_image', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'banner_image', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
                'last' => true,
            ),
            'imagePixelMatchCheck' => array(
                'rule' => array('imagePixelMatchCheck', 'banner_image', array(300,80)), 
                'message' => '画像サイズは横:300px, 縦:80pxにして下さい',
            ),
        ),
        
        //登録バナー本文のバリデーション
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
        
        //公開日
        'published' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
        ),
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
		$banner_picture_id = $this->getNextAutoIncrement();
        
        //配列の詰め替えを行う
        $data = $this->ImageUpload->imageFieldChange(
        	$data, 
        	$this->name, 
        	$this->image_array, 
        	$banner_picture_id
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
			self::DIRECTORY_NUM,
			true
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
                'fields'     => array('Banner.banner_image')
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

    //画像フィールドのバリデーションを無効にする
    public function disableImageValidationForEdit($data){
	    foreach ($this->image_array as $value){
	    	if(empty($data[$this->name][$value]['name'])){
            	unset($this->validate[$value]);		    	
	    	}
        }
    }
	
}