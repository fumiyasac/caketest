<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>アンケートの編集確認</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではアンケート内容を編集することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p>この内容でアンケートを編集します。よろしいですか？</p>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトルは256文字まで入力可能です。</li>
<li>本文は1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>メイン画像は600×200のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>(横のピクセル)=3×(縦のピクセル)の比率であれば綺麗にリサイズされます。</li>
<li><span class="requierd">注意</span>&nbsp;画像の変更を行わない場合は写真を入力しないで下さい。</li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('Post'); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>タイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('title')); ?>
</td>
</tr>
<tr>
<th>内容&nbsp;<span class="requierd">*</span></th>
<td>
<div class="CKEditorContents">
<?php echo $this->Form->value('description'); ?>
</div>
</td>
</tr>
<tr>
<th>サムネイル画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['post_image']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($data['Post']['post_image']['name'], 6, 50, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['post_image']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php elseif($saveTmpImageResult['post_image']['result_code'] == UPLOAD_NO_DATA): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($alreadyAddedImgName['Post']['post_image'], 6, 50, 150, false); ?>
<br>
<span>画像変更なし</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>開始日</th>
<td>
<?php echo h($this->Html->dateFormat($data['Post']['start_date']['year'].'-'.$data['Post']['start_date']['month'].'-'.$data['Post']['start_date']['day']." 00:00:00")); ?>
</td>
</tr>
<tr>
<th>終了日</th>
<td>
<?php echo h($this->Html->dateFormat($data['Post']['end_date']['year'].'-'.$data['Post']['end_date']['month'].'-'.$data['Post']['end_date']['day']." 00:00:00")); ?>
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
<?php echo $this->Form->create('Post', array('type' => 'file', 'action' => 'control_edit')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('Post', array('type' => 'file', 'action' => 'control_edit_complete')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('この内容で登録する', array('div' => false, 'id' => 'completeButton')); ?>
<?php echo $this->Form->end(); ?>
</p>
</div>
</div>
</div>
</section>
</article>
<!-- # Loop Start #  -->

<!-- # Loop End # -->
</article>
<!-- ## Cake View Content End ## -->