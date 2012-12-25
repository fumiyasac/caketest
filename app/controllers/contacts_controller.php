<?php
Class ContactsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Contacts';
    public $uses = array('Contact');
    public $layout = 'common_format_blog';
    public $components = array('Session','Email');
    public $helpers = array('Formhidden');
    
    //入力画面
    public function index(){
        
        try{
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','お問い合わせ');
            $this->set('form_description','このブログに関するお問い合わせ、ご要望はお気軽にどうぞ！');
            
            //トークンの生成
            $this->Session->write('token', String::uuid());
            
        }catch(Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/contacts');
        }
        
    }
    
    //確認画面
    public function confirm(){
        
        try{
            
            //タイトルメッセージ
            $this->set('title_for_layout','お問い合わせ内容の確認');
            $this->set('form_description','この内容で送信してもよろしいですか？' );
            
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
                    $this->set('form_description','このブログに関するお問い合わせ、ご要望はお気軽にどうぞ！');
                    
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

    //送信完了
    public function complete(){
        
        try{
 
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