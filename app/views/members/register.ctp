<!-- ## Cake View Content Start ## -->
<article class="contactArticle">
<header class="contactTitle">
<h2><img src="/images/common/h2_memberregist.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3>会員情報本登録</h3>
</header>
<section>
<div class="forms">
<p class="memberText">
<?php if ( $register_flag ) : ?>
メール認証が完了し、会員本登録がされました。<br>
引き続き、「大塚珍しいもんStore」をお楽しみ下さい。
<?php else: ?>
メール認証が完了できませんでした。<br>
1. 仮登録時の返信メールに記載されているURLであるか<br>
2. URLに誤りがないか<br>
をご確認頂ければ幸いです。<br>
<span class="requierd">(ご不明点があればお問い合わせフォームよりお願いします)</span>
<?php endif; ?>
</p>
<div class="padt20"></div>
<div class="sendButton">
<p>
<?php if ( $register_flag ) : ?>
<?php echo $this->Form->create('Member', array('type' => 'post', 'action' => 'login')); ?>
<?php echo $this->Form->submit('ログイン画面へ', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
<?php else: ?>
<?php echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'index')); ?>
<?php echo $this->Form->submit('お問い合わせフォームへ', array('div' => false, 'id' => 'indexButton')); ?>
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