<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>特集記事の追加確認</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは特集記事一覧を追加することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p>この内容で特集記事を投稿します。よろしいですか？</p>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトル・キャッチコピー・見出しは256文字まで入力可能です。</li>
<li>本文は1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>メイン画像は600×400、その他の画像は300×200のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>(横のピクセル)=1.5×(縦のピクセル)の比率であれば綺麗にリサイズされます。</li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('Special'); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>特集記事タイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('title')); ?>
</td>
</tr>
<tr>
<th>特集記事キャッチコピー&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('kcpy')); ?>
</td>
</tr>
<tr>
<th>特集記事メイン画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_main']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_main']['tmp_file_name'], 1, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_main']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロードに失敗しました</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>特集記事本文&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('description_main')); ?>
</td>
</tr>

<tr>
<th>見出し(サブ1)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('title_sub1')); ?>
</td>
</tr>
<tr>
<th>画像(サブ1)&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_sub1']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_sub1']['tmp_file_name'], 1, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_sub1']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>本文(サブ1)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('description_sub1')); ?>
</td>
</tr>

<tr>
<th>見出し(サブ2)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('title_sub2')); ?>
</td>
</tr>
<tr>
<th>画像(サブ2)&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_sub2']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_sub2']['tmp_file_name'], 1, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_sub2']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>本文(サブ2)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('description_sub2')); ?>
</td>
</tr>

<tr>
<th>見出し(サブ3)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('title_sub3')); ?>
</td>
</tr>
<tr>
<th>画像(サブ3)&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['image_sub3']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['image_sub3']['tmp_file_name'], 1, 100, 150, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['image_sub3']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロード失敗！</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>本文(サブ3)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('description_sub3')); ?>
</td>
</tr>

<tr>
<th>本文(その他)</th>
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
echo h($this->Html->dateFormat($data['Special']['published']['year'].'-'.$data['Special']['published']['month'].'-'.$data['Special']['published']['day']." 00:00:00"));
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
<?php echo $this->Form->create('Special', array('type' => 'file', 'action' => 'control_add')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('Special', array('type' => 'file', 'action' => 'control_add_complete')); ?>
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