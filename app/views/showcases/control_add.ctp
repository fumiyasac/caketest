<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>ショーケースの追加</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではショーケース一覧を追加することが出来ます。</h3>
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
<li>画像は600×400のサイズで投稿して下さい。<br>指定サイズより大きい場合は自動的にリサイズされます。<br>サブ画像については必ず1152×768にして下さい。</li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('Showcase', array('type' => 'file', 'action' => 'add_confirm')); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>ショーケースタイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('title',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('title'); ?>
</td>
</tr>
<tr>
<th>ショーケースキャッチコピー&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('kcpy',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('kcpy'); ?>
</td>
</tr>
<tr>
<th>ショーケースメイン画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_main',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('image_main'); ?>
</td>
</tr>
<tr>
<th>ショーケース本文&nbsp;<span class="requierd">*</span></th>
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
<th>サブ1画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_sub1',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('image_sub1'); ?>
</td>
</tr>
<tr>
<th>サブ1キャプション&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('caption_sub1',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('caption_sub1'); ?>
</td>
</tr>
<tr>
<th>サブ2画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_sub2',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('image_sub2'); ?>
</td>
</tr>
<tr>
<th>サブ2キャプション&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('caption_sub2',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('caption_sub2'); ?>
</td>
</tr>
<tr>
<th>サブ3画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_sub3',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('image_sub3'); ?>
</td>
</tr>
<tr>
<th>サブ3キャプション&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('caption_sub3',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('caption_sub3'); ?>
</td>
</tr>
<tr>
<th>サブ4画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->file('image_sub4',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('image_sub4'); ?>
</td>
</tr>
<tr>
<th>サブ4キャプション&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('caption_sub4',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('caption_sub4'); ?>
</td>
</tr>
<tr>
<th>ぐるなび API ID</th>
<td>
<?php echo $this->Form->text('api_id_gurunabi',array('class' => 'formArea')); ?>
</td>
</tr>
<tr>
<th>ホットペッパー API ID</th>
<td>
<?php echo $this->Form->text('api_id_hotpepper',array('class' => 'formArea')); ?>
</td>
</tr>
<tr>
<th>楽天 API ID</th>
<td>
<?php echo $this->Form->text('api_id_rakuten',array('class' => 'formArea')); ?>
</td>
</tr>
<tr>
<th>じゃらん API ID</th>
<td>
<?php echo $this->Form->text('api_id_jaran',array('class' => 'formArea')); ?>
</td>
</tr>
<tr>
<th>価格</th>
<td>
<?php echo $this->Form->text('price',array('class' => 'formArea')); ?>
</td>
</tr>
<tr>
<th>自由記入項目タイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('other_title',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('other_title'); ?>
</td>
</tr>
<tr>
<th>自由記入項目本文&nbsp;<span class="requierd">*</span></th>
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
    $value = 2;
}
echo $this->Form->input('flag', array('class' => 'radio', 'type' => 'radio', 'options' => Configure::read('FLAG_CONF.flag'), 'legend' => false, 'div' => false, 'label' => false, 'value' => $value));
?>
<?php echo $this->Form->error('flag'); ?>
</td>
</tr>
</table>
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