<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>カタログの追加完了</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではカタログを追加することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p>カタログを投稿が完了しました。一覧ページより内容が正しいかを確認して下さい。</p>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトルは256文字まで入力可能です。</li>
<li>本文は1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>メイン画像は600×200のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>(横のピクセル)=3×(縦のピクセル)の比率であれば綺麗にリサイズされます。</li>
</ol>
    
<div class="forms">
<table cellspacing="0" cellpadding="0" id="formAdmin">
<?php foreach ($saveImageResult as $key => $value): ?>
<tr>
<th><?php echo h($key); ?></th>
<td><?php echo h($value); ?></td>
</tr>
<?php endforeach; ?>
</table>

<div class="sendButton">
<p>
<?php echo $this->Form->create('Catalog', array('type' => 'post', 'url' => 'index')); ?>
<?php echo $this->Form->submit('カタログの一覧に戻る', array('div' => false, 'id' => 'indexButton')); ?>
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