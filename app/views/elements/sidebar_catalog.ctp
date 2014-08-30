<aside class="catalogCommentArticle">
<header class="catalogCommentHeader">
<h4><img src="/images/catalogs/right1_catalog.png" width="300" height="20" alt=""></h4>
</header>
<div class="catalogCommentVisual">
<a href="#"><img src="/images/sample/sample_special_thumb.png" width="300" height="120" alt=""></a>
</div>
<?php $catalogComments = $this->requestAction('catalogs_comments/index/limit:4'); ?>
<?php if( empty($catalogComments) ): ?>
<section>
<div>
<p class="notComment">このカタログへのコメントはありません。</p>
</div>
</section>
<?php else: ?>
<?php foreach ($catalogComments as $catalogComment): ?>
<section>
<header>
<h5><?php echo h($catalogComment['CatalogsComment']['username']); ?>さんのコメント</h5>
</header>
<div>
<p class="catalogCommentDate"><?php echo h($this->Html->dateFormat($catalogComment['CatalogsComment']['published']." 00:00:00")); ?></p>
<p><?php echo mb_strimwidth($catalogComment['CatalogsComment']['text'], 0, 79, '...'); ?></p>
<p class="allRead">&gt;&nbsp;<a href="/catalogs_comments/view/<?php echo $catalogComment['CatalogsComment']['id']; ?>">すべて読む</a></p>
</div>
</section>
<?php endforeach; ?>
<?php endif; ?>
<p class="wholeCommentLink"><a href="/catalogs_comments/search">コメント一覧ページへ</a></p>
</aside>
<!-- ## Cake Element Content End ## -->

<!-- ## Cake Element Content Start ## -->
<aside class="catalogCommentArticle">
<header class="catalogCommentHeader">
<h4><img src="/images/catalogs/right2_catalog.png" width="300" height="20" alt=""></h4>
</header>
<div id="commentResultArea"></div>
<div class="commentWriteArea" id="addComment">
<?php echo $this->Form->create('CatalogsComment', array('type' => 'post', 'action' => 'complete')); ?>
<p class="padb10 authorWrite">投稿者：</p>
<?php echo $this->Form->text('author', array('value' => '', 'id' => 'commentUsername')); ?>
<p class="padt10 padb10 authorWrite">内容：</p>
<?php
echo $this->Form->textarea('text',array(
    'class'=>'formCommentAreaText',
    'rows' => 5,
    'cols' => 40,
    'id' => 'commentText'
));
?>
<?php echo $this->Form->input('catalog_id', array('type' => 'hidden', 'value' => $catalog_id, 'id' => 'commentCatalogId')); ?>
<p class="postCatalogComment">
<?php echo $this->Form->submit('コメントを投稿する', array('div' => false, 'id' => 'commentSubmitButton')); ?>
</p>
<?php echo $this->Form->end(); ?>
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