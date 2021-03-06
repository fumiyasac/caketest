<aside class="specialArticle">
<header class="specialHeader">
<h4><img src="/images/common/h4_other.png" width="300" height="40" alt=""></h4>
</header>
<div class="specialVisual">
<a href="#"><img src="/images/sample/sample_special_thumb.png" width="300" height="119" alt=""></a>
</div>

<?php if($member_page_flag): ?>
<!-- 会員ページサイドバー（はじめ） -->
<section class="membersNav">
<ul>
<li><a href="/members_topics/index">会員専用お知らせ一覧</a></li>
<li><a href="/members_profiles/index">マイプロフィール</a></li>
<!--<li><a href="#">マイクーポン情報</a></li>-->
<!--<li><a href="/members/edit">ユーザー情報設定</a></li>-->
<!--<li><a href="#">マイブログ一覧</a></li>-->
<!--<li><a href="#">会員の心得</a></li>-->
</ul>
</section>
<!-- 会員ページサイドバー（おわり） -->
<?php else: ?>
<!-- 非会員ページサイドバー（はじめ） -->
<?php $posts = $this->requestAction('posts/index/limit:2'); ?>
<?php foreach ($posts as $post): ?>
<section class="contentsNav">
<header>
<h5><?php echo h($post['Post']['title']); ?>【アンケート】</h5>
</header>
<div>
<p class="specialDate">
期間：
<?php echo h($this->Html->dateFormat($post['Post']['start_date']." 00:00:00")); ?>
〜
<?php echo h($this->Html->dateFormat($post['Post']['end_date']." 00:00:00")); ?>
</p>
<p>&gt;&nbsp;<a href="/posts/view/<?php echo h($post['Post']['id']); ?>">アンケートに回答する</a></p>
</div>
</section>
<?php endforeach; ?>
<?php $specials = $this->requestAction('specials/index/limit:2'); ?>
<?php foreach ($specials as $special): ?>
<section class="contentsNav">
<header>
<h5><?php echo h($special['Special']['title']); ?>【特集記事】</h5>
</header>
<div>
<p class="specialDate"><?php echo h($this->Html->dateFormat($special['Special']['published']." 00:00:00")); ?></p>
<p>&gt;&nbsp;<a href="/specials/view/<?php echo h($special['Special']['id']); ?>">この記事を見る</a></p>
</div>
</section>
<?php endforeach; ?>
<!-- 非会員ページサイドバー（おわり） -->
<?php endif; ?>

</aside>
<!-- ## Cake Element Content End ## -->
<!-- ## Cake Element Content Start ## -->
<aside class="searchArea">

<aside class="adsense">
<img src="/images/sample/sample_adsense.png" width="300" height="255" alt="">
</aside>

<header class="searchHeader">
<h4><img src="/images/common/h4_search.png" width="300" height="40" alt=""></h4>
</header>
<section class="keywords">
<header>
<h5><img src="/images/common/h5_keyword.png" width="300" height="20" alt=""></h5>
</header>
<div>
<form id="searchForm" method="get" action="./">
<input type="text" name="q" id="keywordsArea" class="autoClear" value="キーワードを入力して下さい">
<input type="submit" id="searchButton" value="">
</form>
</div>
</section>
<section class="categories">
<header>
<h5><img src="/images/common/h5_category.png" width="300" height="20" alt=""></h5>
</header>
<div>
<ul>
<li><a href="#">新商品紹介</a></li>
<li><a href="#">商品のうらばなし</a></li>
<li><a href="#">大塚のおいしいお店</a></li>
</ul>
</div>
</section>
<!--
<section class="tagclouds">
<header>
<h5><img src="/images/common/h5_tagcloud.png" width="300" height="20" alt=""></h5>
</header>
<div>
<div class="tagArea">
<a href="#">お店のご紹介（22）</a>　<a href="#">大塚ものがたり（17）</a>　<a href="#">つけめん（5）</a>　<a href="#">Shisui deux（3）</a> 
</div>
</div>
</section>
-->
</aside>
<!-- ## Cake Element Content End ## -->
<aside class="siteBanner">
<?php $banners = $this->requestAction('banners/index/limit:4'); ?>
<?php foreach ($banners as $banner): ?>
<?php if($banner['Banner']['blank_flag'] == 1): ?>
<?php $attr = 'target="_blank"'; ?>
<?php else: ?>
<?php $attr = ''; ?>
<?php endif; ?>
<div class="padb10">
<a href="<?php echo $banner['Banner']['link_url']; ?>" <?php echo $attr; ?>><img src="/img/banner/<?php echo $banner['Banner']['banner_image']; ?>" width="300" height="80" alt=""></a>
<p><strong class="siteBannerTitle"><?php echo $banner['Banner']['title']; ?></strong></p>
<p><?php echo $banner['Banner']['description']; ?></p>
</div>
<?php endforeach; ?>
</aside>
<!-- ## Cake Element Content Start ## -->
<aside class="anotherInfo">
<div class="tabButton">
<p id="shopTab"><a href="#shopTabView">ニュース&amp;トピック</a></p>
<p id="catalogTab"><a href="#catalogTabView">カタログ情報</a></p>
</div>
<div class="tabView">
<section id="shopTabView">
<header><h4>当ブログからの最新情報はこちら</h4></header>
<div>
<ul>
<?php $newstopics = $this->requestAction('newstopics/index/limit:4'); ?>
<?php foreach ($newstopics as $newstopic): ?>
<li><date><?php echo h($this->Html->dateFormat($newstopic['Newstopic']['published']." 00:00:00")); ?></date><br><a href="/newstopics/view/<?php echo h($newstopic['Newstopic']['id']); ?>"><?php echo h($newstopic['Newstopic']['title']); ?></a></li>
<?php endforeach; ?>
</ul>
</div>
</section>
<section id="catalogTabView">
<header><h4>大塚Catalogs</h4></header>
<div>
<ul>
<?php $catalogs = $this->requestAction('catalogs/index/limit:4'); ?>
<?php foreach ($catalogs as $catalog): ?>
<li><date><?php echo h($this->Html->dateFormat($catalog['Catalog']['published']." 00:00:00")); ?></date><br><a href="/catalogs/view/<?php echo h($catalog['Catalog']['id']); ?>"><?php echo h($catalog['Catalog']['title']); ?></a></li>
<?php endforeach; ?>
</ul>
</div>
</section>
</div>
</aside>
<!-- ## Cake Element Content End ## -->