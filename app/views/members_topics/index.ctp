<article class="specialsArticle">
<header class="specialsTitle">
<h2><img src="/images/common/h2_memberinfo.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>会員専用情報のご紹介</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>地域の交流や特色のある活動等、多くの顔を見せる街でもある大塚。その中でも気になった商品やお店に関する情報やイベントの告知／レポートを掲載しています。</p>
</div>
</section>
<?php if(!empty($members_topics)): ?>
<?php foreach($members_topics as $members_topic): ?>
<section class="contentList">
<header>
<h4 class="title"><?php echo h($members_topic['MembersTopic']['title']); ?></h4>
</header>
<div class="contentDetail">
<!--<p class="kcpy"><?php echo h($members_topic['MembersTopic']['kcpy']); ?></p>-->
<p class="published"><?php echo h($this->Html->dateFormat($members_topic['MembersTopic']['published']." 00:00:00")); ?> 公開</p>
<p class="main_image padt10 padr20"><img src="/img/members_topic/resized_<?php echo h($members_topic['MembersTopic']['member_topic_image']); ?>" width="300" height="150"></p>
<p class="description_main padt10">
<?php if(mb_strlen($members_topic['MembersTopic']['description']) > 100): ?>
<?php echo h(mb_substr($members_topic['MembersTopic']['description'], 0, 100)."..."); ?>
<?php else: ?>
<?php echo h($members_topic['MembersTopic']['description']); ?>
<?php endif; ?>
</p>
<p class="readMore"><?php echo $this->Html->link('続きを読む', array('action' => 'view', $members_topic['MembersTopic']['id'])); ?></p>
</div>
</section>
<?php endforeach; ?>
<?php else: ?>
<section class="contentListNone">
<p class="emptyData">※現在公開中の会員専用情報はありません。</p>
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