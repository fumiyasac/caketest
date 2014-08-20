<article class="showcasesArticle">
<header class="showcasesTitle">
<h2><img src="/images/common/h2_favorite.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseView">
<header>
<h3><?php echo h($this->data['Showcase']['title']); ?></h3>
</header>
<section class="introduction">
<div class="showcaseMain">
<p class="image_main"><img src="/img/showcase/resized_<?php echo h($this->data['Showcase']['image_main']); ?>"></p>
</div>

<!-- Start of ShowcaseMain// -->
</section>
<section class="detailOfShowcaseMain">
<header>
<h4><?php echo h($this->data['Showcase']['kcpy']); ?></h4>
</header>
<div>
<p class="price">￥<?php echo h($this->data['Showcase']['price']); ?></p>
<p><?php echo h($this->data['Showcase']['description_main']); ?></p>
</div>
</section>
<!-- //End of ShowcaseMain -->

<!-- Start of detailOfShowcaseSub// -->
<section class="detailOfShowcaseSub">
<header>
<h4>フォトギャラリー</h4>
</header>
<div>
<p class="padb10">表示されている画像にマウスを置くと拡大表示されます。<br>下のサムネイル画像をクリックして画像を切り替えることもできます。</p>
<link rel="stylesheet" type="text/css" href="/css/jquery.jqzoom.css" />
<script type="text/javascript" src="/js/jquery.jqzoom-core.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.jqzoom').jqzoom({
     	zoomType: 'innerzoom',
        lens:true,
        title: false,
        preloadImages: false,
        alwaysOn:false
    });	
});
</script>
<section class="showcaseGalleryPhotoMain clearfix">
<a href="/img/showcase/<?php echo h($this->data['Showcase']['image_sub1']); ?>" class="jqzoom" rel='gal1'>
<img src="/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub1']); ?>">
</a>
</section>
<aside class="showcaseGalleryPhotoSub clearfix">
<ul id="thumblist" class="clearfix">
<li>
<a class="zoomThumbActive vtip" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub1']); ?>',largeimage: '/img/showcase/<?php echo h($this->data['Showcase']['image_sub1']); ?>'}" title="<strong class='vTitle'><span>&raquo;</span>&nbsp;<?php echo h($this->data['Showcase']['caption_sub1']); ?></strong>"><img src='/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub1']); ?>' width="148" height="98"></a>
</li>
<li>
<a class="vtip" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub2']); ?>',largeimage: '/img/showcase/<?php echo h($this->data['Showcase']['image_sub2']); ?>'}" title="<strong class='vTitle'><span>&raquo;</span>&nbsp;<?php echo h($this->data['Showcase']['caption_sub2']); ?></strong>"><img src='/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub2']); ?>' width="148" height="98" title="aaa"></a>
</li>
<li>
<a class="vtip" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub3']); ?>',largeimage: '/img/showcase/<?php echo h($this->data['Showcase']['image_sub3']); ?>'}" title="<strong class='vTitle'><span>&raquo;</span>&nbsp;<?php echo h($this->data['Showcase']['caption_sub3']); ?></strong>"><img src='/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub3']); ?>' width="148" height="98"></a>
</li>
<li>
<a class="vtip" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub4']); ?>',largeimage: '/img/showcase/<?php echo h($this->data['Showcase']['image_sub4']); ?>'}" title="<strong class='vTitle'><span>&raquo;</span>&nbsp;<?php echo h($this->data['Showcase']['caption_sub4']); ?></strong>"><img src='/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub4']); ?>' width="148" height="98"></a>
</li>
</ul>
</aside>
</div>
</section>
<!-- //End of detailOfShowcaseSub -->

<!-- Start of detailOfShowcaseApi// -->
<!-- //End of detailOfShowcaseApi -->

<!--
<section class="detailOfSpecialSub">
<header>
<h4><?php echo h($this->data['Special']['title_sub1']); ?></h4>
</header>
<div>
<p class="image_sub padl20"><img src="/img/special/resized_<?php echo h($this->data['Special']['image_sub1']); ?>"></p>
<p><?php echo h($this->data['Special']['description_sub1']); ?></p>
</div>
</section>
<section class="detailOfSpecialSub">
<header>
<h4><?php echo h($this->data['Special']['title_sub2']); ?></h4>
</header>
<div>
<p class="image_sub padl20"><img src="/img/special/resized_<?php echo h($this->data['Special']['image_sub2']); ?>"></p>
<p><?php echo h($this->data['Special']['description_sub2']); ?></p>
</div>
</section>
<section class="detailOfSpecialSub">
<header>
<h4><?php echo h($this->data['Special']['title_sub3']); ?></h4>
</header>
<div>
<p class="image_sub padl20"><img src="/img/special/resized_<?php echo h($this->data['Special']['image_sub3']); ?>"></p>
<p><?php echo h($this->data['Special']['description_sub3']); ?></p>
</div>
</section>
-->

<!-- Start of ShowcaseMain// -->
<section class="detailOfShowcaseEtc">
<div>
<p class="etcInfo"><span>■</span> <?php echo $this->data['Showcase']['other_title']; ?></p>
<div class="etcContents">
<div class="CKEditorContents">
<?php echo $this->data['Showcase']['other_description']; ?>
</div>
</div>
</div>
</section>
<!-- //End of ShowcaseMain -->

<aside class="afterLinks">
<p class="backLink"><?php echo $this->Html->link('一覧へ戻る', array('action' => 'index')); ?></p>
<p class="publishedDate"><?php echo h($this->Html->dateFormat($this->data['Showcase']['published']." 00:00:00")); ?> 公開</p>
</aside>
</article>
<!-- # Loop End # -->
</article>