<?php
/**
 *
 * CatalogsCommentsコントローラークラス
 * Date:    2014/10/16
 * Created: Fumiya Sakai
 *
 */
 
class CatalogsCommentsController extends AppController{
    
    //メンバ変数の設定
    public $name = 'CatalogsComments';
    public $uses = array('CatalogsComment','Catalog');
    public $layout = 'common_format_blog';
    public $components = array('Auth','Session','RequestHandler');
    public $helpers = array('Formhidden','Csv','Html','Dateform');
    
    public $paginate = array(
        'page' => 1,
        'conditions' => array(),
        'fields' => array(
            'id',
            'catalog_id',
            'username',
            'text',
            'flag',
            'published',
            'created',
            'modified',
            ),
        
        'limit' => 100,
        'order' => 'CatalogsComment.id DESC',
    );
    
    //URL遷移先のページ
    private $uri_catalogs_index = '/catalogs/index';
    private $uri_index          = '/catalogs_comments/index';
    private $uri_search         = '/catalogs_comments/search';
    private $uri_control_index  = '/control/catalogs_comments/index';
    
    //認証関連の設定
    public function beforeFilter() {
       parent::beforeFilter();
    }
    
    //管理画面時のレイアウトの切り替え
    public function beforeRender() {
        parent::beforeRender();
    }

    //カタログ（大塚Catalogs）のコメント管理TOP（管理画面）
    public function control_index(){
        
        //パンくずリストの設定
        $breadcrumb = array(
            array('name' => '管理画面TOP','link' => false),
            array('name' => 'カタログ（大塚Catalogs）のコメント一覧','link' => false)
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->CatalogsComment->find('count');
        $this->set('allAmount',$allAmount);
        
        //catalogs_commentsテーブルからデータを持ってくる
        $catalogComments = $this->paginate();
        $this->set('catalogComments', $catalogComments);

        $catalogTitleList = $this->CatalogsComment->getControlCatalogTitleList();
        $this->set('catalogTitleList', $catalogTitleList);
        
        //表示数を取得
        $limitAmount = $this->paginate['limit'];
        $this->set('limitAmount',$limitAmount);
        
        //ビューのレンダリング
        $this->render('control_index');
    }
    
    //カタログ（大塚Catalogs）のコメント表示ステータス変更（管理画面）
    public function control_change($id = null){
        //URLの直アクセスの禁止  
        if($this->RequestHandler->isGet()){
            $this->redirect($this->uri_control_index);
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            //レイアウトを使用しない
            $this->autoRender = false;
            $this->autoLayout = false;
            
			//レスポンスを取得する
			$response = $this->CatalogsComment->changeFlagStatus($id);
            $this->header('Content-type: application/json');
            
            //debugKitのAjax対策
            Configure::write('debug', 0);
            echo json_encode($response);
            exit();
        }
        $this->redirect($this->uri_control_index);
    }

    //カタログ（大塚Catalogs）のコメント削除（管理画面）
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
			$response = $this->CatalogsComment->deleteDataById($id);
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
        
        $filename     = 'カタログ（大塚Catalogs）のコメント'.date('Ymd');
        $headRow      = array('ID','カタログID','ユーザー','内容','投稿日','公開フラグ');
        $contentsRows = $this->CatalogsComment->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }
    
    //エレメント表示のみ（searchへリダイレクト）
    public function index(){
        
        $condition = array('CatalogsComment.flag' => COMMON_PUBLISHED);
        if(isset($this->params['requested'])){
            $catalogComments = $this->paginate('CatalogsComment', $condition);
            return $catalogComments;
        }else{
			//コメント検索ページにリダイレクト
			$this->redirect($this->uri_search);
        }        
    }
    
