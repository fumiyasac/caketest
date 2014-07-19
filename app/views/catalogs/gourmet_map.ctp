<!-- JS & CSS File Area// -->
<link rel="stylesheet" type="text/css" href="/css/catalog/gourmet_map.css" />
<script src="/js/catalog/gourmet_map.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAFv_wSvWKpcbJicykfR8Zdr4gylj95VEA&sensor=false"></script>
<script type="text/javascript">
//Google Map API v3の表示関数
function initialize() {
    // 位置情報と表示データの組み合わせ
    var mapData = new Array();
    <?php foreach($shop_data as $shop): ?>
    mapData.push({position: new google.maps.LatLng(<?php echo $shop['gnavi']['latitude']; ?>, <?php echo $shop['gnavi']['longitude']; ?>), content: '<div style="width:240px;"><strong style="color:#683800;font-size:93%;"><?php echo $shop['gnavi']['name']; ?></strong><p style="color: #FFA200;font-size:85%;">カテゴリー：<?php echo $shop['gnavi']['category']; ?></p><p style="color:#FFA200;font-size:85%;">交通アクセス：<?php echo $shop['gnavi']['access']; ?></p><p><a class="goList" style="color:#3333FF;text-decoration:underline;font-size:85%;" href="#<?php echo $shop['gnavi']['id']; ?>">このお店のリストへ</a></p></div>'});
    <?php endforeach; ?>

    //マップの描画
    var mapOptions = {
        center: new google.maps.LatLng(35.731010, 139.728688),
        zoom: 
        <?php if($range == 1): ?>
        17
        <?php elseif($range == 2): ?>
        16
        <?php elseif($range == 3): ?>
        15
        <?php elseif($range == 4): ?>
        14
        <?php elseif($range == 5): ?>
        13
        <?php else: ?>
        16
        <?php endif;?>,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var myMap = new google.maps.Map(document.getElementById("gMapSlide"), mapOptions);

    //マーカーピンの設置
    for (i = 0; i < mapData.length; i++) {
    var myMarker = new google.maps.Marker({
      position: mapData[i].position,
      map: myMap
    });
    attachMessage(myMarker, mapData[i].content);
  }
}

//マーカーピン設置用関数
function attachMessage(marker, msg) {
    google.maps.event.addListener(marker, 'click', function(event) {
        new google.maps.InfoWindow({
            content: msg
        }).open(marker.getMap(), marker);
    });
}

$(window).load(function() {
    initialize();
});
</script>
<!-- //JS & CSS File Area -->

<!-- Header Contents Area// -->
<aside class="gMapGallery">
<div id="gMapSlide">
</div>
</aside>
<!-- //Header Contents Area -->

<!-- BreadCramb Contents Area// -->
<aside class="breadCramb">
<div id="bread">
<p>
<?php
foreach($breadcrumb as $val) {
    $option = (isset($val['option'])) ? $val['option'] : array();
    $link = (isset($val['link'])) ? $val['link'] : null;
    $this->Html->addCrumb($val['name'], $link, $option);
}
echo $this->Html->getCrumbs(' > ');
?>
</p>
</div>
</aside>
<!-- //BreadCramb Contents Area -->

<!-- Main Contents Area// -->
<div id="mainContents">
<div id="leftContents">

<!-- ## Cake Element Content Start ## -->
<article class="gourmetInfoArea">
<header>
<h2><img src="/images/catalogs/left_catalog_1.png" width="580" height="20" alt=""></h2>
</header>
<div id="gourmetInfoSlider">

<!--  検索ボックス-->
<section class="gourmetInfoImpl clearfix">
    <aside class="gourmetSearchBox magb10">
        <form action="./" method="get">
            <p class="gourmetSearchCountTxt">
                絞り込み検索：現在 <strong class="red"><?php echo $hit_max_count; ?></strong> 件ヒットしました
            </p>
            <input type="hidden" name="page" value="1">
            <table class="gourmetSearchTbl">
                <tr>
                    <th>キーワード：</th>
                    <td>
                        <input type="text" name="keywords" id="gourmetSearchKeywordTxtBox" class="autoClearKeyword" value="<?php echo $keywords; ?>">
                        <br>
                        <span style="font-size: 85%;">※キーワードは10個まで指定できます</span>
                    </td>
                </tr>
                <tr>
                    <th>カテゴリー：</th>
                    <td>
                        <select name="category_l">
                            <option value="">お店のカテゴリーを選択して下さい</option>
                            <?php foreach ($category_list as $category_code => $category_name): ?>
                            <option value="<?php echo $category_code; ?>" <?php if( $category_l == $category_code): ?>selected<?php endif; ?>><?php echo $category_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <br>
                        <span style="font-size: 85%;">※カテゴリーはぐるなびAPIを基準にしています</span>
                    </td>
                </tr>
                <tr>
                    <th>検索範囲：</th>
                    <td>
                        <?php foreach (GourmetMapComponent::$range_array as $key => $value): ?>
                        <input id="Range<?php echo $key; ?>" class="radio" type="radio" value="<?php echo $key; ?>" name="range" <?php if( $range == $key): ?>checked<?php endif; ?>>
                        <label for="Range<?php echo $key; ?>"><?php echo $value; ?></label>
                        <?php endforeach; ?>
                        <br>
                        <span style="font-size: 85%;">※JR大塚駅からの距離になります [ デフォルトは1km以内 ]</span>
                    </td>
                </tr>
            </table>
            <p class="searchAPIButtonArea"><input id="searchAPIButton" type="submit" value=""></p>
        </form>
    </aside>
</section>

<?php if( !empty($shop_data) ): ?>
<?php foreach($shop_data as $shop): ?>
<section class="gourmetInfoImpl clearfix" id="<?php echo $shop['gnavi']['id']; ?>">
    <!--a name="<?php echo $shop['gnavi']['id']; ?>"></a-->
    <header>
        <h3><?php echo $shop['gnavi']['name']; ?></h3>
    </header>
    <div class="gourmetPhotoFromAPI">
        <img src="<?php echo $shop['gnavi']['image_url']; ?>" width="200" height="150" alt="">
        <?php if( $shop['gnavi']['image_url'] != '/images/catalogs/no_photo_catalog_1.jpg' ): ?>        
        <p>【画像提供：ぐるなび】</p>
        <?php endif; ?>
    </div>
    <div class="gourmetInfoFromAPI">
        <table class="dataFromAPI">
            <tr>
                <th class="odd">住所：</th>
                <td class="odd"><?php echo $shop['gnavi']['address']; ?></td>
            </tr> 
            <tr>
                <th class="even">TEL：</th>
                <td class="even"><?php echo $shop['gnavi']['tel']; ?></td>
            </tr>
            <tr>
                <th class="odd">営業時間：</th>
                <td class="odd"><?php echo $shop['gnavi']['opentime']; ?></td>
            </tr>
            <tr>
                <th class="even">定休日：</th>
                <td class="even"><?php echo $shop['gnavi']['holiday']; ?></td>
            </tr>
        </table>
        <ul>
            <li class="gurunabi"><a href="<?php echo $shop['gnavi']['url']; ?>" target="_blank"><img src="/images/catalogs/gurunabi_catalog_1.png" width="130" height="20" alt=""></a></li>
            <?php if($shop['both_flag']): ?>
            <li class="hotpepper"><a href="<?php echo $shop['hotpepper']['url']; ?>" target="_blank"><img src="/images/catalogs/hotpepper_catalog_1.png" width="150" height="20" alt=""></a></li>
            <?php endif; ?>
        </ul>
    </div>
</section>
<?php endforeach; ?>
<?php else: ?>
<section class="gourmetInfoImpl clearfix">
    <div class="noDataArea">
        <p>条件に合致するお店は見つかりませんでした。</p>
    </div>
</section>
<?php endif; ?>

<aside class="gourmetInfoPagenationArea">
    <div class="pagenationImpl">
            <span>
            <?php
            $conditions = array(
                'keywords' => $keywords ,
                'category_l' => $category_l ,
                'range' => $range
            );
            ?>
            <?php echo $this->GourmetMap->apiPagenate($page, $hit_max_count, $conditions); ?>
            </span>
    </div>
</aside>

<aside class="gourmetInfoOtherArea">
    <header>
        <h3>使用API一覧</h3>
    </header>
    <div>
        <p>このコンテンツでは以下のAPIを使用しています。<br>（店舗の住所情報はぐるなびAPIを基準にしています）</p>
        <p class="padt10">
            <a href="http://webservice.recruit.co.jp/"><img src="http://webservice.recruit.co.jp/banner/hotpepper-m.gif" alt="ホットペッパー Webサービス" width="88" height="35" border="0" title="ホットペッパー Webサービス"></a>
            &nbsp;
            <a href="http://www.gnavi.co.jp/"><img src="http://apicache.gnavi.co.jp/image/rest/b/api_90_35.gif" width="90" height="35" border="0" alt="グルメ情報検索サイト　ぐるなび"></a>
        </p>
    </div>
</aside>

<?php echo $this->element('catalog_comment_policy'); ?>
    
</div>
</article>
<!-- ## Cake Element Content End ## -->
