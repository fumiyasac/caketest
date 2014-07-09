<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>アンケートの詳細</h2>
</header>
<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているアンケートの詳細を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody padt10">
<div class="forms">
<table cellpadding="0" cellspacing="0" id="formAdmin">
<tr>
<th>ID</th>
<td><?php echo h($data['Post']['id']); ?></td>
</tr>
<tr>
<th>タイトル</th>
<td><?php echo h($data['Post']['title']); ?></td>
</tr>
<tr>
<th>内容</th>
<td>
<div class="CKEditorContents">
<?php echo $data['Post']['description']; ?>
</div>
</td>
</tr>
<tr>
<th>画像</th>
<td>
<div class="padt10">
<img src="/img/post/resized_<?php echo h($data['Post']['post_image']); ?>" height="50" width="150">
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['Post']['id']); ?> 元画像" href="/img/post/<?php echo h($data['Post']['post_image']); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['Post']['id']); ?> リサイズ済み画像" href="/img/post/resized_<?php echo h($data['Post']['post_image']); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>開始日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Post']['start_date']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>終了日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Post']['end_date']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>作成日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Post']['created']));
?>
</td>
</tr>
<tr>
<th>更新日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['Post']['modified']));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="post_flag_<?php echo $data['Post']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$data['Post']['flag']}")); ?></span>
</td>
</tr>
<tr>
<td colspan="2" class="linkAreaOfView">
<?php
echo $this->Html->link('フォーム項目作成', array('action' => 'form_edit', $data['Post']['id']), array('class' => 'form_edit'));
?>
&nbsp;
<?php
echo $this->Html->link('サンプルを見る', array('action' => 'form_sample', $data['Post']['id']), array('class' => 'form_sample'));
?>
&nbsp;
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $data['Post']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $data['Post']['id']), array('class' => 'edit'));
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
            $.post('/control/posts/change/'+ $(this).data('post-id'),{},function(res){
               $('span#post_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

});
</script>
</article>
<!-- ## Cake View Content End ## -->