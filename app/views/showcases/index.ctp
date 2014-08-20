<article class="showcasesArticle">
<header class="showcasesTitle">
<h2><img src="/images/common/h2_favorite.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>「お気に入り」ショーケースのご紹介</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>当Webサービスの作者が良く行くお店や大塚に関するお気に入りの商品や食べ物を紹介します。多少の独断と偏見が入っているかもわかりませんが、そこらへんは「ご愛嬌」ということでよろしくお願いします（笑）</p>
</div>
</section>
<?php if(!empty($showcases)): ?>
<?php foreach($showcases as $showcase): ?>
<section class="contentList">
<header>
<h4 class="title"><?php echo h($showcase['Showcase']['title']); ?></h4>
</header>
<div class="contentDetail">
<p class="kcpy"><?php echo h($showcase['Showcase']['kcpy']); ?></p>
<p class="price">￥<?php echo h($showcase['Showcase']['price']); ?></p>
<p class="published"><?php echo h($this->Html->dateFormat($showcase['Showcase']['published']." 00:00:00")); ?> 公開</p>
<p class="main_image padt10 padr20"><img src="/img/showcase/resized_<?php echo h($showcase['Showcase']['image_main']); ?>" width="300" height="200"></p>
<p class="description_main padt10">
<?php if(mb_strlen($showcase['Showcase']['description_main']) > 100): ?>
<?php echo h(mb_substr($showcase['Showcase']['description_main'], 0, 100)."..."); ?>
<?php else: ?>
<?php echo h($showcase['Showcase']['description_main']); ?>
<?php endif; ?>
</p>
<p class="readMore"><?php echo $this->Html->link('続きを読む', array('action' => 'view', $showcase['Showcase']['id'])); ?></p>
</div>
</section>
<?php endforeach; ?>
<?php else: ?>
<section class="contentListNone">
<p class="emptyData">※現在公開中のショーケースはありません。</p>
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
        'before' => $paginator->first('<<').'　',
        'after' => '　'.$paginator->last('>>'),
        'modules' => 4,
        'separator' => '・',
    )
);
?>
</p>
</aside>