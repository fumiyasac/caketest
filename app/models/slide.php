<?php
Class Slide extends AppModel{
    
    //モデル名
    public $name = 'Slide';
    
    //バリデーション
    public $validate = array(
        /* スライドショー画像タイトルのバリデーション */
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
        
        /* スライドショー画像本文のバリデーション */
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
        
         /* スライドショー画像のバリデーション */
        //TODO:バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'slide_image' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'slide_image'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'slide_image', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'slide_image', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
                'last' => true,
            ),
        ),
        
        /* スライドショー画像本文のバリデーション */
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