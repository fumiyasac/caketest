<?php
class GourmetMapHelper extends AppHelper{
     
    /**
     * 第1回：大塚グルメマップ用変数
     * API表示用ヘルパー 
     */
    
    //API取得情報のページングを行う
    function apiPagenate($page, $maxDataCount , $conditions = array(), $itemsPerPage = 10){
        $page_tag = "";
        $minPage = 1;
        $maxPage = ceil($maxDataCount / $itemsPerPage);
        $url_parameter = $this->makeLinkParameterForAPI($conditions);
        
        //2ページまでしかないとき
        if($maxPage - $minPage == 1){
            if($page == $minPage){
                 $page_tag .= "<a href='../gourmet_map/?page=" . $page  . $url_parameter  . "  ' class='current'>" . $page . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $maxPage . $url_parameter . "'>最後へ&nbsp;&gt;&gt;</a>";
            }elseif($page == $maxPage){
                 $page_tag .= "<a href='../gourmet_map/?page=". $minPage . $url_parameter  . "'>&lt;&lt;&nbsp;最初へ</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $page . $url_parameter  . "  ' class='current'>" . $page . "</a>";
            }            
        //3ページ以降があるとき
        }elseif( $maxPage - $minPage > 1 ){
            if($page == $minPage){
                 $nextPage = $page + 1;
                 $nextPageEx = $page + 2;
                 $page_tag .= "<a href='../gourmet_map/?page=" . $page . $url_parameter  . "  ' class='current'>" . $page . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $nextPage . $url_parameter  . "'>" . $nextPage . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $nextPageEx . $url_parameter  . "'>" . $nextPageEx . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $maxPage . $url_parameter  . "'>最後へ&nbsp;&gt;&gt;</a>";
            }elseif($page == $maxPage){
                 $prevPage = $page - 1; 
                 $prevPageEx = $page - 2;              
                 $page_tag .= "<a href='../gourmet_map/?page=". $minPage . $url_parameter . "'>&lt;&lt;&nbsp;最初へ</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $prevPageEx . $url_parameter . "'>" . $prevPageEx . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $prevPage . $url_parameter . "'>" . $prevPage . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $page . $url_parameter . "  ' class='current'>" . $page . "</a>";
            }else{
                 $prevPage = $page - 1; 
                 $nextPage = $page + 1;              
                 $page_tag .= "<a href='../gourmet_map/?page=". $minPage . $url_parameter . "'>&lt;&lt;&nbsp;最初へ</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $prevPage . $url_parameter . "'>" . $prevPage . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $page . $url_parameter . "  ' class='current'>" . $page . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $nextPage . $url_parameter . "'>" . $nextPage . "</a>";
                 $page_tag .= "<a href='../gourmet_map/?page=" . $maxPage . $url_parameter . "'>最後へ&nbsp;&gt;&gt;</a>";
            }            
        }
        return $page_tag;
    }

    //リンクパラメータを作成する
    private function makeLinkParameterForAPI( $params = array() ){
        $str = "";
        if( !empty($params) && is_array($params) ){
            //キーワード
            if( !empty($params['keywords']) ){
                $str .= "&freeword=" . $params['keywords'];
            }
            //検索範囲
            if( !empty($params['range']) ){
                $str .= "&range=" . $params['range'];
            }
            //カテゴリー
            if( !empty($params['category_l']) ){
                $str .= "&category_l=" . $params['category_l'];
            }
        }
        return $str;
    }
    
}
?>
