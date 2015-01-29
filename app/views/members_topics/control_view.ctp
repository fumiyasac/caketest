<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>会員専用情報の詳細</h2>
</header>

<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されている会員専用情報の詳細を見ることが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody padt10">
<div class="forms">
<table cellpadding="0" cellspacing="0" id="formAdmin">
<tr>
<th>ID</th>
<td><?php echo h($data['MembersTopic']['id']); ?></td>
</tr>
<tr>
<th>会員専用記事タイトル</th>
<td><?php echo h($data['MembersTopic']['title']); ?></td>
</tr>
<tr>
<th>会員専用記事キャッチコピー</th>
<td><?php echo h($data['MembersTopic']['kcpy']); ?></td>
</tr>
<tr>
<th>会員専用記事メイン画像</th>
<td>
<div class="padt10">
<?php echo $this->DisplayImage->displayPageThumbnail($this->data['MembersTopic']['member_topic_image'], 8, 75, 150, true); ?>
<br>    
<ul class="magl15">
<li><a class="photoView1" title="ID:<?php echo h($data['MembersTopic']['id']); ?> 元画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['MembersTopic']['member_topic_image'], 8, false); ?>">元画像</a></li>
<li><a class="photoView2" title="ID:<?php echo h($data['MembersTopic']['id']); ?> リサイズ済み画像" href="<?php echo $this->DisplayImage->putThumbnailPath($this->data['MembersTopic']['member_topic_image'], 8, true); ?>">リサイズ済み画像</a></li>
</ul>
</div>
</td>
</tr>
<tr>
<th>会員専用記事本文</th>
<td><?php echo h($data['MembersTopic']['description']); ?></td>
</tr>

<tr>
<th>さらに詳しい情報や補足など</th>
<td>
<?php if($data['MembersTopic']['other_description'] !== null): ?>
<div class="CKEditorContents">
<?php echo $data['MembersTopic']['other_description']; ?>
</div>
<?php else: ?>
-
<?php endif; ?>
</td>
</tr>

<tr>
<th>作成日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['MembersTopic']['created']));
?>
</td>
</tr>

<tr>
<th>更新日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['MembersTopic']['modified']));
?>
</td>
</tr>

<tr>
<th>公開日</th>
<td>
<?php 
echo h($this->Html->dateFormat($data['MembersTopic']['published']." 00:00:00"));
?>
</td>
</tr>
<tr>
<th>公開フラグ</th>
<td><span id="memberstopic_flag_<?php echo $data['MembersTopic']['id']; ?>"><?php echo h(Configure::read("FLAG_CONF.flag.{$data['MembersTopic']['flag']}")); ?></span>
</td>
</tr>
<tr>
<td colspan="2" class="linkAreaOfView">
<?php
echo $this->Html->link('公開フラグの設定', '#', array('class' => 'change', 'data-post-id' => $data['MembersTopic']['id']));
?>
&nbsp;
<?php
echo $this->Html->link('編集', array('action' => 'edit', $data['MembersTopic']['id']), array('class' => 'edit'));
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
            $.post('/control/members_topics/change/'+ $(this).data('post-id'),{},function(res){
               $('span#memberstopic_flag_' + res.id).html(res.flagStatus);
            },"json");
        }
        return false;
    });

});
</script>
</article>
<!-- ## Cake View Content End ## -->