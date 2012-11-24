<h1>Blog posts</h1>
<p>
<?php echo $html->link("記事を追加する", "/posts/add"); ?>
</p>
<p>
<?php echo $html->link("helloページへ", "/hello"); ?>
</p>
<br />
<table>
<tr>
<th>Id</th>
<th>Title</th>
<th>Created</th>
</tr>
<!-- $post配列をループして、投稿記事の情報を表示 -->
<?php foreach ($posts as $post): ?>
<tr>
<td><?php echo $post['Post']['id']; ?></td>
<td>
<?php echo $html->link($post['Post']['title'],'/posts/view/'.$post['Post']['id']);?>
&nbsp;<?php echo $html->link(
'削除する',
"/posts/delete/{$post['Post']['id']}",
null,
'この記事を削除しますか?'
)?>
&nbsp;
<?php echo $html->link('編集する', '/posts/edit/'.$post['Post']['id']);?>
</td>
<td><?php echo $post['Post']['created']; ?></td>
</tr>
<?php endforeach; ?>
 
</table>