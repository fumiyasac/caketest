<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>登録バナーの追加</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではバナーを追加することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<?php if(!empty($error_announce)): ?>
<p><span class="requierd"><?php echo $error_announce; ?></span></p>
<?php endif; ?>
<ol class="magt10 magb10 padl20">
<li><span class="requierd">*</span>が付いている項目は必須項目になります。</li>
<li>タイトルは256文字まで入力可能です。</li>
<li>本文・リンクURLは1000字まで入力可能です。</li>
<li>画像の容量は合計2MB以内までです。</li>
<li>バナー画像は300×80のサイズで投稿して下さい。<br><span class="requierd">※指定サイズ以外はエラーになります。</span></li>
</ol>
    
<div class="forms">
<?php echo $this->Form->create('Banner', array('type' => 'file', 'action' => 'add_complete')); ?>
<table cellspacing="0" cellpadding="0" id="formAdmin">
<tr>
<th>タイトル&nbsp;<span class="requierd">*</span></th>
<td>
<?php echo $this->Form->text('title',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('title'); ?>
</td>
</tr>
<tr>
<th>バナー画像&nbsp;<span class="requierd">*</span></th>
<td>
<?php if(!empty($this->data['Banner']['banner_image'])): ?>
<div>
<img src="/img/tmp_banner/<?php echo h($this->data['Banner']['banner_image']); ?>">
<br>
<span>現在アップロードされている画像</span>
</div>
<?php endif; ?>
<?php echo $this->Form->file('banner_image',array('class' => 'formArea')); ?>
<?php echo $this->Form->error('banner_image'); ?>
<?php if($sizeValidateFlag === 0 && !$this->Form->error('banner_image')): ?>
<div class="error-message">画像サイズは横:300px, 縦:80pxにして下さい</div>
<?php endif; ?>
</td>
</tr>
<tr>
<th>本文&nbsp;<span class="requierd">*</span></th>
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
<th>リンクURL&nbsp;<span class="requierd">*</span></th>
<td>
<?php
echo $this->Form->textarea('link_url',array(
    'class'=>'formAreaText',
    'rows' => 5,
    'cols' => 40
));
?>
<?php echo $this->Form->error('link_url'); ?>
</td>
</tr>
<tr>
<th>リンクの種類&nbsp;<span class="requierd">*</span></th>
<td>
<?php
if($this->Form->value('blank_flag')){
    $value_link = $this->Form->value('blank_flag');
}else{
    $value_link = 0;
}
echo $this->Form->input('link_flag', array('class' => 'radio', 'type' => 'radio', 'options' => Configure::read('LINK_CONF.flag'), 'legend' => false, 'div' => false, 'label' => false, 'value' => $value_link));
?>
<?php echo $this->Form->error('link_flag'); ?>
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
<div class="sendButton">
<p><?php echo $this->Form->submit('登録する', array('div' => false, 'id' => 'confirmButton')); ?></p>
</div>
<?php echo $this->Form->end(); ?>
</div>
</div>
</section>
</article>

</article>
<!-- ## Cake View Content End ## -->