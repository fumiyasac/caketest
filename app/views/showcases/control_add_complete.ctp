<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>ショーケースの追加完了</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではショーケース一覧を追加することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p>ショーケースの追加が完了しました。一覧ページより内容が正しいかを確認して下さい。</p>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトル・キャッチコピー・見出しは256文字まで入力可能です。</li>
<li>本文は1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>画像は600×400のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>サブ画像については必ず1152×768にして下さい。</li>
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
<?php echo $this->Form->create('Showcase', array('type' => 'post', 'url' => 'index')); ?>
<?php echo $this->Form->submit('ショーケースの一覧に戻る', array('div' => false, 'id' => 'indexButton')); ?>
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