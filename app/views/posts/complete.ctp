<!-- ## Cake View Content Start ## -->
<article class="postArticle">
<header class="postTitle">
<h2><img src="/images/common/h2_posts.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3><?php echo h($base_data['Post']['title']); ?>のアンケートフォーム</h3>
</header>

<aside class="enquetesMain">
<p class="image_main"><img src="/img/post/resized_<?php echo h($base_data['Post']['post_image']); ?>"></p>
</aside>

<section>
<div class="forms">
<p class="contactText"><?php echo $form_description; ?></p>
<div class="sendButton">
<p>
<?php echo $this->Form->create('Post', array('type' => 'post', 'url' => '/')); ?>
<?php echo $this->Form->submit('TOP画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
</p>
</div>
<!--/form-->
</div>
</section>
</article>
<!-- # Loop End # -->
</article>