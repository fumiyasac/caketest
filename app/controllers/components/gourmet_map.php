<?php
/**
 * 
 * 第1回：大塚グルメマップ用APIデータ表示用コンポーネント
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

class GourmetMapComponent extends Object {
    
    //ベースURI用変数
    public $base_uri_gnavi;
    public $base_uri_hotpepper;
    public $base_uri_category_large;
    
    //API検索用定数
    private $gnavi_key_id           = "afda483163ce455f81f1a911db51d3f6";
    private $hotpepper_key_id       = "baf5261d36850873";
    private $lat                    = "35.731010";
    private $lng                    = "139.728688";
    private $gnavi_coordinates_mode = 2;
    private $hotpepper_count        = 1;
    private $hotpepper_range        = 2;
        
    //検索条件：検索範囲の条件
    public static $range_array = array(
        '1' => '300m以内',
        '2' => '500m以内',
        '3' => '1km以内',
        '4' => '2km以内',
        '5' => '3km以内'
    );
    
    //API検索用のベースURIの作成
    public function initialize(&$controller) {
        //ぐるなびAPI情報
        $this->base_uri_gnavi = 'http://api.gnavi.co.jp/ver1/RestSearchAPI/?keyid='
                . $this->gnavi_key_id
                . '&latitude='. $this->lat 
                . '&longitude=' .  $this->lng
                . '&coordinates_mode=' . $this->gnavi_coordinates_mode;
        //ぐるなびAPI大業態マスタ情報
        $this->base_uri_category_large = 'http://api.gnavi.co.jp/ver1/CategoryLargeSearchAPI/?keyid='
                . $this->gnavi_key_id;
        //HotpepperAPI情報
        $this->base_uri_hotpepper = 'http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key='
                . $this->hotpepper_key_id
                . '&lat='. $this->lat 
                . '&lng=' .  $this->lng
                . '&count=' .  $this->hotpepper_count 
                . '&range=' .  $this->hotpepper_range;
    }
    
    //ぐるなびデータの最大登録数を取得
    public function maxCountFromAPI($params = array()){
        uses('Xml');
        //パラメータストリングを作成する
        $param_string = $this->makeURLStringOfAPI($params);
        $gnavi_xml = new Xml($this->base_uri_gnavi . $param_string);
        $gnavi_xml_data = Set::reverse($gnavi_xml);
        if( !empty($gnavi_xml_data['Response']) ){
            return $gnavi_xml_data['Response']['total_hit_count'];
        }else{
            return 0;
        }
    }

    //ぐるなびデータを取得して新しい配列を作成
    public function mergeDataFromAPI($page, $params = array()){
        uses('Xml');
        //パラメータストリングを作成する
        $param_string = $this->makeURLStringOfAPI($params);
        
        $gnavi_xml = new Xml($this->base_uri_gnavi.'&offset_page='.$page . $param_string);
        $gnavi_xml_data = Set::reverse($gnavi_xml);
        $shop_data_from_api = array();        
        
        if( empty($gnavi_xml_data['Gnavi']['Error']) ){
            //データが1件しかないときの暫定処理
            if( count($gnavi_xml_data['Response']['Rest']) > 10 ){
                $tmp_gnavi_data = $gnavi_xml_data['Response']['Rest'];
                $gnavi_xml_data['Response']['Rest'] = array($tmp_gnavi_data);
            }
            
            //レスポンスデータが存在する場合はお店データの配列を作成する
            if( !empty($gnavi_xml_data['Response']) ){
                $i = 1;
                foreach($gnavi_xml_data['Response']['Rest'] as $gnavi_array){
                    //ぐるなびAPIで必要なものだけを抜き出す
                    $shop_data_from_api[$i]['gnavi']['id'] = $gnavi_array['id'];
                    $shop_data_from_api[$i]['gnavi']['name'] = $gnavi_array['name'];
                    $shop_data_from_api[$i]['gnavi']['address'] = $gnavi_array['address'];
                    //定休日
                    if(empty($gnavi_array[$i]['holiday'])){
                        $shop_data_from_api[$i]['gnavi']['holiday'] = '-';
                    }else{
                        $shop_data_from_api[$i]['gnavi']['holiday'] = $gnavi_array['holiday'];
                    }
                    //営業時間
                    if( $gnavi_array['opentime'] == ',' ){
                        $shop_data_from_api[$i]['gnavi']['opentime'] = '-';
                    }else{
                        $shop_data_from_api[$i]['gnavi']['opentime'] = $gnavi_array['opentime'];
                    }
                    //写真
                    if(empty($gnavi_array['ImageUrl']['shop_image1'])){
                        $shop_data_from_api[$i]['gnavi']['image_url'] = '/images/catalogs/no_photo_catalog_1.jpg';
                    }else{
                        if($this->checkImageURLHeader($gnavi_array['ImageUrl']['shop_image1']) == false){
                            $shop_data_from_api[$i]['gnavi']['image_url'] = '/images/catalogs/no_photo_catalog_1.jpg';
                        }else{
                            $shop_data_from_api[$i]['gnavi']['image_url'] = $gnavi_array['ImageUrl']['shop_image1'];  
                        }
                    }
                    //URL
                    $shop_data_from_api[$i]['gnavi']['url'] = $gnavi_array['url'];
                    //電話番号
                    $shop_data_from_api[$i]['gnavi']['tel'] = $gnavi_array['tel'];
                    
                    //googleMap表示用データ
                    $shop_data_from_api[$i]['gnavi']['category'] = $gnavi_array['category'];
                    $shop_data_from_api[$i]['gnavi']['latitude'] = $gnavi_array['latitude'];
                    $shop_data_from_api[$i]['gnavi']['longitude'] = $gnavi_array['longitude'];
                    $shop_data_from_api[$i]['gnavi']['access'] = $this->getAccessSentence($gnavi_array['Access']);
                    
                    //リクルートWebサービス表示用の配列とのつきあわせ
                    $tel_key = preg_replace('/-/i', '', $gnavi_array['tel']);
                    $hotpepper_xml_data = $this->getDataFromRecruitAPI($tel_key);
                    if(empty($hotpepper_xml_data)){
                        $shop_data_from_api[$i]['both_flag'] = false;
                    }else{
                        $shop_data_from_api[$i]['both_flag'] = true;
                        //リクルートWebサービス表示用の配列を準備する
                        $shop_data_from_api[$i]['hotpepper']['url'] = $hotpepper_xml_data['Urls']['pc'];
                    }
                    $i++;
                }
            }
        }
        return $shop_data_from_api;
    }

    //アクセス情報を取得する
    private function getAccessSentence($params = array()){
        $str = "";
        if(!empty($params['line'])){
            $str .= $params['line'];
        }
        if(!empty($params['station'])){
            $str .= $params['station'];
        }
        if(!empty($params['station_exit'])){
            $str .= $params['station_exit'];
        }
        if(!empty($params['walk'])){
            $str .= $params['walk'];
            $str .= '分';
        }
        return $str; 
    }
    
    //ぐるなびWebサービスの大業態マスタを取得
    public function getDataFromGnaviCategoryLargeAPI(){
        uses('Xml');
        $gnavi_category_xml = new Xml($this->base_uri_category_large);
        $gnavi_category_xml_data = Set::reverse($gnavi_category_xml);
        $gnavi_category_array = array();
        foreach($gnavi_category_xml_data['Response']['CategoryL'] as $gnavi_category){
            $gnavi_category_array[$gnavi_category['category_l_code']] = $gnavi_category['category_l_name'];
        }
        return $gnavi_category_array;
    }
    
    //リクルートWebサービスのデータを電話番号をキーにして取得
    private function getDataFromRecruitAPI($tel = null){
        uses('Xml');
        $hotpepper_xml = new Xml($this->base_uri_hotpepper.'&tel='.$tel);
        $hotpepper_xml_data = Set::reverse($hotpepper_xml);
        
        if(empty($hotpepper_xml_data['Results']['Shop'])){
            return array(); 
        }else{
            return $hotpepper_xml_data['Results']['Shop'];
        }        
    }
    
    //画像ファイルのレスポンスヘッダを解析する
    private function checkImageURLHeader($image_url){
        $params = get_headers($image_url);
        $response = $params[0];
        if( preg_match('{HTTP/1.1 200 OK}', $response) ){
            return true;
        }else{
            return false;
        }
    }
    
    //パラメータストリングを作成する
    private function makeURLStringOfAPI( $params = array() ){
        $str = "";
        if( !empty($params) && is_array($params) ){
            //キーワード
            if( !empty($params['keywords']) ){
                $keywords = preg_replace('/　/', ' ', $params['keywords']);   //全角スペースを半角スペースへ
                $keywords = preg_replace('/\s+/', ' ', $keywords);            //連続する半角スペースを1つの半角スペースへ
                $keywords = preg_replace('/ /', ', ', $keywords);             //1つの半角スペースをカンマへ
                $str .= "&freeword=" . $keywords;
            }
            //検索範囲
            if( !empty($params['range'] )){
                $str .= "&range=" . $params['range'];
            }else{
                $str .= "&range=3";
            }
            //カテゴリー
            if( !empty($params['category_l'] )){
                $str .= "&category_l=" . $params['category_l'];
            }
        }
        return $str;
    }
    
}