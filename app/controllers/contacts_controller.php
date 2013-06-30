<?php
Class ContactsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Contacts';
    public $uses = array('Contact');
    public $layout = 'common_format_blog';
    public $components = array('Session','Email','RequestHandler');
    public $helpers = array('Formhidden','Csv');
    
    //ページャーの設定
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'name',
            'kana',
            'mail',
            'purpose',
            'purpose_etc',
            'text',
            'enquete1',
            'enquete2',
            'enquete3',
            'enquete4',
            'enquete5',
            'created',
            'modified',
            ),
        
        'limit' => 100,
        'order' => 'Contact.id DESC',
    );
    
    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }
        
    //お問い合わせTOP（管理画面）
    public function control_index(){
        
        //パンくずリストの設定
        $breadcrumb = array(
            array('name' => '管理画面TOP', 'link' => false),
            array('name' => 'お問い合わせの一覧','link' => false),
        );
        $this->set('breadcrumb', $breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->Contact->find('count');
        $this->set('allAmount',$allAmount);
        
        //contactsテーブルからデータを持ってくる
        $contacts = $this->paginate();
        $this->set('contacts', $contacts);
        
        //表示数を取得
        $limitAmount = $this->paginate['limit'];
        $this->set('limitAmount',$limitAmount);
        
        //ビューのレンダリング
        $this->render('control_index');

    }
    
    //お問い合わせ削除（管理画面）
    public function control_delete($id = null){
        
        //URLの直アクセスの禁止  
        if($this->RequestHandler->isGet()){
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ削除を行う
        if($this->RequestHandler->isAjax()){
            if($this->Contact->delete($id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                //全ての件数の取得
                $allAmount = $this->Contact->find('count');
                $response = array('id' => $id, 'allAmount' => $allAmount);                
                $this->header('Content-type: application/json');
                //debugKitのAjax対策
                Configure::write('debug', 0);
                echo json_encode($response);
                exit();
            }
        }
        $this->redirect(array('action' => 'control_index'));
    }
    
    //CSVファイルのダウンロード（管理画面）
    public function control_csvdownload(){
        
        Configure::write('debug', 0);
        
        //レイアウトを使用しない
        $this->layout = false;
        
        //ファイル名
        $filename = 'お問い合わせ一覧'.date('Ymd');
        
        //表の1行目の作成
        $headRow = array(
            'ID',
            '名前',
            'フリガナ',
            'メールアドレス',
            'お問い合わせ内容',
            'お問い合わせ内容（その他選択時の備考）',
            '本文',
            '（Q1）あなたのご年齢を選択して下さい',            
            '（Q2）あなたの職業の業種を選択して下さい',
            '（Q3）現在のストアでご興味のある商品はありますか？',
            '（Q4）あなたがよく利用しているオンラインショップは何ですか？',
            '（Q5）Q4のオンラインショップを利用する理由があればお答え下さい。',
            '登録日',
            '更新日',
        );
        
        //データを取得
        $contentsRows = $this->Contact->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));
    }
    
    
    //フォーム入力画面
    public function index(){
        
        try{
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','お問い合わせ');
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'お問い合わせ','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        }catch(Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/contacts');
        }
        
    }
    
    //フォーム確認画面
    public function confirm(){
        
        try{
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','お問い合わせ内容の確認');
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'お問い合わせ内容の確認','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb);
            
            if(!empty($this->data) && $this->Session->check('token')){
                
                //変数に値をセット
                $this->Contact->set($this->data);
                
                //バリデーションチェック
                if($this->Contact->validates()){

                    $this->set('data', $this->data);
                    
                    //ビューのレンダリング
                    $this->render('confirm');
                    
                }else{
                    //前のページのタイトルを追加
                    $this->set('title_for_layout','お問い合わせ');
                    $breadcrumb = array(
                        array('name' => 'HOME', 'link' => '/'),
                        array('name' => 'お問い合わせ','link' => false),
                    );
                    $this->set('breadcrumb', $breadcrumb);
                    $this->set('error_announce','入力内容に誤りがあります。もう一度入力内容を確認して下さい');
                    
                    //ビューのレンダリング
                    $this->render('index');
                }
                
            }else{
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__('不正アクセスが行われた可能性があります', true));
            }
            
        }catch(Exception $e){
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/contacts');
        }
    }

    //フォーム送信完了
    public function complete(){
        
        try{
            
            //パンくずリストの設定
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'お問い合わせの完了','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb);
 
            //アクセスのチェック
            if(!empty($this->data) && $this->Session->check('token')){

                //取得データをDBへ保存する。
                if($this->Contact->save($this->data['Contact']) !== false){

                    $options = Configure::read('MAIL_CONF.custmor');
                    $options['to'] =  $this->data['Contact']['mail'];
                    $options['data'] = $this->data;

                    if ($this->_sendMail($options)) {
                        $options = Set::merge($options, Configure::read('MAIL_CONF.admin'));
                        $this->_sendMail($options);
                        //OK（DB登録・メール処理ともに成功）
                        $this->set('title_for_layout','お問い合わせの完了');
                        $this->set('form_description','お問い合わせが正常に完了しました。弊社より返信メールを送りました。この度はありがとうございました。' );
                        $this->set('complete_link',0);                                

                    }else{
                        //NG（DB登録のみ成功）
                        $this->log('Cannot Send Administrator.');
                        $this->log($this->data);
                        
                        $this->set('title_for_layout','お問い合わせが正常に完了できませんでした');
                        $this->set('form_description','誠に申し訳ございませんが、再度フォームよりお問い合わせ事項を入力して頂きます様宜しくお願いします。' );
                        $this->set('complete_link', 1);  
                    }

                    //ビューのレンダリング
                    $this->render('complete');
                    
                }else{
                    $this->flash("エラーが発生しました。\nお手数ではありますが再度入力をお願いします。",array('controller' => 'entries', 'aciton' => 'index')); 
                }
                
                //トークンの消去(CSRF対策)
                $this->Session->destroy();
                
            }else{
                //データがないのにアクセスした場合、Exceptionを投げる
                throw new Exception(__('不正アクセスが行われた可能性があります', true));                                    
            }
            
        }catch(Exception $e){
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/contacts');
        }
        
    }
}
?>