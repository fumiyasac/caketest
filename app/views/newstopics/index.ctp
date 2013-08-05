<article class="newstopicsArticle">
<header class="newstopicsTitle">
<h2><img src="/images/common/h2_newstopics.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>大塚「珍しいもん」Storeからのニュース&amp;トピックス一覧</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>当サイトからの最新情報やメディアへの掲載情報を掲載しています。メンテナンス情報やサイトからのお得な情報等もこちらで掲載していますので、是非チェックしてみて下さい。</p>
</div>
</section>
<?php if(!empty($newstopics)): ?>
<?php foreach($newstopics as $newstopic): ?>
<section class="contentList">
<header>
<h4 class="title"><?php echo h($newstopic['Newstopic']['title']); ?></h4>
</header>
<div class="contentDetail">
<p class="published"><?php echo h($this->Html->dateFormat($newstopic['Newstopic']['published']." 00:00:00")); ?> 公開</p>
<div class="padt10">
<?php if(mb_strlen($newstopic['Newstopic']['description']) > 100): ?>
<?php echo mb_substr($newstopic['Newstopic']['description'], 0, 100) . "..."; ?>
<?php else: ?>
<?php echo $newstopic['Newstopic']['description']; ?>
<?php endif; ?>
</div>
<p class="readMore"><?php echo $this->Html->link('続きを読む', array('action' => 'view', $newstopic['Newstopic']['id'])); ?></p>
</div>
</section>
<?php endforeach; ?>
<?php else: ?>
<section class="contentListNone">
<p class="emptyData">※現在公開中のニュース&トピックはありません。</p>
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