<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>ショーケースの一覧</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているショーケース(主宰者のFavorite)一覧を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p><b>■&nbsp;現在のデータ総数</b></p>
<p>現在は全<span id="allAmount"><?php echo $allAmount; ?></span>件登録されています。(<?php echo $limitAmount; ?>件ずつ表示)</p>			
<ul class="magt10 magb10 padl20">
<li><a href="/control/showcases/add">新規追加</a></li>
<li><a href="/control/showcases/csvdownload">CSVダウンロード</a></li>
</ul>
</div>
</section>
</article>

<!-- # Loop Start #  -->
<?php foreach($showcases as $showcase): ?>
<article id="showcase_<?php echo $showcase['Showcase']['id']; ?>" class="adminContentContactList">
<header>
<h3>ID：<?php echo h($showcase['Showcase']['id']); ?></h3>
</header>
<section>
<div class="adminContentsBody">
<table cellpadding="0" cellspacing="0">
<tr>
<th>タイトル </th>
<td><?php echo h($showcase['Showcase']['title']); ?></td>
</tr>
<tr>
<th>キャッチコピー</th>
<td><?php echo h($showcase['Showcase']['kcpy']); ?></td>
</tr>
<tr>
<th>公開日</th>
<td>
<?php 
echo h($this->Html->dateFormat($showcase['Showcase']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="showcase_flag_<?php echo $showcase['Showcase']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$showcase['Showcase']['flag']}")); ?></span>
</td>
</tr>
</table>
<p class="content">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $showcase['Showcase']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('詳細', array('action' => 'view', $showcase['Showcase']['id']), array('class' => 'view'));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $showcase['Showcase']['id']), array('class' => 'edit'));
?>
&nbsp;
<?php
echo $this->Html->link('削除', '#', array('class' => 'delete', 'data-post-id' => $showcase['Showcase']['id']));
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
            $.post('/control/showcases/change/'+ $(this).data('post-id'),{},function(res){
               $('span#showcase_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

    
    $("a.delete").click(function(e){
        if(confirm('本当に削除しますか？')){
            $.post('/control/showcases/delete/'+ $(this).data('post-id'),{},function(res){
               $('article#showcase_' + res.id).delay(0).fadeOut("slow");
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