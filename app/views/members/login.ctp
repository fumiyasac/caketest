<!-- ## Cake View Content Start ## -->
<article class="contactArticle">
<header class="contactTitle">
<h2><img src="/images/common/h2_login.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3>メンバー専用ページへログインする</h3>
</header>
<section>
<div class="forms">
<p class="contactText">
ブログ機能／メンバー専用掲示板機能を利用の際は<a class="blue" href="/members/add">会員登録</a>が必要になります。<br>
パスワードを忘れた方は<a class="blue" href="/members/password_reminder">こちら</a>からご確認いただけます。
</p>
<?php if($session->check('Message.auth')): ?>
<?php print($session->flash('auth')); ?>
<?php endif; ?>

<?php echo $this->Form->create('Member', array('type' => 'post', 'action' => 'login')); ?>
<p class="magt20"><span class="remarked">●</span>メンバー情報（<span class="requierd">*</span>は必須項目です）</p>
<table cellspacing="0" cellpadding="0" id="formInquiry">
<tr>
<th>ユーザー名&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('username',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('username'); ?>
</td>
</tr>
<tr>
<th>パスワード&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('password',array('type' => 'password' , 'class' => 'formArea')); ?>
<?php echo $this->Form->error('password'); ?>
</td>
</tr>
</table>
<div class="sendButton">
<p><?php echo $this->Form->submit('ログインする', array('div' => false, 'id' => 'confirmButton')); ?></p>
</div>
<!--/form-->
<?php echo $this->Form->end(); ?>
</div>
</section>
</article>
<!-- # Loop End # -->
</article>