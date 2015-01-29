<!-- ## Cake View Content Start ## -->
<article class="memberArticle">
<header class="memberTitle">
<h2><img src="/images/common/h2_myprofile.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3>プロフィール情報の変更</h3>
</header>
<section>
<div class="forms">
<?php if(!empty($error_announce)): ?>
<p><span class="requierd"><?php echo $error_announce; ?></span></p>
<?php endif; ?>
<p class="memberText">こちらからブログに掲載されるプロフィールの情報を変更・更新することができます。<br>
プロフィールの公開をしない場合は「非公開」への設定をお願い致します。</p>
<?php echo $this->Form->create('MembersProfile', array('type' => 'post', 'action' => 'confirm')); ?>
<p class="magt20"><span class="remarked">●</span>会員プロフィール編集（<span class="requierd">*</span>は必須項目です）</p>
<table cellspacing="0" cellpadding="0" id="formInquiry">
<tr>
<th>サムネイル画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('filename',array('class' => 'formArea')); ?>
<div class="padt10">
<?php if(!empty($error_announce)): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($alreadyAddedImgName['MembersProfile']['filename'], 9, 150, 150, false); ?>
<?php else: ?>
<?php echo $this->DisplayImage->displayControlThumbnail($this->data['MembersProfile']['filename'], 9, 150, 150, false); ?>
<?php endif; ?>
<p><span class="remark-text">※</span>画像は150×150ピクセルにリサイズされます。<br>
<span class="remark-text">※</span>うまくリサイズがされない場合はあらかじめ上記サイズの画像をご用意下さい。</p>
</td>
</tr>
<tr>
<th>ブログや個人サイトのURL&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('link_url',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('link_url'); ?>
</td>
</tr>
<tr>
<th>自己紹介&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Form->textarea('description',array(
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<?php echo $this->Form->error('description'); ?>
</td>
</tr>
<tr>
<th>公開フラグ&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->input('flag', array('class' => 'radio', 'type' => 'radio', 'options' => Configure::read('FLAG_CONF.flag'), 'legend' => false, 'div' => false, 'label' => false)); ?>
<?php echo $this->Form->error('flag'); ?>
</td>
</tr>
</table>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<div class="sendButton">
<p><?php echo $this->Form->submit('編集内容を確認する', array('div' => false, 'id' => 'confirmButton')); ?></p>
</div>
<!--/form-->
<?php echo $this->Form->end(); ?>
</div>
</section>
</article>
<!-- # Loop End # -->
</article>