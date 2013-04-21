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
<?php if(!empty($error_announce)): ?>
<p><span class="requierd"><?php echo $error_announce; ?></span></p>
<?php endif; ?>
<p class="contactText">当ブログ及び大塚「珍しいもん」Storeをご覧頂きありがとうございます。<br>商品に関すること、このWebサイトに関すること、または「こういう機能が欲しい」等、お気づきになった点がありましたらお問い合わせ下さい。<br>（アンケートにもご回答頂ければ幸いです。）
</p>
<?php echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'confirm')); ?>
<p class="magt20"><span class="remarked">●</span>お問い合わせ内容（<span class="requierd">*</span>は必須項目です）</p>
<table cellspacing="0" cellpadding="0" id="formInquiry">
<tr>
<th>お名前&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('name',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('name'); ?>
</td>
</tr>
<tr>
<th>フリガナ&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('kana',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('kana'); ?>
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
<th>お問い合わせ内容&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Form->input('purpose', array(
    'div' => false, 
    'type' => 'select', 
    'empty' => '--お問い合わせ内容を選択--', 
    'options' => Configure::read('CONTACT_CONF.title'),
    'label' => false
));
?>
<br>（その他を選択した方は記入して下さい）
<?php echo $this->Form->text('purpose_etc',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('purpose_etc'); ?>
</td>
</tr>
<tr>
<th>本文&nbsp;<span class="requierd">*</span></th>
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

<p class="magt20"><span class="remarked">●</span>アンケート内容（必須項目ではありません）</p>
<table cellspacing="0" cellpadding="0" id="formEnquete">
<tr>
<th>Q1. あなたのご年齢を選択して下さい。</th>
</tr>
<tr>
<td>
<?php
echo $this->Form->input('enquete1', array(
    'div' => false, 
    'type' => 'select', 
    'empty' => '--あなたの年齢を選択--', 
    'options' => Configure::read('ENQUETE_CONF.enquete1'),
    'label' => false
));
?>
</td>
</tr>
<tr>
<th>Q2. あなたの職業の業種を選択して下さい。</th>
</tr>
<tr>
<td>
<?php
echo $this->Form->input('enquete2', array(
    'div' => false, 
    'type' => 'select', 
    'empty' => '--あなたの業種を選択--', 
    'options' => Configure::read('ENQUETE_CONF.enquete2'),
    'label' => false
));
?>
</td>
</tr>
<tr>
<th>Q3. 現在のストアでご興味のある商品はありますか？</th>
</tr>
<tr>
<td>
<?php echo $this->Form->text('enquete3',array('class' => 'formArea')); ?>
</td>
</tr>
<tr>
<th>Q4. あなたがよく利用しているオンラインショップは何ですか？</th>
</tr>
<tr>
<td>
<?php echo $this->Form->text('enquete4',array('class' => 'formArea')); ?>
</td>
</tr>
<tr>
<th>Q5. Q4のオンラインショップをよく利用する理由があればお答え下さい。</th>
</tr>
<tr>
<td>
<?php
echo $this->Form->textarea('enquete5',array(
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
</td>
</tr>
</table>
<div class="sendButton">
<p><?php echo $this->Form->submit('送信内容を確認する', array('div' => false, 'id' => 'confirmButton')); ?></p>
</div>
<!--/form-->
<?php echo $this->Form->end(); ?>
</div>
</section>
</article>
<!-- # Loop End # -->
</article>