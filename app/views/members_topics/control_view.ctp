<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>特集記事の詳細</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されている特集記事の詳細を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody padt10">
<div class="forms">
<table cellpadding="0" cellspacing="0" id="formAdmin">
<tr>
<th>ID</th>
<td><?php echo h($data['Special']['id']); ?></td>
</tr>
<tr>
<th>タイトル</th>
<td><?php echo h($data['Special']['title']); ?></td>
</tr>
<tr>
<th>キャッチコピー</th>
<td><?php echo h($data['Special']['kcpy']); ?></td>
</tr>
<tr>
<th>特集記事メイン画像</th>
<td>
<div class="padt10">
<img src="/img/special/resized_<?php echo h($this->data['Special']['image_main']); ?>" height="100" width="150">
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Special']['id']); ?> 元画像" href="/img/special/<?php echo h($this->data['Special']['image_main']); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Special']['id']); ?> リサイズ済み画像" href="/img/special/resized_<?php echo h($this->data['Special']['image_main']); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>特集記事本文</th>
<td><?php echo h($data['Special']['description_main']); ?></td>
</tr>

<tr>
<th>見出し(サブ1)</th>
<td><?php echo h($data['Special']['title_sub1']); ?></td>
</tr>
<tr>
<th>画像(サブ1)</th>
<td>
<div class="padt10">
<img src="/img/special/resized_<?php echo h($this->data['Special']['image_sub1']); ?>" height="100" width="150">
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Special']['id']); ?> 元画像" href="/img/special/<?php echo h($this->data['Special']['image_sub1']); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Special']['id']); ?> リサイズ済み画像" href="/img/special/resized_<?php echo h($this->data['Special']['image_sub1']); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>本文(サブ1)</th>
<td><?php echo h($data['Special']['description_sub2']); ?></td>
</tr>

<tr>
<th>見出し(サブ2)</th>
<td><?php echo h($data['Special']['title_sub2']); ?></td>
</tr>
<tr>
<th>画像(サブ2)</th>
<td>
<div class="padt10">
<img src="/img/special/resized_<?php echo h($this->data['Special']['image_sub2']); ?>" height="100" width="150">
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Special']['id']); ?> 元画像" href="/img/special/<?php echo h($this->data['Special']['image_sub2']); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Special']['id']); ?> リサイズ済み画像" href="/img/special/resized_<?php echo h($this->data['Special']['image_sub2']); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>本文(サブ2)</th>
<td><?php echo h($data['Special']['description_sub2']); ?></td>
</tr>

<tr>
<th>見出し(サブ3)</th>
<td><?php echo h($data['Special']['title_sub3']); ?></td>
</tr>
<tr>
<th>画像(サブ3)</th>
<td>
<div class="padt10">
<img src="/img/special/resized_<?php echo h($this->data['Special']['image_sub3']); ?>" height="100" width="150">
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Special']['id']); ?> 元画像" href="/img/special/<?php echo h($this->data['Special']['image_sub3']); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Special']['id']); ?> リサイズ済み画像" href="/img/special/resized_<?php echo h($this->data['Special']['image_sub3']); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>本文(サブ3)</th>
<td><?php echo $data['Special']['description_sub3']; ?></td>
</tr>

<tr>
<th>本文(その他)</th>
<td>
<?php if($data['Special']['other_description'] !== null): ?>
<div class="CKEditorContents">
<?php echo $data['Special']['other_description']; ?>
</div>
<?php else: ?>
-
<?php endif; ?>
</td>
</tr>

<tr>
<th>作成日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Special']['created']));
?>
</td>
</tr>

<tr>
<th>更新日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Special']['modified']));
?>
</td>
</tr>

<tr>
<th>公開日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Special']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="special_flag_<?php echo $data['Special']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$data['Special']['flag']}")); ?></span>
</td>
</tr>
<tr>
<td colspan="2" class="linkAreaOfView">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $data['Special']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $data['Special']['id']), array('class' => 'edit'));
?>
&nbsp;
<?php
echo $this->Html->link('一覧へ戻る', array('action' => 'index'), array('class' => 'index'));
?>
</td>
</tr>
</table>
</div>
</div>
</section>
</article>
<script type="text/javascript">
$(function(){
    
    $("a.photoView1,a.photoView2").fancybox({
        'transitionIn' : 'fade',
        'transitionOut' : 'fade',
        'speedIn' : 750, 
        'speedOut' : 300, 
        'overlayShow' : true,
        'href' : false,
        'overlayOpacity' : 0.95,
        'overlayColor' : '#ffffff'
    });
    
    $("a.change").click(function(e){
        if(confirm('公開ステータスを変更しますか？')){
            $.post('/control/specials/change/'+ $(this).data('post-id'),{},function(res){
               $('span#special_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

});
</script>
</article>
<!-- ## Cake View Content End ## -->