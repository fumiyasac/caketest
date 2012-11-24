<?php
class UsersController extends AppController{
    
    public $name = "Users";
    public $uses = array("User");
    public $components = array("Auth", "Session","Cookie"); //コンポーネントの指定
    public $layout = "cakephp_kihon";
    
    //各アクションの前に実行する共有スクリプト
    function beforeFilter() {
        //ログインしなくても良いアクションを指定
        $this->Auth->allow("add");
        
        //ログインが失敗した際のエラーメッセージ
        $this->Auth->loginError = "ユーザーIDまたはパスワードが違います。";
        
        //権限がないactionを実行した際のエラーメッセージ
        $this->Auth->authError = "ログインして下さい";
        
        //暗号化無効のメソッドを再読み込み
        $this->Auth->authenticate = ClassRegistry::init("User");
        
        //クッキー取得
        $c_username = $this->Cookie->read("c_username");
        $c_password = $this->Cookie->read("c_password");
        
        //変数の初期化
        if(isset($c_username)){
            $this->set("c_username",$c_username);
            $this->set("c_password",$c_password);
        }else{
            $this->set("c_username","");
            $this->set("c_password","");            
        }
        
        //初期設定の自動リダイレクト機能をoff
        $this->Auth->autoRedirect = false;
    }


    function index(){
        $a_username = $this->Auth->user("username");
        $this->set("a_username", $a_username);
    }
    
    function login(){
        //認証成功時のクッキー書き込み
        if($this->Auth->user()){
            
            if($this->data["User"]["remember_me"] == "0"){
                $c_username = $this->data["User"]["username"];
                $c_password = $this->data["User"]["password"];
                $this->Cookie->write("c_username", $c_username, true , "1 year");
                $this->Cookie->write("c_password", $c_password, true , "1 year");
            }else{
                $this->Cookie->delete("c_usename");
                $this->Cookie->delete("c_password");
            }
            
            //Auth内の呼び出し元へのリダイレクト
            $this->redirect($this->Auth->redirect("/users/index"));
        }
    }
    
    function logout(){
        $this->Session->setFlash("ログアウトしました。");
        $this->redirect($this->Auth->logout());
    }
    
    function add(){
        if(!empty($this->data)){

            //変数の初期化
            if($this->data["User"]["remember_me"] == "0"){
                $c_username = $this->data["User"]["username"];
                $c_password = $this->data["User"]["password"];
                $this->Cookie->write("c_username", $c_username, true , "1 year");
                $this->Cookie->write("c_password", $c_password, true , "1 year");
            }else{
                $this->Cookie->delete("c_usename");
                $this->Cookie->delete("c_password");
            }
            unset($this->data["User"]["remember_me"]);
            
            $this->User->create();
            $this->User->save($this->data["User"]);
            
            //バリデーションが成功した場合のロジック
            if($this->User->validates()){
                $this->redirect(array("action" => "index"));   
            }
            //$this->flash("登録が完了しました。", "./login");
        }
    }
}
?>
