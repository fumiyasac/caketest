<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>特集記事の編集</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは特集記事一覧の内容を編集することが出来ます。</h3>
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
<li>メイン画像は600×400、その他の画像は300×200のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>(横のピクセル)=1.5×(縦のピクセル)の比率であれば綺麗にリサイズされます。</li>
<li><span class="requierd">注意</span>：画像の変更を行わない場合は写真を入力しないで下さい。</li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('Special', array('type' => 'file', 'action' => 'edit_confirm')); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>特集記事タイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('title',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('title'); ?>
</td>
</tr>
<tr>
<th>特集記事キャッチコピー&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('kcpy',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('kcpy'); ?>
</td>
</tr>
<tr>
<th>特集記事メイン画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_main',array('class' => 'formArea')); ?>
<div class="padt10">
<?php if(!empty($error_announce)): ?>
<img src="/img/special/<?php echo h($alreadyAddedImgName['Special']['image_main']); ?>" height="100" width="150">
<?php else: ?>
<img src="/img/special/<?php echo h($this->data['Special']['image_main']); ?>" height="100" width="150">
<?php endif; ?>
<br>
<span>現在アップロードされている画像</span>
</div>
<?php echo $this->Form->error('image_main'); ?>
</td>
</tr>
<tr>
<th>特集記事本文&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Form->textarea('description_main',array(
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<?php echo $this->Form->error('description_main'); ?>
</td>
</tr>

<tr>
<th>見出し(サブ1)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('title_sub1',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('title_sub1'); ?>
</td>
</tr>
<tr>
<th>画像(サブ1)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_sub1',array('class' => 'formArea')); ?>
<div class="padt10">
<?php if(!empty($error_announce)): ?>
<img src="/img/special/<?php echo h($alreadyAddedImgName['Special']['image_sub1']); ?>" height="100" width="150">
<?php else: ?>
<img src="/img/special/<?php echo h($this->data['Special']['image_sub1']); ?>" height="100" width="150">
<?php endif; ?>
<br>
<span>現在アップロードされている画像</span>
</div>
<?php echo $this->Form->error('image_sub1'); ?>
</td>
</tr>
<tr>
<th>本文(サブ1)&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Form->textarea('description_sub1',array(
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<?php echo $this->Form->error('description_sub1'); ?>
</td>
</tr>

<tr>
<th>見出し(サブ2)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('title_sub2',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('title_sub2'); ?>
</td>
</tr>
<tr>
<th>画像(サブ2)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_sub2',array('class' => 'formArea')); ?>
<div class="padt10">
<?php if(!empty($error_announce)): ?>
<img src="/img/special/<?php echo h($alreadyAddedImgName['Special']['image_sub2']); ?>" height="100" width="150">
<?php else: ?>
<img src="/img/special/<?php echo h($this->data['Special']['image_sub2']); ?>" height="100" width="150">
<?php endif; ?>
<br>
<span>現在アップロードされている画像</span>
</div>
<?php echo $this->Form->error('image_sub2'); ?>
</td>
</tr>
<tr>
<th>本文(サブ2)&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Form->textarea('description_sub2',array(
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<?php echo $this->Form->error('description_sub2'); ?>
</td>
</tr>

<tr>
<th>見出し(サブ3)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('title_sub3',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('title_sub3'); ?>
</td>
</tr>
<tr>
<th>画像(サブ3)&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_sub3',array('class' => 'formArea')); ?>
<div class="padt10">
<?php if(!empty($error_announce)): ?>
<img src="/img/special/<?php echo h($alreadyAddedImgName['Special']['image_sub3']); ?>" height="100" width="150">
<?php else: ?>
<img src="/img/special/<?php echo h($this->data['Special']['image_sub3']); ?>" height="100" width="150">
<?php endif; ?>
<br>
<span>現在アップロードされている画像</span>
</div>
<?php echo $this->Form->error('image_sub3'); ?>
</td>
</tr>
<tr>
<th>本文(サブ3)&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Form->textarea('description_sub3',array(
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<?php echo $this->Form->error('description_sub3'); ?>
</td>
</tr>

<tr>
<th>本文(その他)</th>
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
<?php
if($this->Form->value('flag')){
    $value = $this->Form->value('flag');
}else{
    $value = 1;
}
echo $this->Form->input('flag', array('class' => 'radio', 'type' => 'radio', 'options' => Configure::read('FLAG_CONF.flag'), 'legend' => false, 'div' => false, 'label' => false, 'value' => $value));
?>
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