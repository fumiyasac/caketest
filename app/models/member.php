<?php
/**
 *
 * Memberモデルクラス
 * Date:    2014/10/20
 * Created: Fumiya Sakai
 *
 */

//モデルクラスのインポート
App::Import('Model','MembersTopic');
App::Import('Model','MembersProfile');

class Member extends AppModel{
    
    //モデル名
    public $name = 'Member';

	//インポートしたモデル
    private $MembersTopic;
    
    //private変数（会員規約同意フラグ　1:同意済）
	private $is_agree_flag     = 1;

    //private変数（仮会員認証用トークン長　デフォルト：16）
	private $auth_token_length = 16;
	
    //public変数（仮会員権限フラグ　2:一般ユーザー）
    public $default_role 	     = 2;
    
    //public変数（ステータスフラグ 0:メール認証未対応,1:メール認証対応済）
    public $mail_auth_yet      = 0;
    public $mail_auth_already  = 1;

    //バリデーション
    public $validate = array(
        //ユーザー名
        'username' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'ユーザー名は必須項目になります',
                'last' => true,
            ),
            'betweenCheck' => array(
                'rule' => array('between', 8, 20),
                'message' => 'ユーザー名は半角英数字で8～20文字以内です',
                'last' => true,
            ),
            'strCheck' => array(
                'rule' => 'alphaNumeric',
                'message' => '半角英数字以外は入力できません',
                'last' => true,
            ),
            'uniqueCheck' => array(
                'rule' => 'isUnique',
                'message' => 'このユーザー名は既に登録されています',
            ),
        ),
        //パスワード
        'pass' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'パスワードは必須項目になります',
                'last' => true,
            ),
            'betweenCheck' => array(
                'rule' => array('minLength', 8),
                'message' => 'パスワードは半角英数字で8文字以上です',
                'last' => true,
            ),
            'strCheck' => array(
                'rule' => 'alphaNumeric',
                'message' => '半角英数字以外は入力できません',
                'last' => true,
            ),
        ),
        //お名前
        'fullname' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'お名前は必須項目になります',
            ),
        ),
        //メールアドレス
        'mail' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'メールアドレスは必須項目になります',
                'last' => true,
            ),
            'uniqueCheck' => array(
                'rule' => 'isUnique',
                'message' => 'このユーザー名は既に登録されています',
                'last' => true,
            ),           
            'notPattern' => array(
                'rule' => array('email', false, '/^[a-z0-9\._-]{1,64}@(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)$/i'), 
                'message' => 'メールアドレスを正しく入力して下さい',
            ),
        ),
       'mail_conf' => array(
            'rule' => array('mailAddressMatch'),
            'message' => 'メールアドレスが一致しません',
        ),
        //サービスの使用目的
        'text' => array(
            'rule' => 'notEmpty',
            'message' => 'サービスの使用目的は必須項目になります',
        ),
        //会員規約同意
        'agree' => array(
            'rule' => array('isAgreeCheck'),
            'message' => '会員規約に同意されない場合は会員登録ができません',
        ),
    );

    //コンストラクタ
	public function __construct(){
		parent::__construct();
		//インポートモデルを読み込む場合はインスタンスを作成
		$this->MembersTopic   = new MembersTopic();
		$this->MembersProfile = new MembersProfile();
	}
    
    //メールアドレスの照合（確認で入力したものと同じか否か）
    public function mailAddressMatch($data){        
        //確認用ではないメールアドレスのデータを取得
        $p_mail = $this->data['Member']['mail'];
        //確認用のメールアドレスのデータを取得
        $p_mail_conf = array_shift($data);
        
        if($p_mail == $p_mail_conf){
            return true;
        }else{
            return false;
        }
    }
    
    //業種とその他ボックスの連携を行う
    public function isAgreeCheck($data){
        //業種のテキスト欄を取得
        $p_agree = array_shift($data);
        
        //会員規約に同意しているか
        if($p_agree != $this->is_agree_flag){
            return false;
        }else{
            return true;
        }
    }
    
    //メンバー情報を登録するためsaveメソッドをカスタマイズ
    public function saveMemberInfo($data = null, $validate = true, $fieldList = array()) {
        
        //passというフィールドがあれば、passをハッシュ化してpasswordに追加する(Editがない時限定)
        if(isset($data['pass'])){
            App::import('Component', 'Auth');
            $data['password'] = AuthComponent::password($data['pass']);
        }
        
        //親のsave()を呼び出す
        return parent::save($data, $validate, $fieldList);
    }
    
    //32バイトのトークン値を作成する
    public function createTokenForSite(){
    	
        $token_length = $this->auth_token_length;
        $bytes = openssl_random_pseudo_bytes($token_length);
        $token = bin2hex($bytes);
        
        //重複したトークンがある場合は再帰的にトークン値を作成する
        $sameTokenCount = $this->find('count', 
            array('conditions' => array('Member.token' => $token))
        );
        
        if($sameTokenCount != 0){
            $this->createTokenForSite();
        }else{
            return $token;
        }
    }
    
    //メールより受け取ったトークン値を検証する
    public function checkTokenForParam($token){
        
        $memberRecord = $this->find('first',
            array('conditions' => array('Member.token' => $token))
        );
                
        //トークン値に合致するレコードがあった場合は、statusを0から1へ変更
        if( !empty($memberRecord) ){
            $memberId = $memberRecord['Member']['id'];
            $now = date("Y-m-d H:i:s");
            $this->updateAll(
                $fields = array(
                    'Member.status'   => $this->mail_auth_already,
                    'Member.modified' => "'".$now."'"
                ),
                $conditions = array(
                    'Member.id' => $memberId
                )
            );
            return $this->mail_auth_already;
        }else{
            return $this->mail_auth_yet;
        }
    }
    
    //プロフィールのデフォルト値を新規追加する
    public function saveProfileFormat($id){
	    return $this->MembersProfile->saveInitialProfileByMemberId($id);    	
    }
    
    //会員専用情報を最新5件を取得する
    public function getMembersTopicRecords(){
	    return $this->MembersTopic->getNewestMemberTopic();
    }
    
}