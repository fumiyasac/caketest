<?php
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
            array(
                'name' => '管理画面TOP',
                'link' => false
            ),
            array(
                'name' => 'カタログ（大塚Catalogs）のコメント一覧',
                'link' => false
            ),
        );
        $this->set('breadcrumb',$breadcrumb);
        
        //全ての件数の取得
        $allAmount = $this->CatalogsComment->find('count');
        $this->set('allAmount',$allAmount);
        
        //catalogs_commentsテーブルからデータを持ってくる
        $catalogComments = $this->paginate();
        $this->set('catalogComments', $catalogComments);
        
        $catalogDatas = $this->Catalog->find('all');        
        foreach($catalogDatas as $value){
        	$catalog_id = $value['Catalog']['id'];
	        $catalogTitleList[$catalog_id] = $value['Catalog']['title'];
        }
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
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ公開ステータスの変更を行う
        if($this->RequestHandler->isAjax()){
            
            $this->CatalogsComment->id = $id;
            
            //ステータスを変更する
            if($this->CatalogsComment->field('flag') == 2){
                $flag_id = 1;
            } else if($this->CatalogsComment->field('flag') == 1) {
                $flag_id = 2;
            }
            
            if($this->CatalogsComment->saveField('flag', $flag_id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                //変更したステータスの取得
                $response = array('id' => $id, 'flagStatus' => Configure::read("FLAG_CONF.flag.{$flag_id}"));                
                $this->header('Content-type: application/json');
                //debugKitのAjax対策
                Configure::write('debug', 0);
                echo json_encode($response);
                exit();
            }
        }
        $this->redirect(array('action' => 'control_index'));
    }

    //カタログ（大塚Catalogs）のコメント削除（管理画面）
    public function control_delete($id = null){
        //URLの直アクセスの禁止  
        if($this->RequestHandler->isGet()){
            $this->redirect(array('action' => 'control_index'));
        }
        
        //Ajaxリクエスト時のみ削除を行う
        if($this->RequestHandler->isAjax()){
            
            //削除処理
            if($this->CatalogsComment->delete($id)){
                $this->autoRender = false;
                $this->autoLayout = false;
                                
                //全ての件数の取得
                $allAmount = $this->CatalogsComment->find('count');
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
        $filename = '登録バナーの一覧'.date('Ymd');
        
        //表の1行目の作成
        $headRow = array(
            'ID',
            'カタログID',
            'ユーザー',
            '内容',
            '投稿日',
            '公開フラグ',
        );
        
        //データを取得
        $contentsRows = $this->CatalogsComment->find('all');
        
        //変数を値へセット
        $this->set(compact('filename', 'headRow', 'contentsRows'));        
    }
    
    //一覧表示
    public function index(){
        
        if(isset($this->params['requested'])){
            $catalogComments = $this->paginate('CatalogsComment', array('CatalogsComment.flag' => 1));
            return $catalogComments;
        }else{
			
			//コメント一覧を表示する時に使用予定（まだ未定）
			
        }
        
    }
    
    public function complete(){
		
		try{
                    
			//Ajaxリクエスト時のみ公開ステータスの変更を行う
			if($this->RequestHandler->isAjax()){
            
            	//AjaxでPOSTされた値を取得する
				$catalog_id = $this->params['form']['catalog_id'];
				$username   = $this->params['form']['username'];
				$text   	= h($this->params['form']['text']);
				$published	= date("Y-m-d", time());
				$flag		= 2;
			
				//saveする条件を追加する
				$this->data['CatalogsComment'] = array(
            		'catalog_id' => $catalog_id,
					'username'   => $username,
					'text'       => $text,
					'published'  => $published,
					'flag'       => $flag,
				);
								
				$this->log($this->data);
            
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
            $this->redirect('/catalogs/index');
            
        }                
    
    }

    //@todo:search（検索＆一覧表示）
    public function search(){
    
    	try{
    				    		
    		//カタログコメントの検索条件パラメータを取得
    		$conditions = array();
    		$conditions['CatalogsComment.flag'] = 1;
			
			//検索条件
			if(!empty($this->params['url']['catalog_id'])){
				$conditions['CatalogsComment.catalog_id'] = $this->params['url']['catalog_id'];
				$catalog_id = $this->params['url']['catalog_id'];
			}else{
				$catalog_id = '';
			}
			$this->set('catalog_id', $catalog_id);
			
			if(!empty($this->params['url']['query'])){
				$query = $this->params['url']['query'];
				$query = preg_replace('/　/',	' ', $query);		//全角スペースを半角スペースへ
                $query = preg_replace('/\s+/',	' ', $query);		//連続する半角スペースを1つの半角スペースへ
				$query = trim($query);								//先頭と末尾をトリム
                $query_array = preg_replace('/ /',	',', $query);	//1つの半角スペースをカンマへ
				$query_array = explode(',', $query_array);
				
				if(count($query_array) === 1){
					$conditions['CatalogsComment.text LIKE ?'] = '%'.$query_array[0].'%';					
				}elseif(count($query_array) > 1){
					foreach($query_array as $query_key => $query_value){
						if(!empty($query_value)){
							$conditions['or'][$query_key]['CatalogsComment.text LIKE ?'] = '%'.$query_value.'%';
						}
					}					
				}
			}else{
				$query = '';
			}		
			$this->set('query', $query);
						
			//条件に合致するデータを取得
			$this->paginate['limit'] = 10;
			//$this->paginate['limit'] = 1;
			$catalogsComments = $this->paginate('CatalogsComment', $conditions);
	    	$this->set('catalogsComments', $catalogsComments);
	    	
	    	//カタログマスタデータを取得
	    	$catalogDatas = $this->Catalog->find('all',array('conditions' => array('Catalog.flag' => 1)));        
			foreach($catalogDatas as $value){
				$catalog_id = $value['Catalog']['id'];
				$catalogTitleList[$catalog_id]['title'] = $value['Catalog']['title'];
				$catalogTitleList[$catalog_id]['template'] = $value['Catalog']['template'];
				$catalogTitleList[$catalog_id]['catalog_image'] = $value['Catalog']['catalog_image'];
			}
			$this->set('catalogTitleList', $catalogTitleList);
	    	
	    	//タイトルメッセージのセット
            $this->set('title_for_layout','大塚Catalogs 皆様からのコメント一覧');
	    	
	    	//パンくずリストの設定 
            $breadcrumb = array(
                array('name' => 'HOME', 'link' => '/'),
                array('name' => '大塚Catalogs', 'link' => array('controller' => 'catalogs', 'action' => 'index')),
                array('name' => '皆様からのコメント一覧','link' => false),
            );
            $this->set('breadcrumb', $breadcrumb);
	    	
	    	//最大レコード数を取得
	    	$hit_max_count = $this->CatalogsComment->find('count', array('conditions' => $conditions));
	    	$this->set('hit_max_count', $hit_max_count);
	    	
    	} catch (Exception $e){
            
            //エラー処理
            $this->log($e->getMessage());
            $this->redirect('/catalogs/index');
            
        }
	    
    }
    
    //詳細表示
    public function view($id = null){
	    //コメント情報を取得する
	    try{
	    
	        //idがなければ一覧ページへリダイレクト
            if(!isset($id) && is_numeric($id)){
                 $this->redirect('/catalogs/');
            }
            
            //データを取得する
            $this->data = $this->CatalogsComment->find('first',
                array(
                    'conditions' => array(
                    	'CatalogsComment.id' => $id, 
						'CatalogsComment.flag' => 1
					),
                )
            );
            
            //変数をセット
            if($this->data === false){
                $this->redirect('/catalogs/');
            }else{
                $this->set('data', $this->data);
                
                //カタログマスタデータを取得する
                $catalogDatas = $this->Catalog->find('all',array('conditions' => array('Catalog.flag' => 1)));        
				foreach($catalogDatas as $value){
					$catalog_id = $value['Catalog']['id'];
					$catalogTitleList[$catalog_id]['title'] = $value['Catalog']['title'];
					$catalogTitleList[$catalog_id]['template'] = $value['Catalog']['template'];
					$catalogTitleList[$catalog_id]['catalog_image'] = $value['Catalog']['catalog_image'];
				}
				$this->set('catalogTitleList', $catalogTitleList);
            }
            
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
            $this->redirect("/catalogs/");
	    }
	    	    
    }
    
    
}
?>
