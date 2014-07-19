<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>アンケート回答結果の一覧</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではアンケート回答結果の一覧を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p><b>■&nbsp;現在のデータ総数</b></p>
<p>現在は全<span id="allAmount"><?php echo $allAmount; ?></span>件登録されています。(<?php echo $limitAmount; ?>件ずつ表示)</p>			
</div>
</section>
</article>

<!-- # Loop Start #  -->
<?php foreach($posts_enquetes as $enquete): ?>
<article id="posts_enquetes_<?php echo $enquete['PostsEnquete']['id']; ?>" class="adminContentContactList">
<header>
<h3>ID：<?php echo h($enquete['PostsEnquete']['id']); ?></h3>
</header>
<section>
<div class="adminContentsBody">
<table cellpadding="0" cellspacing="0">
<tr>
<th>アンケートID</th>
<td><?php echo h($enquete['PostsEnquete']['post_id']); ?></td>
</tr>
<tr>
<th>ユーザー名</th>
<td><?php echo h($enquete['PostsEnquete']['username']); ?></td>
</tr>
<tr>
<th>お問い合わせ内容</th>
<td>
詳細ボタンをクリックするとアンケートの回答を閲覧できます。
<!-- formDetail #start -->
<div id="detail_<?php echo $enquete['PostsEnquete']['id']; ?>" class="modalView">
    <h4>アンケート結果</h4>
    <p class="enqueteLine">
        <strong>質問項目1. <?php echo h($enquete['PostsEnquete']['enquete_question1']); ?></strong>
        <br>
        形式：<?php echo h(Configure::read("POST_PARTS_CONF.type.{$enquete['PostsEnquete']['enquete_type1']}")); ?>
        <br>
        <?php echo h($enquete['PostsEnquete']['enquete_answer1']); ?>
    </p>
    <p class="enqueteLine">
        <strong>質問項目2. <?php echo h($enquete['PostsEnquete']['enquete_question2']); ?></strong>
        <br>
        形式：<?php echo h(Configure::read("POST_PARTS_CONF.type.{$enquete['PostsEnquete']['enquete_type2']}")); ?>
        <br>
        <?php echo h($enquete['PostsEnquete']['enquete_answer2']); ?>
    </p>
    <p class="enqueteLine">
        <strong>質問項目3. <?php echo h($enquete['PostsEnquete']['enquete_question3']); ?></strong>
        <br>
        形式：<?php echo h(Configure::read("POST_PARTS_CONF.type.{$enquete['PostsEnquete']['enquete_type3']}")); ?>
        <br>
        <?php echo h($enquete['PostsEnquete']['enquete_answer3']); ?>
    </p>
    <p class="enqueteLine">
        <strong>質問項目4. <?php echo h($enquete['PostsEnquete']['enquete_question4']); ?></strong>
        <br>
        形式：<?php echo h(Configure::read("POST_PARTS_CONF.type.{$enquete['PostsEnquete']['enquete_type4']}")); ?>
        <br>
        <?php echo h($enquete['PostsEnquete']['enquete_answer4']); ?>
    </p>
    <p class="enqueteLine">
        <strong>質問項目5. <?php echo h($enquete['PostsEnquete']['enquete_question5']); ?></strong>
        <br>
        形式：<?php echo h(Configure::read("POST_PARTS_CONF.type.{$enquete['PostsEnquete']['enquete_type5']}")); ?>
        <br>
        <?php echo h($enquete['PostsEnquete']['enquete_answer5']); ?>
    </p>
</div>
<!-- formDetail #end -->
</td>
</tr>
<tr>
<th>登録日</th>
<td><?php echo date("Y年n月j日 H:i:s" , strtotime($enquete['PostsEnquete']['created'])); ?>
</td>
</tr>
</table>
<p class="content">
<?php
echo $this->Html->link('詳細','#detail_'.$enquete['PostsEnquete']['id'], array('class' => 'view'));
?>
&nbsp;
<?php
echo $this->Html->link('削除','#', array('class' => 'delete', 'data-post-id' => $enquete['PostsEnquete']['id']));
?>
</p>
</div>
</section>
</article>
<?php endforeach; ?>
<!-- # Loop End # -->
<script type="text/javascript">
$(function(){
        
    $("a.view").fancybox({
        'transitionIn' : 'fade',
        'transitionOut' : 'fade',
        'speedIn' : 750, 
        'speedOut' : 300, 
        'overlayShow' : true,
        'href' : false,
        'overlayOpacity' : 0.95,
        'overlayColor' : '#ffffff'
    });

    
    $("a.delete").click(function(e){
        if(confirm('本当に削除しますか？')){
            $.post('/control/posts_enquetes/delete/'+ $(this).data('post-id'),{},function(res){
               $('article#posts_enquetes_' + res.id).delay(0).fadeOut("slow");
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