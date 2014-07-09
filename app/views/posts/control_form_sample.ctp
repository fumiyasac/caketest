<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>アンケートのサンプル</h2>
</header>
<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているアンケートのサンプルを確認することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody padt10">
<ol class="magb10 padl20">
<li>サンプルは登録試験はできません。</li>
<li>アンケートコンテンツは会員になると登録できます。</li>
</ol>
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

<?php foreach($posts_enquetes as $num => $enquetes): ?>
<tr>
<th>質問事項<?php echo $num; ?></th>
<td id="enqueteArea<?php echo $num; ?>">

<?php echo $this->MakeEnquete->makeEnqueteQuestionModule($num, $enquetes['required'], $enquetes['type'], $enquetes['question']); ?>
<br>
<?php echo $this->MakeEnquete->makeEnqueteAnswerModule($num, $enquetes['type'], null, $enquetes['answer']); ?>

</td>
</tr>
<?php endforeach; ?>

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
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $data['Post']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('詳細', array('action' => 'view', $data['Post']['id']), array('class' => 'view'));
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
<script src="/js/utility_post.js"></script>
</article>
<!-- ## Cake View Content End ## -->