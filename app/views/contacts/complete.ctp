<!-- ## Cake View Content Start ## -->
<article class="contactArticle">
<header class="contactTitle">
<h2><img src="/images/common/h2_contact.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3>みなさまのご意見・ご要望をお待ちしています！</h3>
</header>
<section>
<div class="forms">
<p class="contactText"><?php echo $form_description; ?></p>
<div class="sendButton">
<p>
<?php if($complete_link == 0): ?>
<?php echo $this->Form->create('Contact', array('type' => 'post', 'url' => '../archives/index')); ?>
<?php echo $this->Form->submit('TOP画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
<?php else: ?>
<?php echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'index')); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
<?php endif; ?>
</p>
</div>
<!--/form-->
</div>
</section>
</article>
<!-- # Loop End # -->
</article>