<?php
Class MembersController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Members';
    public $uses = array('Member');
    public $layout = 'common_format_blog';
    public $components = array('Session','Email','RequestHandler');
    public $helpers = array('Formhidden','Csv');
    
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'username',
            'password',
            'fullname',
            'company',
            'agree',
            'token',
            'gender',
            'status',
            'role',
            'created',
            'modified',
            ),
        'limit' => 10,
        'order' => 'Member.id DESC',
    );

    //認証関連の設定
    public function beforeFilter() {
        
        //AppControllerのbeforeFliterの呼び出し
        parent::beforeFilter();        

    }

    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }

    // ログイン処理
    public function login() {
        
    }

    // ログアウト処理
    public function logout() {
        $this->Auth->logout();
    }
    
    //会員規約に同意（ユーザー画面）
    public function agree(){
        
    }
    
    //会員情報入力（ユーザー画面）
    public function add(){
        
    }
    
    //会員情報登録確認（ユーザー画面）
    public function confirm(){
        
    }
   
    //会員情報登録完了（ユーザー画面）
    public function complete(){
        
    }
    
    //会員情報登録完了（ユーザー画面）
    public function password_remind(){
        
    }
    
    
    
    
    
    
}