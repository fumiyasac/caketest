<article class="specialsArticle">
<header class="specialsTitle">
<h2><img src="/images/common/h2_special.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseView">
<header>
<h3><?php echo h($this->data['Special']['title']); ?></h3>
</header>
<section class="introduction">
<div class="spacialMain">
<p class="image_main"><img src="/img/special/resized_<?php echo h($this->data['Special']['image_main']); ?>"></p>
<!--<p><?php echo h($this->data['Special']['kcpy']); ?></p>-->
<!--<p class="publishDate"><?php echo h($this->Html->dateFormat($this->data['Special']['published']." 00:00:00")); ?> 公開</p>-->
</div>
</section>
<section class="detailOfSpecialMain">
<header>
<h4><?php echo h($this->data['Special']['kcpy']); ?></h4>
</header>
<div>
<p><?php echo h($this->data['Special']['description_main']); ?></p>
</div>
</section>
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
<?php if($this->data['Special']['other_description'] !== false): ?>
<section class="detailOfSpecialEtc">
<div>
<p class="etcInfo"><span>■</span> その他の情報</p>
<div class="etcContents"><?php echo h($this->data['Special']['other_description']); ?></div>
</div>
</section>
<?php endif; ?>
<aside class="afterLinks">
<p class="backLink"><?php echo $this->Html->link('一覧へ戻る', array('action' => 'index')); ?></p>
<p class="publishedDate"><?php echo h($this->Html->dateFormat($this->data['Special']['published']." 00:00:00")); ?> 公開</p>
</aside>
</article>
<!-- # Loop End # -->
</article>