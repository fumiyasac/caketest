<?php
/**
 *
 * PostsEnquetesコントローラークラス
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

class PostsEnquetesController extends AppController{
    
    //メンバ変数の設定
    public $name = 'PostsEnquetes';
    public $uses = array('PostsEnquete');
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform');
    
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'post_id',
            'username',
            'enquete_question1',
            'enquete_question2',
            'enquete_question3',
            'enquete_question4',
            'enquete_question5',
            'enquete_type1',
            'enquete_type2',
            'enquete_type3',
            'enquete_type4',
            'enquete_type5',
            'enquete_answer1',
            'enquete_answer2',
            'enquete_answer3',
            'enquete_answer4',
            'enquete_answer5',
            'created',
            'modified',
            ),
        
        'limit' => 100,
        'order' => 'PostsEnquete.id DESC',
    );
    
    //URL遷移先のページ
    private $uri_control_index = '/control/posts_enquetes/index';
	    
    //認証関連の設定
    public function beforeFilter() {
       parent::beforeFilter();
    }
    
    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }

    //アンケート結果TOP（管理画面）
    public function control_index(){
        
        //パンくずリストの設定
        $breadcrumb = array(
            array('name' => '管理画面TOP', 'link' => false),
            array('name' => 'アンケート回答結果の一覧','link' => false),
        );
        $this->set('breadcrumb', $breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->PostsEnquete->find('count');
        $this->set('allAmount',$allAmount);
        
        //posts_enquetesテーブルからデータを持ってくる
        $posts_enquetes = $this->paginate();
        $this->set('posts_enquetes', $posts_enquetes);
        
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
            $this->redirect($this->uri_control_index);
        }
        
        //Ajaxリクエスト時のみ削除を行う
        if($this->RequestHandler->isAjax()){
            
            //レイアウトを使用しない
            $this->autoRender = false;
            $this->autoLayout = false;

			//レスポンスを出力する
			$response = $this->PostsEnquete->deleteDataById($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }
    
    //CSVファイルのダウンロード（管理画面）
    public function control_csvdownload(){
        
        //レイアウトを使用しない
        Configure::write('debug', 0);
        $this->layout = false;
        
        $filename = 'アンケート回答結果の一覧'.date('Ymd');
        $headRow  = array(
            'ID',
            'アンケートID',
            'ユーザー名',
            '質問内容1',            
            '質問内容2',
            '質問内容3',
            '質問内容4',
            '質問内容5',
            '質問タイプ1',            
            '質問タイプ2',
            '質問タイプ3',
            '質問タイプ4',
            '質問タイプ5',
            '質問に対する回答1',            
            '質問に対する回答2',
            '質問に対する回答3',
            '質問に対する回答4',
            '質問に対する回答5',
            '登録日',
            '更新日',
        );
        $contentsRows = $this->PostsEnquete->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));
    }
    
}