<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>カタログの追加確認</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではカタログを追加することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p>この内容でカタログを投稿します。よろしいですか？</p>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトルは256文字まで入力可能です。</li>
<li>本文は1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>メイン画像は600×200のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>(横のピクセル)=3×(縦のピクセル)の比率であれば綺麗にリサイズされます。</li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('Catalog'); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>カタログタイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('title')); ?>
</td>
</tr>
<tr>
<th>カタログキャッチコピー&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo h($this->Form->value('kcpy')); ?>
</td>
</tr>
<tr>
<th>カタログテンプレート&nbsp;<span class="requierd">*</span></th>
<td>
/catalogs/<?php echo $this->Form->value('template'); ?>
</td>
</tr>
<tr>
<th>サムネイル画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if($saveTmpImageResult['catalog_image'] == 1): ?>    
<img src="/img/tmp_catalog/<?php echo h($data['Catalog']['catalog_image']['name']); ?>" height="50" width="150">
<br>
<span>画像アップロード成功！</span>
<?php elseif($saveTmpImageResult['catalog_image'] == 0): ?>
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
<th>公開日</th>
<td>
<?php
echo h($this->Html->dateFormat($data['Catalog']['published']['year'].'-'.$data['Catalog']['published']['month'].'-'.$data['Catalog']['published']['day']." 00:00:00"));
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
<?php echo $this->Form->create('Catalog', array('type' => 'file', 'action' => 'control_add')); ?>
<?php echo $this->Formhidden->hiddenVars(); ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('Catalog', array('type' => 'file', 'action' => 'control_add_complete')); ?>
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