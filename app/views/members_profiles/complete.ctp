<!-- ## Cake View Content Start ## -->
<article class="contactArticle">
<header class="contactTitle">
<h2><img src="/images/common/h2_memberregist.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3>会員情報仮登録完了</h3>
</header>
<section>
<div class="forms">
<p class="contactText"><?php echo $form_description; ?></p>
<div class="padt10"></div>
<div class="sendButton">
<p>
<?php echo $this->Form->create('Member', array('type' => 'post', 'action' => 'login')); ?>
<?php echo $this->Form->submit('ログイン画面へ', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
</p>
</div>
<!--/form-->
</div>
</section>
</article>
<!-- # Loop End # -->
</article>