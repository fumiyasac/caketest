<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>ショーケースの詳細</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているショーケースの詳細を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody padt10">
<div class="forms">
<table cellpadding="0" cellspacing="0" id="formAdmin">
<tr>
<th>ID</th>
<td><?php echo h($data['Showcase']['id']); ?></td>
</tr>
<tr>
<th>ショーケースタイトル</th>
<td><?php echo h($data['Showcase']['title']); ?></td>
</tr>
<tr>
<th>ショーケースキャッチコピー</th>
<td><?php echo h($data['Showcase']['kcpy']); ?></td>
</tr>
<tr>
<th>ショーケースメイン画像</th>
<td>
<div class="padt10">
<?php echo $this->DisplayImage->displayPageThumbnail($this->data['Showcase']['image_main'], 7, 100, 150, true); ?>
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Showcase']['id']); ?> 元画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_main'], 7, false); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Showcase']['id']); ?> リサイズ済み画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_main'], 7, true); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>ショーケース本文</th>
<td><?php echo h($data['Showcase']['description_main']); ?></td>
</tr>
<tr>
<th>サブ1画像</th>
<td>
<div class="padt10">
<?php echo $this->DisplayImage->displayPageThumbnail($this->data['Showcase']['image_sub1'], 7, 100, 150, true); ?>
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Showcase']['id']); ?> 元画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_sub1'], 7, false); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Showcase']['id']); ?> リサイズ済み画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_sub1'], 7, true); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>サブ1キャプション</th>
<td><?php echo h($data['Showcase']['caption_sub1']); ?></td>
</tr>
<tr>
<th>サブ2画像</th>
<td>
<div class="padt10">
<?php echo $this->DisplayImage->displayPageThumbnail($this->data['Showcase']['image_sub2'], 7, 100, 150, true); ?>
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Showcase']['id']); ?> 元画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_sub2'], 7, false); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Showcase']['id']); ?> リサイズ済み画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_sub2'], 7, true); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>サブ2キャプション</th>
<td><?php echo h($data['Showcase']['caption_sub2']); ?></td>
</tr>
<tr>
<th>サブ3画像</th>
<td>
<div class="padt10">
<?php echo $this->DisplayImage->displayPageThumbnail($this->data['Showcase']['image_sub3'], 7, 100, 150, true); ?>
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Showcase']['id']); ?> 元画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_sub3'], 7, false); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Showcase']['id']); ?> リサイズ済み画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_sub3'], 7, true); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>サブ3キャプション</th>
<td><?php echo h($data['Showcase']['caption_sub3']); ?></td>
</tr>
<tr>
<th>サブ4画像</th>
<td>
<div class="padt10">
<?php echo $this->DisplayImage->displayPageThumbnail($this->data['Showcase']['image_sub4'], 7, 100, 150, true); ?>
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Showcase']['id']); ?> 元画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_sub4'], 7, false); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Showcase']['id']); ?> リサイズ済み画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Showcase']['image_sub4'], 7, true); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>サブ4キャプション</th>
<td><?php echo h($data['Showcase']['caption_sub4']); ?></td>
</tr>
<tr>
<th>ぐるなび API ID</th>
<td><?php echo h($data['Showcase']['api_id_gurunabi']); ?></td>
</tr>
<tr>
<th>ホットペッパー API ID</th>
<td><?php echo h($data['Showcase']['api_id_hotpepper']); ?></td>
</tr>
<tr>
<th>楽天 API ID</th>
<td><?php echo h($data['Showcase']['api_id_rakuten']); ?></td>
</tr>
<tr>
<th>じゃらん API ID</th>
<td><?php echo h($data['Showcase']['api_id_rakuten']); ?></td>
</tr>
<tr>
<th>価格</th>
<td><?php echo h($data['Showcase']['price']); ?></td>
</tr>
<tr>
<th>自由記入項目タイトル</th>
<td><?php echo h($data['Showcase']['other_title']); ?></td>
</tr>
<tr>
<th>自由記入項目本文</th>
<td>
<div class="CKEditorContents">
<?php echo $data['Showcase']['other_description']; ?>
</div>
</td>
</tr>
<tr>
<th>作成日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Showcase']['created']));
?>
</td>
</tr>
<tr>
<th>更新日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Showcase']['modified']));
?>
</td>
</tr>
<tr>
<th>公開日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Showcase']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="showcase_flag_<?php echo $data['Showcase']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$data['Showcase']['flag']}")); ?></span>
</td>
</tr>
<tr>
<td colspan="2" class="linkAreaOfView">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $data['Showcase']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $data['Showcase']['id']), array('class' => 'edit'));
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
            $.post('/control/showcases/change/'+ $(this).data('post-id'),{},function(res){
               $('span#showcase_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

});
</script>
</article>
<!-- ## Cake View Content End ## -->