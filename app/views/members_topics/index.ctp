<article class="specialsArticle">
<header class="specialsTitle">
<h2><img src="/images/common/h2_special.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>大塚のことやお店に関する特集記事のご紹介</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>地域の交流や特色のある活動等、多くの顔を見せる街でもある大塚。その中でも気になった商品やお店に関する情報やイベントの告知／レポートを掲載しています。</p>
</div>
</section>
<?php if(!empty($specials)): ?>
<?php foreach($specials as $special): ?>
<section class="contentList">
<header>
<h4 class="title"><?php echo h($special['Special']['title']); ?></h4>
</header>
<div class="contentDetail">
<p class="kcpy"><?php echo h($special['Special']['kcpy']); ?></p>
<p class="published"><?php echo h($this->Html->dateFormat($special['Special']['published']." 00:00:00")); ?> 公開</p>
<p class="main_image padt10 padr20"><img src="/img/special/resized_<?php echo h($special['Special']['image_main']); ?>" width="300" height="200"></p>
<p class="description_main padt10">
<?php if(mb_strlen($special['Special']['description_main']) > 100): ?>
<?php echo h(mb_substr($special['Special']['description_main'], 0, 100)."..."); ?>
<?php else: ?>
<?php echo h($special['Special']['description_main']); ?>
<?php endif; ?>
</p>
<p class="readMore"><?php echo $this->Html->link('続きを読む', array('action' => 'view', $special['Special']['id'])); ?></p>
</div>
</section>
<?php endforeach; ?>
<?php else: ?>
<section class="contentListNone">
<p class="emptyData">※現在公開中の特集記事はありません。</p>
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