<aside class="catalogCommentArticle">
<header class="catalogCommentHeader">
<h4><img src="/images/catalogs/right1_catalog.png" width="300" height="20" alt=""></h4>
</header>
<div class="catalogCommentVisual">
<a href="#"><img src="/images/sample/sample_special_thumb.png" width="300" height="120" alt=""></a>
</div>
<section>
<header>
<h5>特集記事のテスト</h5>
</header>
<div>
<p class="catalogCommentDate">2013年07月08日</p>
<p><a href="/specials/">テストコンテンツを表示しています</a></p>
</div>
</section>
<section>
<header>
<h5>特集記事のテスト</h5>
</header>
<div>
<p class="catalogCommentDate">2013年07月08日</p>
<p><a href="/specials/">テストコンテンツを表示しています</a></p>
</div>
</section>
<section>
<header>
<h5>特集記事のテスト</h5>
</header>
<div>
<p class="catalogCommentDate">2013年07月08日</p>
<p><a href="/specials/">テストコンテンツを表示しています</a></p>
</div>
</section>
</aside>
<!-- ## Cake Element Content End ## -->

<!-- ## Cake Element Content Start ## -->
<aside class="catalogCommentArticle">
<header class="catalogCommentHeader">
<h4><img src="/images/catalogs/right2_catalog.png" width="300" height="20" alt=""></h4>
</header>
<div class="commentWriteArea">
<p class="padb10 authorWrite">投稿者：fumiyasac</p>
<?php
echo $this->Form->textarea('text',array(
    'class'=>'formCommentAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<p class="postCatalogComment">
<a href="#"><img src="/images/catalogs/btn_comment_catalog.png" width="158" height="23" alt=""></a>
</p>
</div>
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
<aside class="adsense">
<img src="/images/sample/sample_adsense.png" width="300" height="255" alt="">
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