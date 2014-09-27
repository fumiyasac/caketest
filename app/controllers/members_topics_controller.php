<?php
Class MembersTopicsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'MembersTopics';
    public $uses = array('MembersTopic');
    public $layout = 'common_format_blog';
    public $components = array('Session','Email','RequestHandler');
    public $helpers = array('Formhidden','Csv');
    
    //画像格納カラム名の配列
    private $image_array = array("member_topic_image");
    
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'title',
            'description',
            'member_topic_image',
            'published',
            'flag',
            'created',
            'modified',
            ),
        'limit' => 10,
        'order' => 'MembersTopic.id DESC',
    );

    //認証関連の設定
    public function beforeFilter() {
        parent::beforeFilter();       
    }

    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
    
    
    
}