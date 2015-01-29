<article class="specialsArticle">
<header class="specialsTitle">
<h2><img src="/images/common/h2_memberinfo.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseView">
<header>
<h3><?php echo h($this->data['MembersTopic']['title']); ?></h3>
</header>
<section class="introduction">
<div class="spacialMain">
<p class="image_main"><img src="/img/members_topic/resized_<?php echo h($this->data['MembersTopic']['member_topic_image']); ?>"></p>
<!--<p><?php echo h($this->data['Special']['kcpy']); ?></p>-->
<!--<p class="publishDate"><?php echo h($this->Html->dateFormat($this->data['Special']['published']." 00:00:00")); ?> 公開</p>-->
</div>
</section>
<section class="detailOfSpecialMain">
<header>
<h4><?php echo h($this->data['MembersTopic']['kcpy']); ?></h4>
</header>
<div>
<p><?php echo h($this->data['MembersTopic']['description']); ?></p>
</div>
</section>

<?php if($this->data['MembersTopic']['other_description'] !== false): ?>
<section class="detailOfSpecialEtc">
<div>
<p class="etcInfo"><span>■</span> さらに詳しい情報や補足など</p>
<div class="etcContents">
<div class="CKEditorContents">
<?php echo $this->data['MembersTopic']['other_description']; ?>
</div>
</div>
</div>
</section>
<?php endif; ?>

<aside class="afterLinks">
<p class="backLink"><?php echo $this->Html->link('一覧へ戻る', array('action' => 'index')); ?></p>
<p class="publishedDate"><?php echo h($this->Html->dateFormat($this->data['MembersTopic']['published']." 00:00:00")); ?> 公開</p>
</aside>

</article>
<!-- # Loop End # -->
</article>