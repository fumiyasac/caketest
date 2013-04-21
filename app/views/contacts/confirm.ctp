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
<p class="contactText">下記の内容でお問い合わせ内容を送信します。よろしいですか？</p>
<?php echo $this->Form->create('Contact'); ?>
<p class="magt20"><span class="remarked">●</span>お問い合わせ内容（<span class="requierd">*</span>は必須項目です）</p>
<table cellspacing="0" cellpadding="0" id="formInquiry">
<tr>
<th>お名前&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('name')); ?>
</td>
</tr>
<tr>
<th>フリガナ&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('kana')); ?>
</td>
</tr>
<tr>
<th>メールアドレス&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('mail')); ?>
</td>
</tr>
<tr>
<th>メールアドレス（確認用）&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('mail_conf')); ?>
</td>
</tr>
<tr>
<th>お問い合わせ内容&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h(Configure::read("CONTACT_CONF.title.{$this->Form->value('purpose')}")); ?>

<?php if(!empty($data['Contact']['purpose_etc'])): ?>
<br />内容:
<?php echo h($this->Form->value('purpose_etc')); ?>
<?php endif; ?>

</td>
</tr>
<tr>
<th>本文&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo nl2br(h($this->Form->value('text'))); ?>
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
<?php echo h(Configure::read("ENQUETE_CONF.enquete1.{$this->Form->value('enquete1')}")); ?>
</td>
</tr>
<tr>
<th>Q2. あなたの職業の業種を選択して下さい。</th>
</tr>
<tr>
<td>
<?php echo h(Configure::read("ENQUETE_CONF.enquete2.{$this->Form->value('enquete2')}")); ?>
</td>
</tr>
<tr>
<th>Q3. 現在のストアでご興味のある商品はありますか？</th>
</tr>
<tr>
<td>
<?php echo h($this->Form->value('enquete3')); ?>
</td>
</tr>
<tr>
<th>Q4. あなたがよく利用しているオンラインショップは何ですか？</th>
</tr>
<tr>
<td>
<?php echo h($this->Form->value('enquete4')); ?>
</td>
</tr>
<tr>
<th>Q5. Q4のオンラインショップをよく利用する理由があればお答え下さい。</th>
</tr>
<tr>
<td>
<?php echo nl2br(h($this->Form->value('enquete5'))); ?>
</td>
</tr>
</table>
<?php echo $this->Form->end(); ?>
<div class="sendButton">
<p>
<?php echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'index')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'complete')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('この内容で送信する', array('div' => false, 'id' => 'completeButton')); ?>
<?php echo $this->Form->end(); ?>
</p>
</div>
<!--/form-->
</div>
</section>
</article>
<!-- # Loop End # -->
</article>