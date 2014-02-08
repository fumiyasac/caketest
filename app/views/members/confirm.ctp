<!-- ## Cake View Content Start ## -->
<article class="memberArticle">
<header class="memberTitle">
<h2><img src="/images/common/h2_memberregist.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3>会員情報登録の確認</h3>
</header>
<section>
<div class="forms">
<p class="memberText">下記の内容で会員情報を登録します。よろしいですか？</p>
<?php echo $this->Form->create('Member'); ?>
<p class="magt20"><span class="remarked">●</span>会員情報登録（<span class="requierd">*</span>は必須項目です）</p>
<table cellspacing="0" cellpadding="0" id="formInquiry">
<tr>
<th>希望ユーザー名&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('username')); ?>
</td>
</tr>
<tr>
<th>希望パスワード&nbsp;<span class="requierd">*</span></th>
<td>
●●●●●●●●（お忘れなくメモしておいて下さい）
</td>
</tr>
<tr>
<th>メールアドレス&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('mail')); ?>
</td>
</tr>
<tr>
<th>性別&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h(Configure::read("GENDER_CONF.flag.{$this->Form->value('gender')}")); ?>
</td>
</tr>
<tr>
<th>所属名または会社名</th>
<td>
<?php
if($this->Form->value('company')){
    echo $this->Form->value('company');
}else{
    echo '-';
}
?>
</td>
</tr>
<tr>
<th>サービスの使用目的&nbsp;<span class="requierd">*</span></th>
<td>
<?php
if($this->Form->value('text')){
    echo nl2br(h($this->Form->value('text')));
}else{
    echo '-';
}
?>
</td>
</tr>
</table>

<?php echo $this->Form->end(); ?>
<div class="sendButton">
<p>
<?php echo $this->Form->create('Member', array('type' => 'post', 'action' => 'add')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('Member', array('type' => 'post', 'action' => 'complete')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('この内容で登録する', array('div' => false, 'id' => 'completeButton')); ?>
<?php echo $this->Form->end(); ?>
</p>
</div>
<!--/form-->
</div>
</section>
</article>
<!-- # Loop End # -->
</article>