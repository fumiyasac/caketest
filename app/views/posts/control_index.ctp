<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>アンケートの一覧</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているアンケート一覧を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p><b>■&nbsp;現在のデータ総数</b></p>
<p>現在は全<span id="allAmount"><?php echo $allAmount; ?></span>件登録されています。(<?php echo $limitAmount; ?>件ずつ表示)</p>			
<ul class="magt10 magb10 padl20">
<li><a href="/control/posts/add">新規追加</a></li>
<li><a href="/control/posts/csvdownload">CSVダウンロード</a></li>
</ul>
</div>
</section>
</article>

<!-- # Loop Start #  -->
<?php foreach($posts as $post): ?>
<article id="post_<?php echo $post['Post']['id']; ?>" class="adminContentContactList">
<header>
<h3>ID：<?php echo h($post['Post']['id']); ?></h3>
</header>
<section>
<div class="adminContentsBody">
<table cellpadding="0" cellspacing="0">
<tr>
<th>タイトル</th>
<td><?php echo h($post['Post']['title']); ?></td>
</tr>
<tr>
<th>内容</th>
<td><?php echo h($post['Post']['description']); ?></td>
</tr>
<tr>
<th>開始日</th>
<td>
<?php 
echo h($this->Html->dateFormat($post['Post']['start_date']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>終了日</th>
<td>
<?php 
echo h($this->Html->dateFormat($post['Post']['end_date']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="post_flag_<?php echo $post['Post']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$post['Post']['flag']}")); ?></span>
</td>
</tr>
</table>
<p class="content">
<?php
echo $this->Html->link('フォーム項目作成', array('action' => 'form_edit', $post['Post']['id']), array('class' => 'form_edit'));
?>
&nbsp;
<?php
echo $this->Html->link('サンプルを見る', array('action' => 'form_sample', $post['Post']['id']), array('class' => 'form_sample'));
?>
&nbsp;
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $post['Post']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('詳細', array('action' => 'view', $post['Post']['id']), array('class' => 'view'));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $post['Post']['id']), array('class' => 'edit'));
?>
&nbsp;
<?php
echo $this->Html->link('削除', '#', array('class' => 'delete', 'data-post-id' => $post['Post']['id']));
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
            $.post('/control/posts/change/'+ $(this).data('post-id'),{},function(res){
               $('span#post_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });
    
    $("a.delete").click(function(e){
        if(confirm('本当に削除しますか？')){
            $.post('/control/posts/delete/'+ $(this).data('post-id'),{},function(res){
               $('article#post_' + res.id).delay(0).fadeOut("slow");
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