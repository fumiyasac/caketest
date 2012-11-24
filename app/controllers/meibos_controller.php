<?php
class MeibosController extends AppController{
    public $name = "Meibos";
    public $uses = array("Meibo","Tuikameibo"); //使用モデルの指定
    public $layout = "cakephp_kihon"; //オリジナルレイアウトの指定
    
    //ページネーションの追加
    public $paginate = array(
        "page" => 1, //初期設定のページ番号
        "conditions" => array(), //画面表示の検索条件の指定
        "fields" => array(
            "id",
            "shimei",
            "jusyo",
            "denwa",
            "Tuikameibo.faxnum", //tuikameibos内のデータ（faxnum）
            "Tuikameibo.mailadd", //tuikameibos内のデータ（mailadd）
            "Tuikameibo.yubinnum" //tuikameibos内のデータ（yubinnum）
        ), //画面表示するフィールド（列）
        "sort" => "id", //並び順の基準となるフィールド（列）
        "limit" => 2 //1ページに画面表示する件数
    );
    
    //コンポーネントの指定
    public  $components = array("Auth");

    function index(){
        //$data = $this->Meibo->find("all");
        $data = $this->paginate();
        $this->set("result",$data);
        
        //表示件数の転送
        $kensu = $this->paginate["limit"];
        $this->set("kensu",$kensu);
        
        //ユーザー名の転送
        $a_username = $this->Auth->user("username");
        $this->set("a_username", $a_username);
        
    }

    function tuika(){
        
        //key別の変数に追加する
        //extract($this->params["form"]);
        extract($this->data["Meibo"]);
        
        if(!empty($shimei) && !empty($jusyo) && !empty($denwa)){
            /*
            $data = array("Meibo" => array());
            $data["Meibo"]["shimei"] = $shimei;
            $data["Meibo"]["jusyo"] = $jusyo;
            $data["Meibo"]["denwa"] = $denwa;
             */
            
            //モデルへのデータ保存
            //$this->Meibo->save($data);
            
            //Meiboモデルへのデータ追加保存
            $this->Meibo->save($this->data);
            
            //Tuikameiboモデルへのデータ追加保存
            $id = $this->Meibo->getInsertID();
            $this->data["Tuikameibo"]["meibo_id"] = $id;
            $this->Tuikameibo->save($this->data);
            
            $this->flash('名簿の追加が完了しました。','/meibos');
            //$this->redirect("index");
        }else{
            $this->cakeError("eSyori",array("eStr" => "名前・住所・電話を入力して下さい。"));
        }

    }
    
    function sakujo(){
        $delelePwd = "123456";
        
        //パスワードの照合
        if($this->data["Meibo"]["delete_pwd"] == $delelePwd){
            
            //表示件数の取得
            //$saidaiId = $this->Meibo->field("id",array(),"Meibo.id desc");
            $kensu = $this->paginate["limit"];
            
            //表示件数分の繰り返し
            for($i=0;$i<$kensu;++$i){
                //チェックデータの判定
                if(isset($this->data["Meibo"][$i]) && $this->data["Meibo"][$i] != 0){
                    $id = $this->data["Meibo"][$i];
                    //Meiboモデルのデータ削除
                    $this->Meibo->delete($id);
                    //Tuikameiboモデルへのデータ削除
                    $sakujo = $this->Tuikameibo->field("id", array("meibo_id" => $id));
                    $this->Tuikameibo->delete($sakujo);
                }
            }
            $this->flash('名簿の削除が完了しました。','/meibos');
        }else{
            $this->cakeError("eSyori",array("eStr" => "パスワードが間違っています。"));
        }
    }
    
    
}
?>

