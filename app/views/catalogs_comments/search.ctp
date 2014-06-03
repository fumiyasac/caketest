<article class="catalogCommentsArticle">
<header class="catalogCommentsTitle">
<h2><img src="/images/common/h2_catalogs.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>皆様のコメントをご紹介</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>地域の交流や特色のある活動等、多くの顔を見せる街でもある大塚。その中でも気になった商品やお店に関する情報やイベントの告知／レポートを掲載しています。</p>
</div>
</section>

<script type="text/javascript" src="/js/jquery.jtruncsubstr.js"></script>
<script type="text/javascript">
$().ready(function() {
    $('.description_main').jTruncSubstr({
        length: 80,              // 表示する文字数
        minTrail: 0,             // 省略文字の最低文字数
        moreText: "[続きを読む]",  // 省略部分を表示するリンクの文字
        lessText: "[続きを隠す]",  // 省略部分を非表示にするリンクの文字
        ellipsisText: "...",     // 省略部分をあらわす文字
        moreAni: 0,              // 折り広げるスピード
        lessAni: 0               // 折り畳むスピード
    });
    //$('.description_main .clearboth .truncate_more_link').css('color','#683800');
});
</script>

<!-- # Search Box Start #  -->
<aside class="commentSearchBoxArea">
    <form action="/catalogs_comments/search" method="get">
        <p class="commentSearchCountTxt">
            絞り込み検索：現在 <strong class="red"><?php echo $hit_max_count; ?></strong> 件ヒットしました
        </p>
        <table class="commentSearchTbl">
            <tr>
                <th>キーワード：</th>
                <td>
                    <input type="text" name="query" id="commentSearchKeywordTxtBox" class="autoClearKeyword" value="<?php echo $query; ?>">
                    <br>
                    <span style="font-size: 85%;">※本文がキーワード検索対象になります</span>
                </td>
            </tr>
            <tr>
               <th>カタログの種類：</th>
               <td>
                   <select name="catalog_id">
                       <option value="">カタログの種類を選択して下さい</option>
                       <?php foreach ($catalogTitleList as $catalogKey => $catalogValue): ?>
                       <option value="<?php echo $catalogKey; ?>"><?php echo $catalogValue['title']; ?></option>
                       <?php endforeach; ?>
                   </select>
                   <br>
                   <span style="font-size: 85%;">※カタログの種類は現在公開中のものになります</span>
                </td>
            </tr>
        </table>
        <p class="searchAPIButtonArea"><input id="searchAPIButton" type="submit" value=""></p>
    </form>
</aside>
<!-- # Search Box End #  -->

<?php if(!empty($catalogsComments)): ?>
<?php foreach($catalogsComments as $catalogsComments): ?>
<section class="contentList">
<header>
<h4 class="title"><?php echo h($catalogsComments['CatalogsComment']['username']); ?>さんのコメント</h4>
</header>
<div class="contentDetail">
<p class="kcpy">カタログ名：<?php echo $catalogTitleList[$catalogsComments['CatalogsComment']['catalog_id']]['title']; ?></p>
<p class="published"><?php echo h($this->Html->dateFormat($catalogsComments['CatalogsComment']['published']." 00:00:00")); ?> 公開</p>
<p class="description_main padt10">
<?php echo h($catalogsComments['CatalogsComment']['text']); ?>
</p>
<p class="readMore">
<a href="../../catalogs/<?php echo $catalogTitleList[$catalogsComments['CatalogsComment']['catalog_id']]['template']; ?>/">
カタログを見る
</a>
</p>
</div>
</section>
<?php endforeach; ?>
<?php else: ?>
<section class="contentListNone">
<p class="emptyData">※検索条件に合致するコメントはありません。</p>
</section>
<?php endif; ?>
</article>
<!-- # Loop End # -->
</article>

<aside class="pagenationArea">
<p>
<?php
echo $paginator->numbers(
    array(
        'before' => $paginator->first('<<', array('url' => array('?' => 'query='.$query.'&catalog_id='.$catalog_id)) ).'　',
        'after' => '　'.$paginator->last('>>', array('url' => array('?' => 'query='.$query.'&catalog_id='.$catalog_id)) ),
        'modules' => 4,
        'separator' => '・',
        'url' => array('?' => 'query='.$query.'&catalog_id='.$catalog_id)
    )
);
?>
</p>
</aside>

<aside class="commentSubPageArea">
<header>
<h3>大塚Catalogsであなたも盛り上がってみませんか？</h3>
</header>
<div>
<p class="padt5">大塚Catalogsでは、地域に密着したトピックス（ぐるめ、おみせ、まつり等）を紹介しています。<br>コメントを投稿して楽しむもよし、暇つぶしのお供にするもよし、ここで情報を調べてから大塚へ立ち寄ってみるのもよし。楽しみ方はあなた次第！</p>
<p class="padt10"><img src="/images/catalogs/catalogs_footer.jpg" width="600" height="300" alt=""></p>
</div>
</aside>

<?php echo $this->element('catalog_comment_policy'); ?>