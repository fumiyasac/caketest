<!-- ## Cake View Content Start ## -->
<article class="memberArticle">
<header class="memberTitle">
<h2><img src="/images/common/h2_memberregist.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3>会員情報登録と会員規約</h3>
</header>
<section>
<div class="forms">
<?php if(!empty($error_announce)): ?>
<p><span class="requierd"><?php echo $error_announce; ?></span></p>
<?php endif; ?>
<p class="memberText">大塚「珍しいもん」Storeでのブログ機能のご利用等の際には会員登録が必要になります。<br>
1度ご登録頂きますと、マイページから当サイトのお得情報やキャンペーン等がご利用頂けます。</p>
<?php echo $this->Form->create('Member', array('type' => 'post', 'action' => 'confirm')); ?>
<p class="magt20"><span class="remarked">●</span>会員情報登録（<span class="requierd">*</span>は必須項目です）</p>
<table cellspacing="0" cellpadding="0" id="formInquiry">
<tr>
<th>希望ユーザー名&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('username',array('class' => 'formArea', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->error('username'); ?>
</td>
</tr>
<tr>
<th>希望パスワード&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->password('pass',array('class' => 'formArea', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->error('pass'); ?>
</td>
</tr>
<tr>
<th>お名前（フルネーム）&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('fullname',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('fullname'); ?>
</td>
</tr>
<tr>
<th>メールアドレス&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('mail',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('mail'); ?>
</td>
</tr>
<tr>
<th>メールアドレス（確認用）&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('mail_conf',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('mail_conf'); ?>
</td>
</tr>
<tr>
<th>性別</th>
<td>
<?php
if($this->Form->value('gender')){
    $value = $this->Form->value('gender');
}else{
    $value = 1;
}
echo $this->Form->input('gender', array('class' => 'radio', 'type' => 'radio', 'options' => Configure::read('GENDER_CONF.flag'), 'legend' => false, 'div' => false, 'label' => false, 'value' => $value));
?>
<?php echo $this->Form->error('gender'); ?>
</td>
</tr>
<tr>
<th>所属名または会社名</th>
<td>
<?php echo $this->Form->text('company',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('company'); ?>
</td>
</tr>
<tr>
<th>サービスの使用目的&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Form->textarea('text',array(
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<?php echo $this->Form->error('text'); ?>
</td>
</tr>
</table>

<p class="magt20"><span class="remarked">●</span>会員規約同意</p>
<table cellspacing="0" cellpadding="0" id="formEnquete">
<tr>
<th>大塚「珍しいもん」Store 会員規約</th>
</tr>
<tr>
<td>
<div class="servicePolicy">
<p>あああああああああああああああああああああああああああああああああああああああああああああ</p>
<p>あああああああああああああああああああああああああああああああああああああああああああああ</p>
<p>あああああああああああああああああああああああああああああああああああああああああああああ</p>
<p>あああああああああああああああああああああああああああああああああああああああああああああ</p>
<p>あああああああああああああああああああああああああああああああああああああああああああああ</p>
<p>あああああああああああああああああああああああああああああああああああああああああああああ</p>
<p>あああああああああああああああああああああああああああああああああああああああああああああ</p>
<p>あああああああああああああああああああああああああああああああああああああああああああああ</p>
</div>
</td>
</tr>
<tr>
<td>
<label for="agreement">
<?php
echo $this->Form->checkbox('agree', array(
    'id' =>'agreement',
    'class' =>'chkbox',
    'type' => 'checkbox',
    'value' => 1, 
    'label' => false, 
    'div' => false
));
?>
&nbsp;会員規約に同意する<span class="requierd">*</span></label>
<?php echo $this->Form->error('agree'); ?>
</td>
</tr>
</table>
<div class="sendButton">
<p><?php echo $this->Form->submit('登録内容を確認する', array('div' => false, 'id' => 'confirmButton')); ?></p>
</div>
<!--/form-->
<?php echo $this->Form->end(); ?>
</div>
</section>
</article>
<!-- # Loop End # -->
</article>