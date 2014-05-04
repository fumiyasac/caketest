<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>カタログ（大塚Catalogs）のコメント一覧</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているカタログ（大塚Catalogs）のコメント一覧を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p><b>■&nbsp;現在のデータ総数</b></p>
<p>現在は全<span id="allAmount"><?php echo $allAmount; ?></span>件登録されています。(<?php echo $limitAmount; ?>件ずつ表示)</p>			
<ul class="magt10 magb10 padl20">
<li><a href="/control/catalogs_comments/csvdownload">CSVダウンロード</a></li>
</ul>
</div>
</section>
</article>

<!-- # Loop Start #  -->
<?php foreach($catalogComments as $catalogComment): ?>
<article id="catalog_comment_<?php echo $catalogComment['CatalogsComment']['id']; ?>" class="adminContentContactList">
<header>
<h3>ID：<?php echo h($catalogComment['CatalogsComment']['id']); ?></h3>
</header>
<section>
<div class="adminContentsBody">
<table cellpadding="0" cellspacing="0">
<tr>
<th>カタログタイトル</th>
<td>
<?php
$catalog_id = $catalogComment['CatalogsComment']['catalog_id'];
echo $catalogTitleList[$catalog_id];
?>
</td>
</tr>
<tr>
<th>投稿者</th>
<td><?php echo h($catalogComment['CatalogsComment']['username']); ?></td>
</tr>
<tr>
<th>投稿内容</th>
<td><?php echo h($catalogComment['CatalogsComment']['text']); ?></td>
</tr>
<tr>
<th>投稿日</th>
<td>
<?php 
echo h($this->Html->dateFormat($catalogComment['CatalogsComment']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="catalog_comment_flag_<?php echo $catalogComment['CatalogsComment']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$catalogComment['CatalogsComment']['flag']}")); ?></span>
</td>
</tr>
</table>
<p class="content">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $catalogComment['CatalogsComment']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('削除', '#', array('class' => 'delete', 'data-post-id' => $catalogComment['CatalogsComment']['id']));
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
            $.post('/control/catalogs_comments/change/'+ $(this).data('post-id'),{},function(res){
               $('span#catalog_comment_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

    
    $("a.delete").click(function(e){
        if(confirm('本当に削除しますか？')){
            $.post('/control/catalogs_comments/delete/'+ $(this).data('post-id'),{},function(res){
               $('article#catalog_comment_' + res.id).delay(0).fadeOut("slow");
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