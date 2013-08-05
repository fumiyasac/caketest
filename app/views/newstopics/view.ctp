<article class="newstopicsArticle">
<header class="newstopicsTitle">
<h2><img src="/images/common/h2_newstopics.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseView">
<header>
<h3><?php echo h($this->data['Newstopic']['title']); ?></h3>
</header>
<section class="introduction">
<div class="newstopicMain">
<p class="image_main"><img src="/img/newstopic/resized_<?php echo h($this->data['Newstopic']['newstopic_image']); ?>"></p>
</div>
</section>
<section class="detailOfNewstopicMain">
<div>
<?php echo $this->data['Newstopic']['description']; ?>
</div>
</section>
<aside class="afterLinks">
<p class="backLink"><?php echo $this->Html->link('一覧へ戻る', array('action' => 'index')); ?></p>
<p class="publishedDate"><?php echo h($this->Html->dateFormat($this->data['Newstopic']['published']." 00:00:00")); ?> 公開</p>
</aside>
</article>
<!-- # Loop End # -->
</article>