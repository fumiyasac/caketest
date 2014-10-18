<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>カタログの詳細</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているカタログの詳細を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody padt10">
<div class="forms">
<table cellpadding="0" cellspacing="0" id="formAdmin">
<tr>
<th>ID</th>
<td><?php echo h($data['Catalog']['id']); ?></td>
</tr>
<tr>
<th>カタログタイトル</th>
<td><?php echo h($data['Catalog']['title']); ?></td>
</tr>
<tr>
<th>カタログキャッチコピー</th>
<td><?php echo h($data['Catalog']['kcpy']); ?></td>
</tr>
<tr>
<th>カタログテンプレート</th>
<td>
ディレクトリ：/catalogs/<?php echo h($data['Catalog']['template']); ?><br>
URL：<a href="/catalogs/<?php echo h($data['Catalog']['template']); ?>/" target="_blank">コンテンツを見る</a>
</td>
</tr>
<tr>
<th>サムネイル画像</th>
<td>
<div class="padt10">
<?php echo $this->DisplayImage->displayPageThumbnail($this->data['Catalog']['catalog_image'], 5, 50, 150, true); ?>
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Catalog']['id']); ?> 元画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Catalog']['catalog_image'], 5, false); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Catalog']['id']); ?> リサイズ済み画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['Catalog']['catalog_image'], 5, true); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>本文</th>
<td>
<div class="CKEditorContents">
<?php echo $data['Catalog']['description']; ?>
</div>
</td>
</tr>
<tr>
<th>作成日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Catalog']['created']));
?>
</td>
</tr>
<tr>
<th>更新日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Catalog']['modified']));
?>
</td>
</tr>
<tr>
<th>公開日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Catalog']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="catalog_flag_<?php echo $data['Catalog']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$data['Catalog']['flag']}")); ?></span>
</td>
</tr>
<tr>
<td colspan="2" class="linkAreaOfView">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $data['Catalog']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $data['Catalog']['id']), array('class' => 'edit'));
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
            $.post('/control/catalogs/change/'+ $(this).data('post-id'),{},function(res){
               $('span#catalog_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

});
</script>
</article>
<!-- ## Cake View Content End ## -->