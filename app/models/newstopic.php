<?php
Class Newstopic extends AppModel{
    
    //モデル名
    public $name = 'Newstopic';
    
    //バリデーション
    public $validate = array(
        /* ニュース&トピックタイトルのバリデーション */
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
        
        /* ニュース&トピック画像のバリデーション */
        //TODO:バリデーションルールの策定　※画像の振り分けと処理共通ロジックの実装
        'newstopic_image' => array(
            'imageExistCheck' => array(    
                'rule' => array('imageExistCheck', 'newstopic_image'),
                'message' => 'この項目は必須項目になります',
                'last' => true,
            ),
            'imageMimeCheck' => array(
                'rule' => array('imageMimeCheck', 'newstopic_image', array('image/jpeg','image/gif','image/png')),
                'message' => 'ファイルはJPG,PNG,GIFのいずれかにして下さい',
                'last' => true,
            ),
            'imageVolumeCheck' => array(
                'rule' => array('imageVolumeCheck', 'newstopic_image', 2000000), 
                'message' => 'ファイルの容量は2M以下にして下さい',
            ),
        ),
                
        /* ニュース&トピック本文のバリデーション */
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
        
        /* ニュース&トピックリンクURLのバリデーション */
        'link_url' => array(
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

        /* ニュース&トピック公開日 */
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