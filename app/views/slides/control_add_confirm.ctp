<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>スライドショー画像の追加確認</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではスライドショー画像を追加することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p>この内容でスライドショー画像を投稿します。よろしいですか？</p>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトルは256文字まで入力可能です。</li>
<li>本文・リンクURLは1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>スライドショー画像は360×250のサイズで投稿して下さい。<br><span class="requierd">※指定サイズ以外はエラーになります。</span></li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('Banner'); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>タイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('title')); ?>
</td>
</tr>
<tr>
<th>スライドショー画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['slide_image']['result_code'] == UPLOAD_SUCCESS): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($saveTmpImageResult['slide_image']['tmp_file_name'], 2, 250, 360, true); ?>
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['slide_image']['result_code'] == UPLOAD_FAILUER): ?>
<span class="requierd">※画像アップロードに失敗しました</span>
<?php endif; ?>
</td>
</tr>
<tr>
<th>本文&nbsp;<span class="requierd">*</span></th>
<td>
<div class="CKEditorContents">
<?php echo $this->Form->value('description'); ?>
</div>
</td>
</tr>
<tr>
<th>リンクURL&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->value('link_url'); ?>
</td>
</tr>
<tr>
<th>リンクの種類&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h(Configure::read("LINK_CONF.flag.{$this->Form->value('blank_flag')}")); ?>
</td>
</tr>
<tr>
<th>公開日</th>
<td>
<?php
echo h($this->Html->dateFormat($data['Slide']['published']['year'].'-'.$data['Slide']['published']['month'].'-'.$data['Slide']['published']['day']." 00:00:00"));
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
<?php echo $this->Form->create('Slide', array('type' => 'file', 'action' => 'control_add')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('Slide', array('type' => 'file', 'action' => 'control_add_complete')); ?>
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