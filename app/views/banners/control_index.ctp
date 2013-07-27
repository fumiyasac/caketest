<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>登録バナーの一覧</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているバナーの一覧を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p><b>■&nbsp;現在のデータ総数</b></p>
<p>現在は全<span id="allAmount"><?php echo $allAmount; ?></span>件登録されています。(<?php echo $limitAmount; ?>件ずつ表示)</p>			
<ul class="magt10 magb10 padl20">
<li><a href="/control/banners/add">新規追加</a></li>
<li><a href="/control/banners/csvdownload">CSVダウンロード</a></li>
</ul>
</div>
</section>
</article>

<!-- # Loop Start #  -->
<?php foreach($banners as $banner): ?>
<article id="banner_<?php echo $banner['Banner']['id']; ?>" class="adminContentContactList">
<header>
<h3>ID：<?php echo h($banner['Banner']['id']); ?></h3>
</header>
<section>
<div class="adminContentsBody">
<table cellpadding="0" cellspacing="0">
<tr>
<th>バナー情報</th>
<td>
<div class="padt10">
<img src="/img/banner/<?php echo h($banner['Banner']['banner_image']); ?>" height="80" width="300">
<br>    
<ul class="magl15">
<li>URL：
<?php if($banner['Banner']['blank_flag'] == 1): ?>
<?php $arr = array('target' => '_blank'); $context = '外部'; ?>
<?php else: ?>
<?php $arr = array(); $context = '内部'; ?>
<?php endif; ?>
<?php echo $this->Html->link($banner['Banner']['link_url'], $banner['Banner']['link_url'], $arr); ?>
<br>(<?php echo $context; ?>リンク)</li>
</ul>
</div>
</td>
</tr>
<tr>
<th>タイトル</th>
<td><?php echo h($banner['Banner']['title']); ?></td>
</tr>
<tr>
<th>バナー本文</th>
<td><?php echo h($banner['Banner']['description']); ?></td>
</tr>
<tr>
<th>公開日</th>
<td>
<?php 
echo h($this->Html->dateFormat($banner['Banner']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="banner_flag_<?php echo $banner['Banner']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$banner['Banner']['flag']}")); ?></span>
</td>
</tr>
</table>
<p class="content">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $banner['Banner']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $banner['Banner']['id']), array('class' => 'edit'));
?>
&nbsp;
<?php
echo $this->Html->link('削除', '#', array('class' => 'delete', 'data-post-id' => $banner['Banner']['id']));
?>
</p>
</div>
</section>
</article>
<?php endforeach; ?>
<!-- # Loop End # -->
<script type="text/javascript">
$(function(){
    
    $("a.change").click(function(e){
        if(confirm('公開ステータスを変更しますか？')){
            $.post('/control/banners/change/'+ $(this).data('post-id'),{},function(res){
               $('span#banner_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

    
    $("a.delete").click(function(e){
        if(confirm('本当に削除しますか？')){
            $.post('/control/banners/delete/'+ $(this).data('post-id'),{},function(res){
               $('article#banner_' + res.id).delay(0).fadeOut("slow");
               $('#allAmount').html(res.allAmount);
            },"json");
        }
        return false;
    });
});
</script>
</article>

<aside class="pagenationArea">
<p>
<?php
echo $paginator->numbers(
    array(
        'before' => $paginator->first('<<').'　',
        'after' => '　'.$paginator->last('>>'),
        'modules' => 4,
        'separator' => '・',
    )
);
?>
</p>
</aside>
<!-- ## Cake View Content End ## -->