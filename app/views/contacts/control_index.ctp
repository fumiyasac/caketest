<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>お問い合わせの一覧</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここではお問い合わせフォームからのお問い合わせ一覧を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p><b>■&nbsp;現在のデータ総数</b></p>
<p>現在は全<span id="allAmount"><?php echo $allAmount; ?></span>件登録されています。(<?php echo $limitAmount; ?>件ずつ表示)</p>			
</div>
</section>
</article>

<!-- # Loop Start #  -->
<?php foreach($contacts as $contact): ?>
<article id="contact_<?php echo $contact['Contact']['id']; ?>" class="adminContentContactList">
<header>
<h3>ID：<?php echo h($contact['Contact']['id']); ?></h3>
</header>
<section>
<div class="adminContentsBody">
<table cellpadding="0" cellspacing="0">
<tr>
<th>お名前</th>
<td><?php echo h($contact['Contact']['name']); ?></td>
</tr>
<tr>
<th>メールアドレス</th>
<td><?php echo h($contact['Contact']['mail']); ?></td>
</tr>
<tr>
<th>お問い合わせ内容</th>
<td>
<?php echo h(Configure::read("CONTACT_CONF.title.{$contact['Contact']['purpose']}")); ?>
<?php if($contact['Contact']['purpose_etc']): ?>
<br>■備考：<?php echo h($contact['Contact']['purpose_etc']); ?><br>
<?php endif; ?>
<!-- formDetail #start -->
<div id="detail_<?php echo $contact['Contact']['id']; ?>" class="modalView">
    <h4>ID</h4>
    <p><?php echo h($contact['Contact']['id']); ?></p>
    <h4>お問い合わせ内容（本文）</h4>
    <p><?php echo h($contact['Contact']['text']); ?></p>
    <h4>アンケート結果</h4>
    <p class="enqueteLine">
        <strong>（Q1）あなたのご年齢を選択して下さい</strong>
        <br>
        <?php echo h(Configure::read("ENQUETE_CONF.enquete1.{$contact['Contact']['enquete1']}")); ?>
    </p>
    <p class="enqueteLine">
        <strong>（Q2）あなたの職業の業種を選択して下さい</strong>
        <br>
        <?php echo h(Configure::read("ENQUETE_CONF.enquete2.{$contact['Contact']['enquete2']}")); ?>
    </p>
    <p class="enqueteLine">
        <strong>（Q3）現在のストアでご興味のある商品はありますか？</strong>
        <br>
        <?php echo h($contact['Contact']['enquete3']); ?>
    </p>
    <p class="enqueteLine">
        <strong>（Q4）あなたがよく利用しているオンラインショップは何ですか？</strong>
        <br>
        <?php echo h($contact['Contact']['enquete4']); ?>
    </p>
    <p class="enqueteLine">
        <strong>（Q5）Q4のオンラインショップを利用する理由があればお答え下さい。</strong>
        <br>
        <?php echo h($contact['Contact']['enquete5']); ?>
    </p>
</div>
<!-- formDetail #end -->
</td>
</tr>
<tr>
<th>登録日</th>
<td><?php echo date("Y年n月j日 H:i:s" , strtotime($contact['Contact']['created'])); ?>
</td>
</tr>
</table>
<p class="content">
<?php
echo $this->Html->link('詳細','#detail_'.$contact['Contact']['id'], array('class' => 'view'));
?>
&nbsp;
<?php
echo $this->Html->link('削除','#', array('class' => 'delete', 'data-post-id' => $contact['Contact']['id']));
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
            $.post('/control/contacts/delete/'+ $(this).data('post-id'),{},function(res){
               $('article#contact_' + res.id).delay(0).fadeOut("slow");
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
</aside>
<!-- ## Cake View Content End ## -->