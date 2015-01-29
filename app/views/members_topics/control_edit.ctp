<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>会員専用情報の編集</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは会員専用情報一覧の内容を編集することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<?php if(!empty($error_announce)): ?>
<p><span class="requierd"><?php echo $error_announce; ?></span></p>
<?php endif; ?>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトル・キャッチコピー・見出しは256文字まで入力可能です。</li>
<li>本文は1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>画像は600×300のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>(横のピクセル)=2×(縦のピクセル)の比率であれば綺麗にリサイズされます。</li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('MembersTopic', array('type' => 'file', 'action' => 'edit_confirm')); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>会員専用記事タイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('title',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('title'); ?>
</td>
</tr>
<tr>
<th>会員専用記事キャッチコピー&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('kcpy',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('kcpy'); ?>
</td>
</tr>
<tr>
<th>会員専用記事メイン画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('member_topic_image',array('class' => 'formArea')); ?>
<div class="padt10">
<?php if(!empty($error_announce)): ?>
<?php echo $this->DisplayImage->displayControlThumbnail($alreadyAddedImgName['MembersTopic']['member_topic_image'], 8, 75, 150, false); ?>
<?php else: ?>
<?php echo $this->DisplayImage->displayControlThumbnail($this->data['MembersTopic']['member_topic_image'], 8, 75, 150, false); ?>
<?php endif; ?>
<br>
<span>現在アップロードされている画像</span>
</div>
<?php echo $this->Form->error('member_topic_image'); ?>
</td>
</tr>
<tr>
<th>会員専用記事本文&nbsp;<span class="requierd">*</span></th>
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
<th>さらに詳しい情報や補足など</th>
<td>
<?php
echo $this->Form->textarea('other_description',array(
    'id' => 'ckEditor',
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<?php echo $this->Form->error('other_description'); ?>
</td>
</tr>

<tr>
<th>公開日&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Dateform->dateYMD('published', null,
        array(
            'minYear' => 2010,
            'maxYear' => 2020,
            'empty' => false,
            'separator'=>array(" 年 "," 月 "," 日 "),
        )
     );
?>
<?php echo $this->Form->error('published'); ?>
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
<p><?php echo $this->Form->submit('登録内容を確認する', array('div' => false, 'id' => 'confirmButton')); ?></p>
</div>
<?php echo $this->Form->end(); ?>
</div>

</div>
</section>
</article>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
var editor = CKEDITOR.replace('ckEditor');
</script>
</article>
<!-- ## Cake View Content End ## -->