<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>ショーケースの追加確認</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではショーケース一覧を追加することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p>この内容で特集記事を投稿します。よろしいですか？</p>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトル・キャッチコピー・見出しは256文字まで入力可能です。</li>
<li>本文は1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>画像は600×400のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>サブ画像については必ず1152×768にして下さい。</li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('Showcase'); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>ショーケースタイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('title')); ?>
</td>
</tr>
<tr>
<th>ショーケースキャッチコピー&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('kcpy')); ?>
</td>
</tr>
<tr>
<th>ショーケースメイン画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_main']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_main']['tmp_file_name'], 7, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_main']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロードに失敗しました</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>ショーケース本文&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('description_main')); ?>
</td>
</tr>
<tr>
<th>サブ1画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_sub1']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_sub1']['tmp_file_name'], 7, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_sub1']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>サブ1キャプション&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('caption_sub1')); ?>
</td>
</tr>
<tr>
<th>サブ2画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_sub2']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_sub2']['tmp_file_name'], 7, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_sub2']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>サブ2キャプション&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('caption_sub2')); ?>
</td>
</tr>
<tr>
<th>サブ3画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_sub3']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_sub3']['tmp_file_name'], 7, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_sub3']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>サブ3キャプション&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('caption_sub3')); ?>
</td>
</tr>
<tr>
<th>サブ4画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_sub4']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_sub3']['tmp_file_name'], 7, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_sub4']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>サブ4キャプション&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('caption_sub4')); ?>
</td>
</tr>

<tr>
<th>ぐるなび API ID</th>
<td>
<?php echo h($this->Form->value('api_id_gurunabi')); ?>
</td>
</tr>
<tr>
<th>ホットペッパー API ID</th>
<td>
<?php echo h($this->Form->value('api_id_hotpepper')); ?>
</td>
</tr>
<tr>
<th>楽天 API ID</th>
<td>
<?php echo h($this->Form->value('api_id_rakuten')); ?>
</td>
</tr>
<tr>
<th>じゃらん API ID</th>
<td>
<?php echo h($this->Form->value('api_id_jaran')); ?>
</td>
</tr>
<tr>
<th>価格</th>
<td>
<?php echo h($this->Form->value('price')); ?>
</td>
</tr>
<tr>
<th>自由記入項目タイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('other_title')); ?>
</td>
</tr>
<tr>
<th>自由記入項目本文</th>
<td>
<div class="CKEditorContents">
<?php echo $this->Form->value('other_description'); ?>
</div>
</td>
</tr>
<tr>
<th>公開日</th>
<td>
<?php
echo h($this->Html->dateFormat($data['Showcase']['published']['year'].'-'.$data['Showcase']['published']['month'].'-'.$data['Showcase']['published']['day']." 00:00:00"));
?>
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
<?php echo $this->Form->create('Showcase', array('type' => 'file', 'action' => 'control_add')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('Showcase', array('type' => 'file', 'action' => 'control_add_complete')); ?>
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