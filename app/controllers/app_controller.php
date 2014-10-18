<?php
class AppController extends Controller {
    
    public $components = array(
        'DebugKit.Toolbar',
        'Session',
        'Security',
        'Auth'
    );
    
    public function beforeFilter() {
        //自前でやるからCSRF対策とパラメータ改ざん対策を停止する
        $this->Security->validatePost = false;
        
        //認証に使用するモデルを'Member'に変更する
        $this->Auth->userModel = 'Member';
        //一度すべてのアクションに対して認証を無効にする（認証が必要なものだけをコントローラーに設定する）
        $this->Auth->allow('*');
        
        //エラーメッセージの設定
        $this->Auth->authError = "ユーザー名及びパスワード入力して下さい";
        $this->Auth->loginError = "ユーザー名またはパスワードが違います";
        
        //認証の実装
        $this->Auth->loginAction = array(
        	'controller' => 'members',
			'action'     => 'login',
			'admin'      => false
		);
        $this->Auth->fields = array(
        	'username' => 'username',
			'password' => 'password'
		);
		$this->Auth->userScope = array(
			'Member.agree'  => 1,
			'Member.status' => 0
		);
        $this->Auth->loginRedirect = '/members/mypage';
        
        //ログイン状態のユーザーを取得
        $is_login = $this->Auth->user();
        $this->set('is_login', $is_login);
        
        //管理者専用ページにはBasic認証をつける
        if (!empty($this->params['control'])) {
            $this->Security->loginOptions = array('type' => 'basic', 'realm' => 'Master Admin Tool');
            $this->Security->loginUsers = array('superuser' => 'password');
            $this->Security->requireLogin();
        }
    }
    
    //認証成功時に呼ばれる
    public function isAuthorized(){
        $this->Session->setFlash(__('ログインしています（ユーザー名：'.$this->Auth->user('username').'）', true));
        return true;
    }
    
    //管理画面時のレイアウトと分割を行う
    public function beforeRender() {
        
        //管理画面
        if(!empty($this->params['control'])){

            //CSV出力の際はレイアウトを使用しない
            if($this->params['action'] === 'control_csvdownload'){
                $this->layout = false;
            }else{
                $this->layout = 'common_format_control';
            }
        }
        
        //ログイン中かつ会員用レイアウト変更を要するアクションか否かの判定
        App::import('Lib','CommonDefine');
		$member_action_list = CommonDefine::member_page_settings();
		$target_action      = $this->params['action'];
		$target_controller  = $this->params['controller'];
        if( !empty($member_action_list[$target_controller]) && in_array($target_action, $member_action_list[$target_controller]) ){
	        $member_page_flag = true;
        }else{
	        $member_page_flag = false;
        }
        $this->set('member_page_flag', $member_page_flag);
        
    }
    
    //メール送信の共通関数
    public function _sendMail($options = array()){
        $rtn = false;
        if($options){
            
            extract($options);
            $this->Email->reset();
            $this->Email->language = 'Japanese';
            $this->Email->charset = 'UTF-8';
            $this->Email->lineLength = 1024;
            $this->Email->sendAs = 'text';
            //$this->Email->delivery = 'debug';
            $this->Email->from = $this->_mbConvertEncodingEx($from);
            $this->Email->to = $this->_mbConvertEncodingEx($to);
            $this->Email->return = $return;
            $this->Email->subject = $this->_mbConvertEncodingEx($subject);
            if ($data) {                
                $data = $this->_mbConvertEncodingEx($data);
            }
            $this->set(compact('data'));
            $this->Email->template = $this->_mbConvertEncodingEx($template);
            $this->log(compact('data'));
            $rtn = $this->Email->send();
        }
        return $rtn;
    }
    
    //文字エンコード変換の共通関数
    function _mbConvertEncodingEx($target){
        if(is_array($target)){
            foreach ($target as $key => $val){
                $target[$key] = $this->_mbConvertEncodingEx($val, $this->Email->charset, Configure::read('App.encoding'));
            }
        }else{
            $target = mb_convert_encoding($target, $this->Email->charset, Configure::read('App.encoding'));
        }
        return $target;
    }
	
}