<?php
Class Post extends AppModel{

	var $name = 'Post';
	
	//バリデーションの設定
	var $validate = array(
		
		/* アンケートタイトルのバリデーション */
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
		
        /* アンケート本文のバリデーション */
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
		
        /* カタログメイン画像のバリデーション */
        //TODO:バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
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

        /* 開始日 */
        'start_date' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
            ),
        ),

        /* 終了日 */
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
	
	
	//開始日＜終了日になっているかのチェック
    public function dateCorrectCheck($data){        
        $start_date = $this->data['Post']['start_date'];
        $end_date = array_shift($data);
        
        if(strtotime($start_date) < strtotime($end_date)){
            return true;
        }else{
            return false;
        }
    }	
	
}
?>