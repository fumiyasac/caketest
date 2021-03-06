<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>ニュース&トピックの詳細</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているニュース&トピックの詳細を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody padt10">
<div class="forms">
<table cellpadding="0" cellspacing="0" id="formAdmin">
<tr>
<th>ID</th>
<td><?php echo h($data['Newstopic']['id']); ?></td>
</tr>
<tr>
<th>タイトル</th>
<td><?php echo h($data['Newstopic']['title']); ?></td>
</tr>
<tr>
<th>画像</th>
<td>
<div class="padt10">
<?php echo $this->DisplayImage->displayPageThumbnail($this->data['Newstopic']['newstopic_image'], 4, 50, 150, true); ?>
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Newstopic']['id']); ?> 元画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Newstopic']['newstopic_image'], 4, false); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Newstopic']['id']); ?> リサイズ済み画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Newstopic']['newstopic_image'], 4, true); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>リンクURL</th>
<td>
URL：
<?php if($data['Newstopic']['blank_flag'] == 1): ?>
<?php $arr = array('target' => '_blank'); $context = '外部'; ?>
<?php else: ?>
<?php $arr = array(); $context = '内部'; ?>
<?php endif; ?>
<?php echo $this->Html->link($data['Newstopic']['link_url'], $data['Newstopic']['link_url'], $arr); ?>
<br>(<?php echo $context; ?>リンク)
</td>
</tr>
<tr>
<th>本文</th>
<td>
<div class="CKEditorContents">
<?php echo $data['Newstopic']['description']; ?>
</div>
</td>
</tr>
<tr>
<th>作成日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Newstopic']['created']));
?>
</td>
</tr>
<tr>
<th>更新日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Newstopic']['modified']));
?>
</td>
</tr>
<tr>
<th>公開日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Newstopic']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="newstopic_flag_<?php echo $data['Newstopic']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$data['Newstopic']['flag']}")); ?></span>
</td>
</tr>
<tr>
<td colspan="2" class="linkAreaOfView">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $data['Newstopic']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $data['Newstopic']['id']), array('class' => 'edit'));
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
            $.post('/control/newstopics/change/'+ $(this).data('post-id'),{},function(res){
               $('span#newstopic_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

});
</script>
</article>
<!-- ## Cake View Content End ## -->