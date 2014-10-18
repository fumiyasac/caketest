<?php
/**
 *
 * Showcaseモデルクラス
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

//画像アップロードのライブラリインポート
App::import('Lib','ImageUpload');

class Showcase extends AppModel{
    
    //モデル名
    public $name = 'Showcase';
    
    //定数（ディレクトリ番号　デフォルト：7）    
    const DIRECTORY_NUM = 7;
    
    //ライブラリ
    private $ImageUpload;

    //画像格納カラム名の配列
    private $image_array;
    
    private $image_array_main = array('image_main');
    private $image_array_sub  = array('image_sub1', 'image_sub2', 'image_sub3', 'image_sub4');
    
    //private変数（画像サイズ　メインデフォルト：横600px,縦400px　サブデフォルト：横600px,縦400px）   
    private $image_size_main = array(600, 400);
    private $image_size_sub  = array(600, 400);

    //バリデーション
    public $validate = array(
        
        //ショーケースタイトルのバリデーション
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
        
        //ショーケースキャッチコピーのバリデーション
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
        
        //ショーケースメイン画像のバリデーション
        //TODO:バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'image_main' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'image_main'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'image_main', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'image_main', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),
                
        //ショーケース本文のバリデーション
        'description_main' => array(
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
        
        //ショーケース画像(サブ1)のバリデーション
        //バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'image_sub1' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'image_sub1'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'image_sub1', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'image_sub1', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),
        
        //ショーケースキャプション(サブ1)のバリデーション
        'caption_sub1' => array(
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
        
        //ショーケース画像(サブ2)のバリデーション
        //バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'image_sub2' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'image_sub2'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'image_sub2', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'image_sub2', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),
        
        /* キャプション(サブ2)のバリデーション */
        'caption_sub2' => array(
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
        
        //ショーケース画像(サブ3)のバリデーション
        //バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'image_sub3' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'image_sub3'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'allowEmpty' => true,
                'rule' => array('imageMimeCheck', 'image_sub3', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'allowEmpty' => true,
                'rule' => array('imageVolumeCheck', 'image_sub3', 20000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),
        
        //ショーケースキャプション(サブ3)のバリデーション
        'caption_sub3' => array(
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

        //ショーケース画像(サブ4)のバリデーション
        //バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'image_sub4' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'image_sub3'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'allowEmpty' => true,
                'rule' => array('imageMimeCheck', 'image_sub3', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'allowEmpty' => true,
                'rule' => array('imageVolumeCheck', 'image_sub3', 20000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),
        
        //ショーケースキャプション(サブ4)のバリデーション
        'caption_sub4' => array(
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

        //自由入力欄(タイトル)のバリデーション
        'other_title' => array(
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
                
        //自由入力欄（本文）のバリデーション
        'other_description' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
                'last' => true,
            )
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
    	$this->image_array = array_merge($this->image_array_main, $this->image_array_sub);
	}
	
	//コンテンツ部分のデータ取得を行う
	public function getDetailDataById($id){
		
		$conditions = array(
			'Showcase.id'   => $id,
			'Showcase.flag' => COMMON_PUBLISHED
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
		$showcase_picture_id = $this->getNextAutoIncrement();
        
        //配列の詰め替えを行う
        $data = $this->ImageUpload->imageFieldChange(
        	$data, 
        	$this->name, 
        	$this->image_array, 
        	$showcase_picture_id
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
		$imageResultMain = $this->ImageUpload->addImageReplaceAndCrop(
			$data, 
			$this->name, 
			$this->image_array_main, 
			$this->image_size_main, 
			$ratio_flag, 
			self::DIRECTORY_NUM
		);
		
		$imageResultSub = $this->ImageUpload->addImageReplaceAndCrop(
			$data, 
			$this->name, 
			$this->image_array_sub, 
			$this->image_size_sub, 
			$ratio_flag, 
			self::DIRECTORY_NUM
		);		
		
		$imageResult = array_merge($imageResultMain + $imageResultSub);
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
                'fields'     => array(
                	'Showcase.image_main',
                	'Showcase.image_sub1',
                	'Showcase.image_sub2',
                	'Showcase.image_sub3',
                	'Showcase.image_sub4'
                )
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