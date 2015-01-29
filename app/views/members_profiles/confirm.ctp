<!-- ## Cake View Content Start ## -->
<article class="memberArticle">
<header class="memberTitle">
<h2><img src="/images/common/h2_myprofile.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3>プロフィール情報の変更確認</h3>
</header>
<section>
<div class="forms">
<p class="memberText">こちらからブログに掲載されるプロフィールの情報を変更・更新することができます。<br>
プロフィールの公開をしない場合は「非公開」への設定をお願い致します。</p>
<p class="memberText">下記の内容で会員情報を登録します。よろしいですか？</p>
<?php echo $this->Form->create('MembersProfile'); ?>
<p class="magt20"><span class="remarked">●</span>会員プロフィール編集（<span class="requierd">*</span>は必須項目です）</p>
<table cellspacing="0" cellpadding="0" id="formInquiry">
<tr>
<th>サムネイル画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['filename']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($data['MembersProfile']['filename']['name'], 9, 150, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['filename']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php elseif($saveTmpImageResult['filename']['result_code'] == UPLOAD_NO_DATA): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($alreadyAddedImgName['MembersProfile']['filename'], 9, 150, 150, false); ?>
<br>
<span>画像変更なし</span>
<?php endif; ?>
<p><span class="remark-text">※</span>画像は150×150ピクセルにリサイズされます。<br>
<span class="remark-text">※</span>うまくリサイズがされない場合はあらかじめ上記サイズの画像をご用意下さい。</p>
</td>
</tr>
<tr>
<th>ブログや個人サイトのURL&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('link_url')); ?>
</td>
</tr>
<tr>
<th>自己紹介&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->value('description'); ?>
</td>
</tr>
<tr>
<th>公開フラグ&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h(Configure::read("FLAG_CONF.flag.{$this->Form->value('flag')}")); ?>
</td>
</tr>
</table>

<?php echo $this->Form->end(); ?>
<div class="sendButton">
<p>
<?php echo $this->Form->create('MembersProfile', array('type' => 'post', 'action' => 'add')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('MembersProfile', array('type' => 'post', 'action' => 'complete')); ?>
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