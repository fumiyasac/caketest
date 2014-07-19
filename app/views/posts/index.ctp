<article class="newstopicsArticle">
<header class="newstopicsTitle">
<h2><img src="/images/common/h2_posts.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>大塚「珍しいもん」Store発、皆様へのアンケート</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>お題はこのサイトに関することから、大塚に関すること、そして皆さんのライフスタイルに関することまで幅広く扱っています。<br><a class="blue" href="/members/add">会員登録</a>がお済みの方は是非回答にご協力いただけると嬉しく思います。</p>
</div>
</section>
<?php if(!empty($posts)): ?>
<?php foreach($posts as $post): ?>
<section class="contentList">
<header>
<h4 class="title"><?php echo h($post['Post']['title']); ?></h4>
</header>
<div class="contentDetail">
<p class="published">受付期間：<?php echo h($this->Html->dateFormat($post['Post']['start_date']." 00:00:00")); ?>〜<?php echo h($this->Html->dateFormat($post['Post']['end_date']." 00:00:00")); ?> </p>
<div class="padt10">
<?php echo $post['Post']['description']; ?>
</div>
<p class="readMore"><?php echo $this->Html->link('アンケートに回答する', array('action' => 'view', $post['Post']['id'])); ?></p>
</div>
</section>
<?php endforeach; ?>
<?php else: ?>
<section class="contentListNone">
<p class="emptyData">※現在公開中のアンケートはありません。</p>
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