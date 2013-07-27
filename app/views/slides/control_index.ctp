<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>スライドショー画像の一覧</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているスライドショー画像の一覧を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody">
<p><b>■&nbsp;現在のデータ総数</b></p>
<p>現在は全<span id="allAmount"><?php echo $allAmount; ?></span>件登録されています。(<?php echo $limitAmount; ?>件ずつ表示)</p>			
<ul class="magt10 magb10 padl20">
<li><a href="/control/slides/add">新規追加</a></li>
<li><a href="/control/slides/csvdownload">CSVダウンロード</a></li>
</ul>
</div>
</section>
</article>

<!-- # Loop Start #  -->
<?php foreach($slides as $slide): ?>
<article id="slide_<?php echo $slide['Slide']['id']; ?>" class="adminContentContactList">
<header>
<h3>ID：<?php echo h($slide['Slide']['id']); ?></h3>
</header>
<section>
<div class="adminContentsBody">
<table cellpadding="0" cellspacing="0">
<tr>
<th>スライドショー画像情報</th>
<td>
<div class="padt10">
<img src="/img/slide/<?php echo h($slide['Slide']['slide_image']); ?>" height="260" width="350">
<br>    
<ul class="magl15">
<li>URL：
<?php if($slide['Slide']['blank_flag'] == 0): ?>
<?php $arr = array('target' => '_blank'); $context = '外部'; ?>
<?php else: ?>
<?php $arr = array(); $context = '内部'; ?>
<?php endif; ?>
<?php echo $this->Html->link($slide['Slide']['link_url'], $slide['Slide']['link_url'], $arr); ?>
<br>(<?php echo $context; ?>リンク)</li>
</ul>
</div>
</td>
</tr>
<tr>
<th>タイトル</th>
<td><?php echo h($slide['Slide']['title']); ?></td>
</tr>
<tr>
<th>スライドショー本文</th>
<td><?php echo h($slide['Slide']['description']); ?></td>
</tr>
<tr>
<th>公開日</th>
<td>
<?php 
echo h($this->Html->dateFormat($slide['Slide']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="slide_flag_<?php echo $slide['Slide']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$slide['Slide']['flag']}")); ?></span>
</td>
</tr>
</table>
<p class="content">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $slide['Slide']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $slide['Slide']['id']), array('class' => 'edit'));
?>
&nbsp;
<?php
echo $this->Html->link('削除', '#', array('class' => 'delete', 'data-post-id' => $slide['Slide']['id']));
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
            $.post('/control/slides/change/'+ $(this).data('post-id'),{},function(res){
               $('span#slide_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

    
    $("a.delete").click(function(e){
        if(confirm('本当に削除しますか？')){
            $.post('/control/slides/delete/'+ $(this).data('post-id'),{},function(res){
               $('article#slide_' + res.id).delay(0).fadeOut("slow");
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