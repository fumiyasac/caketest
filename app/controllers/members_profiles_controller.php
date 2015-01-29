<?php
/**
 *
 * MembersProfilesコントローラークラス
 * Date:    2014/10/25
 * Created: Fumiya Sakai
 *
 */

class MembersProfilesController extends AppController{
    
    //メンバ変数の設定
    public $name = 'MembersProfiles';
    public $uses = array('MembersProfile');
    public $layout = 'common_format_blog';
    public $components = array('Session','Email','RequestHandler','Auth');
    public $helpers = array('Formhidden','Csv','Html','Dateform','DisplayImage');
    
    //ログイン状態の管理
    private $member_login_id;
    
    //デフォルト設定
    public $paginate = array(
      'page' => 1,
        'conditions' => array(),
          'fields' => array(
            'id',
            'member_id',
            'filename',
            'link_url',
            'description',
            'flag',
            'created',
            'modified',
          ),
        'limit' => 10,
        'order' => 'MembersProfile.id DESC',
    );
    
    //URL遷移先のページ
    private $uri_index  = '/members_profiles/index';
    private $uri_edit   = '/members_profiles/edit';
    private $uri_mypage = '/members/mypage';
		
    //認証関連の設定
    public function beforeFilter() {
      parent::beforeFilter();
      
      //ログイン状態を取得
      $this->member_login_id = $this->_getLoginInfo($this->Session->read('Auth'));
      
      //ログインが必要なアクションを設定（管理画面系は除く）
	    $this->Auth->deny(
	    	'index',
	    	'edit',
	    	'edit_confirm',
	    	'edit_complete'
	    );
	}

    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
    
    //マイプロフィール（会員画面）
    public function index(){
				
		//Authコンポーネントからメンバー情報を取得する
        $member_id = $this->member_login_id;
            
        //member_idがなければ一覧ページへリダイレクト
        if(!isset($member_id) && is_numeric($member_id)){
            $this->redirect($this->uri_mypage);
        }

		//タイトルメッセージのセット
        $this->set('title_for_layout','マイプロフィール');
        $breadcrumb = array(
            array('name' => 'HOME', 'link' => '/'),
            array('name' => 'マイプロフィール','link' => false)
        );
        $this->set('breadcrumb', $breadcrumb);
        
        //メンバーのプロフィール情報を取得
        $this->data = $this->MembersProfile->getProfileByMemberId($member_id);
        
        //変数をセット
        $this->set('data', $this->data);
        
        //ビューのレンダリング
        $this->render('index'); 
    }
    
    //現在ログイン中のユーザーIDを取得する
    private function _getLoginInfo($userInfo = array()){
	      
		if(!empty($userInfo) && isset($userInfo)){
			//ユーザー情報が格納されている場合はmember_idを返す
			return $userInfo['Member']['id'];
	    }else{
			//ユーザー情報が格納されていない場合はfalseを返す
			return false;
		}
    }
    
}