    //コメント投稿完了ページ    
    public function complete(){
		
		try{
                    
			//Ajaxリクエスト時のみ公開ステータスの変更を行う
			if($this->RequestHandler->isAjax()){
            
            	//AjaxでPOSTされた値を取得する
				$catalog_id = $this->params['form']['catalog_id'];
				$username   = $this->params['form']['username'];
				$text   	= h($this->params['form']['text']);
				$published	= date("Y-m-d", time());
				$flag		= ADMIN_ONLY;
			
				//saveする条件を追加する
				$this->data['CatalogsComment'] = array(
            		'catalog_id' => $catalog_id,
					'username'   => $username,
					'text'       => $text,
					'published'  => $published,
					'flag'       => $flag
				);
				
				if($this->CatalogsComment->save($this->data['CatalogsComment']) !== false){

                	$this->autoRender = false;
					$this->autoLayout = false;

					//変更したステータスの取得
					$response = $text;                
					$this->header('Content-type: application/json');

					//debugKitのAjax対策
					Configure::write('debug', 0);
					echo json_encode($response);
					exit();
				}
			}
		
		} catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }                
    }

    //search（検索＆一覧表示）
    public function search(){
    
    	try{
    		
    		$catalog_id = !empty($this->params['url']['catalog_id']) ? $this->params['url']['catalog_id'] : '';
    		$query      = !empty($this->params['url']['query'])      ? $this->_trimSearchParam($this->params['url']['query']) : '';
    		
    		//パラメーターをセットする	
			$this->set('catalog_id', $catalog_id);
			$this->set('query', $query);
			
			//検索条件を作成する
    		$conditions = $this->_makeSearchConditions($catalog_id, $query);
						
			//条件に合致するデータを取得
			$this->paginate['limit'] = 10;
			//$this->paginate['limit'] = 1;
			
			$catalogsComments = $this->paginate('CatalogsComment', $conditions);
	    	$this->set('catalogsComments', $catalogsComments);
	    	
	    	//カタログマスタデータを取得
	    	$catalogTitleList = $this->CatalogsComment->getCatalogDataList();
			$this->set('catalogTitleList', $catalogTitleList);
	    	
	    	//タイトルメッセージのセット
            $this->set('title_for_layout','大塚Catalogs 皆様からのコメント一覧');
	    	
	    	//パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '大塚Catalogs', 'link' => $this->uri_catalogs_index),
                array('name' => '皆様からのコメント一覧','link' => false)
            );
            $this->set('breadcrumb', $breadcrumb);
	    	
	    	//最大レコード数を取得
	    	$hit_max_count = $this->CatalogsComment->find('count', array('conditions' => $conditions));
	    	$this->set('hit_max_count', $hit_max_count);
	    	
    	} catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
        }
    }
    
    //詳細表示
    public function view($id = null){
	    //コメント情報を取得する
	    try{
	    
	        //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect($this->uri_index);
            }
            
            //データを取得する
            $this->data = $this->CatalogsComment->getDetailDataById($id);
            if($this->data === false){
                $this->redirect('/catalogs/');
            }
            
            //変数をセット
            $this->set('data', $this->data);
            
	    	//カタログマスタデータを取得
	    	$catalogTitleList = $this->CatalogsComment->getCatalogDataList();
			$this->set('catalogTitleList', $catalogTitleList);
            
            //タイトルメッセージのセット
            $this->set('title_for_layout','大塚Catalogs（'.$catalogTitleList[$this->data['CatalogsComment']['catalog_id']]['title'].'）内のコメント');
            
            //パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '大塚Catalogs', 'link' => array('controller' => 'catalogs', 'action' => 'index')),
                array('name' => '皆様からのコメント一覧','link' => array('controller' => 'catalogs_comments', 'action' => 'search')),
                array('name' => $catalogTitleList[$this->data['CatalogsComment']['catalog_id']]['title'].'に関するコメント','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb); 
		    
	    }catch(Exception $e){
	    	
		    //エラー処理
            $this->log($e->getMessage());
            $this->redirect($this->uri_index);
	    }
    }
    
    //private関数：検索条件用condition配列を生成する
    private function _makeSearchConditions($catalog_id = null, $query = null){
		
		//カタログコメントの検索条件パラメータを取得
    	$conditions = array();
    	$conditions['CatalogsComment.flag'] = COMMON_PUBLISHED;
		
		//検索条件：カタログID
		if(!empty($catalog_id)){
			$conditions['CatalogsComment.catalog_id'] = $catalog_id;
		}
		
		//検索条件：フリーワード
		if(!empty($query)){
            
            $query_array = preg_replace('/ /',	',', $query);	//1つの半角スペースをカンマへ
			$query_array = explode(',', $query_array);
			
			//パラメータが1つしかない場合
			if(count($query_array) === 1){
				
				$conditions['CatalogsComment.text LIKE ?'] = '%'.$query_array[0].'%';					
			
			//パラメータが2つ以上ある場合
			}elseif(count($query_array) > 1){
				
				foreach($query_array as $query_key => $query_value){
					if(!empty($query_value)){	
						$conditions['or'][$query_key]['CatalogsComment.text LIKE ?'] = '%'.$query_value.'%';
					}
				}
				
			}
			
		}
		return $conditions;
    }
    
    //private関数：クエリ文字列をトリムする
    private function _trimSearchParam($query){
		
		$query = preg_replace('/　/',	' ', $query);		//全角スペースを半角スペースへ
        $query = preg_replace('/\s+/',	' ', $query);		//連続する半角スペースを1つの半角スペースへ
		$query = trim($query);								//先頭と末尾をトリム
		return $query;
    }
    
}