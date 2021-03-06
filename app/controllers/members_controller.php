<?php
/**
 *
 * Membersコントローラークラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */

class MembersController extends AppController{
    
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
            'purpose',
            'status',
            'role',
            'created',
            'modified',
            ),
        'limit' => 10,
        'order' => 'Member.id DESC',
    );

    //URL遷移先のページ
    private $uri_index = '/members/index';
    private $uri_add   = '/members/add';
    private $uri_login = '/members/login';

    //認証関連の設定
    public function beforeFilter() {
        parent::beforeFilter();
        
      //ログインが必要なアクションを設定（管理画面系は除く）
	    $this->Auth->deny(
	    	'mypage'
	    );
    }

    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }

    //ログイン処理
    public function login(){
        
        //タイトルメッセージのセット
        $this->set('title_for_layout','ログイン');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => 'ログイン','link' => false),
        );
        $this->set('breadcrumb', $breadcrumb);
    }

    //ログアウト処理
    public function logout(){
        $this->Auth->logout();
        $this->redirect($this->uri_login);
    }
	
	//マイページ（会員画面）
    public function mypage(){

		//タイトルメッセージのセット
        $this->set('title_for_layout','マイページ');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => 'マイページ','link' => false)
        );
        $this->set('breadcrumb', $breadcrumb);
        
        //会員専用情報をマイページに掲載する
        $members_topics = $this->Member->getMembersTopicRecords();
        $this->set('members_topics', $members_topics);
    }

    //会員とは（ユーザー画面）
    public function index(){
        
        try{
    
            //タイトルメッセージのセット
            $this->set('title_for_layout','会員募集に関して');
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '会員募集に関して','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb);
            
        }catch(Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
    }
    
    //メンバー規約に同意（ユーザー画面）
    public function add(){
        
        try{
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','会員情報登録');
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '会員情報登録','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        }catch(Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
    }
    
    //メンバー情報登録確認（ユーザー画面）
    public function confirm(){
        try{
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','会員情報の確認');
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '会員情報の確認','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Member->set($this->data);
                
                //バリデーションチェック
                if($this->Member->validates()){
                    
                    $this->set('data', $this->data);
                    
                    //ビューのレンダリング
                    $this->render('confirm');
                    
                }else{
                    
                    //前のページのタイトルを追加                    
                    $this->set('title_for_layout','会員情報登録');
                    $breadcrumb = array(
                        array('name' => 'HOME', 'link' => '/'),
                        array('name' => '会員情報登録','link' => false)
                    );
                    $this->set('breadcrumb', $breadcrumb);
                    $this->set('error_announce', ERROR_ANNOUNCE_VALIDATE);
                    
                    //ビューのレンダリング
                    $this->render('add');
                }
                
            }else{
                
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__(ERROR_ANNOUNCE_ILLIGAL_ACCESS, true));
            }
            
        }catch(Exception $e){
        	
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_add);
        }
    }
   
    //メンバー情報登録完了（ユーザー画面）
    public function complete(){
        
        try{
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','会員情報仮登録完了');
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '会員仮登録完了','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){
                
                //生成したトークン/権限:2を追加する
                $this->data['Member']['token'] = $this->Member->createTokenForSite();
                $this->data['Member']['role']  = $this->Member->default_role;
                
                //取得データをDBへ保存する。
                if($this->Member->saveMemberInfo($this->data['Member']) !== false){
                    
                    //プロフィールのデフォルト値を設定しておく
                    $new_member_id = $this->Member->getLastInsertId();
                    $this->Member->saveProfileFormat($new_member_id);
                    
                    $options = Configure::read('MAIL_CONF.custmor');
                    $options['to']   = $this->data['Member']['mail'];
                    $options['data'] = $this->data;

                    if ($this->_sendMail($options)) {
                        $options = Set::merge($options, Configure::read('MAIL_CONF.admin'));
                        $this->_sendMail($options);
                        //OK（DB登録・メール処理ともに成功）
                        $this->set('title_for_layout','会員仮登録完了');
                        $this->set('form_description','会員仮登録完了が正常に完了しました。弊社より返信メールを送りました。この度はありがとうございました。' );
                        $this->set('complete_link',0);                                

                    }else{
                        //NG（DB登録のみ成功）
                        $this->log('Cannot Send Administrator.');
                        $this->log($this->data);
                        
                        $this->set('title_for_layout','会員仮登録完了');
                        $this->set('form_description','会員仮登録は完了しましたが、弊社より返信メールが正しく送信できなかった可能性があります。' );
                        $this->set('complete_link', 1);  
                    }

                    //ビューのレンダリング
                    $this->render('complete');
                    
                }else{
                    $this->flash("エラーが発生しました。\nお手数ではありますが再度入力をお願いします。",array('controller' => 'members', 'aciton' => 'add')); 
                }
                
            }else{
                
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__('不正アクセスが行われた可能性があります', true));
                
            }
            
        }catch(ErrorException $e){
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_add);
        }
    }
    
    //トークン認証完了
    public function register($token = null){
        
        //タイトルメッセージのセット
        $this->set('title_for_layout','会員登録完了');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => '会員登録完了','link' => false)
        );
        $this->set('breadcrumb', $breadcrumb);
        
        //トークンの妥当性の検証を行う(検証結果を返す)
        $registFlag = $this->Member->checkTokenForParam($token);
        $this->set('register_flag', $registFlag);      
        
        //ビューのレンダリング
        $this->render('register');
    }
    
}