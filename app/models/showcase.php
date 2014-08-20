<?php
Class Showcase extends AppModel{
    
    //モデル名
    public $name = 'Showcase';
    
    //バリデーション
    public $validate = array(
        /* 特集記事タイトルのバリデーション */
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
        
        /* 特集記事キャッチコピーのバリデーション */
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
        
        /* 特集記事メイン画像のバリデーション */
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
                
        /* 特集記事本文のバリデーション */
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
        
        /* 画像(サブ1)のバリデーション */
        //TODO:バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
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
        
        /* キャプション(サブ1)のバリデーション */
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
        
        /* 画像(サブ2)のバリデーション */
        //TODO:バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
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
        
        /* 画像(サブ3)のバリデーション */
        //TODO:バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
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
        
        /* キャプション(サブ3)のバリデーション */
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

        /* 画像(サブ4)のバリデーション */
        //TODO:バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
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
        
        /* キャプション(サブ4)のバリデーション */
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

        /* 自由入力欄(タイトル)のバリデーション */
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
                
        /* 自由入力欄（本文）のバリデーション */
        'other_description' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
                'last' => true,
            )
        ),        

        /* 公開日 */
        'published' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
        ),
        
        
    );
    
}
?>