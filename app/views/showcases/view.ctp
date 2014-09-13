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
<link rel="stylesheet" type="text/css" href="/css/cloud-zoom.css" />
<script type="text/javascript" src="/js/cloud-zoom.js"></script>
<script type="text/javascript" src="/js/showcase.js"></script>

<section class="showcaseGalleryPhotoMain clearfix">
<div class="selectedZoomArea displayTarget0">
<a href="/img/showcase/<?php echo h($this->data['Showcase']['image_sub1']); ?>" class="cloud-zoom" rel="position:'inside',titleOpacity:0.6">
<img src="/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub1']); ?>" title="マウスを動かすと画像の位置が拡大表示されます">
</a>
</div>

<div class="selectedZoomArea displayTarget1">
<a href="/img/showcase/<?php echo h($this->data['Showcase']['image_sub2']); ?>" class="cloud-zoom" rel="position:'inside',titleOpacity:0.6">
<img src="/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub2']); ?>" title="マウスを動かすと画像の位置が拡大表示されます">
</a>
</div>

<div class="selectedZoomArea displayTarget2">
<a href="/img/showcase/<?php echo h($this->data['Showcase']['image_sub3']); ?>" class="cloud-zoom" rel="position:'inside',titleOpacity:0.6">
<img src="/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub3']); ?>" title="マウスを動かすと画像の位置が拡大表示されます">
</a>
</div>

<div class="selectedZoomArea displayTarget3">
<a href="/img/showcase/<?php echo h($this->data['Showcase']['image_sub4']); ?>" class="cloud-zoom" rel="position:'inside',titleOpacity:0.6">
<img src="/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub4']); ?>" title="マウスを動かすと画像の位置が拡大表示されます">
</a>
</div>
</section>

<aside class="showcaseGalleryPhotoSub clearfix">
<ul id="thumblist" class="clearfix">
<li class="thumItem">
<a class="zoomThumbActive vtip" href='javascript:void(0);' title="<strong class='vTitle'><span>&raquo;</span>&nbsp;<?php echo h($this->data['Showcase']['caption_sub1']); ?></strong>"><img src='/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub1']); ?>' width="148" height="98"></a>
</li>
<li class="thumItem">
<a class="vtip" href='javascript:void(0);' title="<strong class='vTitle'><span>&raquo;</span>&nbsp;<?php echo h($this->data['Showcase']['caption_sub2']); ?></strong>"><img src='/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub2']); ?>' width="148" height="98"></a>
</li>
<li class="thumItem">
<a class="vtip" href='javascript:void(0);' title="<strong class='vTitle'><span>&raquo;</span>&nbsp;<?php echo h($this->data['Showcase']['caption_sub3']); ?></strong>"><img src='/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub3']); ?>' width="148" height="98"></a>
</li>
<li class="thumItem">
<a class="vtip" href='javascript:void(0);' title="<strong class='vTitle'><span>&raquo;</span>&nbsp;<?php echo h($this->data['Showcase']['caption_sub4']); ?></strong>"><img src='/img/showcase/resized_<?php echo h($this->data['Showcase']['image_sub4']); ?>' width="148" height="98"></a>
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