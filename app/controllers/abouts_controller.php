<?php
/**
 *
 * Aboutsコントローラークラス
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

class AboutsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'Abouts';
    public $uses = array();
    public $layout = 'common_format_blog';
    public $components = array();
    public $helpers = array();
    
    //URL遷移先のページ
    private $uri_index = '/abouts/index';
    
    //このサイトに関して
    public function index(){
        
        try{
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','このブログについて');
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => 'このブログについて','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb);
            
        }catch(Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
    }
    
}