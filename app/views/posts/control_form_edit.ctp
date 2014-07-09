<!-- ## Cake View Content Start ## -->
<article class="adminBlock">
<header class="adminTitle">
<h2>アンケートのフォーム項目作成</h2>
</header>
<article class="adminContentList adminContact">
<header>
<h3>ここでは現在登録されているアンケートのフォーム項目を作成することが出来ます。</h3>
</header>
<section>
<div class="adminContentsBody padt10">
<ol class="magb10 padl20">
<li>アンケート項目は全部で5つあります。</li>
<li>必須項目も選択できます。（デフォルトは必須項目になっています）</li>
<li>回答形式は以下の形式が選択できます。
<ul class="padl15 magb10 magt10">
<li>テキストボックス</li>
<li>テキストエリア</li>
<li>プルダウンメニュー</li>
<li>チェックボックス</li>
</ul>
</li>
<li>「プルダウンメニュー・チェックボックス」を選択した場合は回答リストを選択できます。</li>
<li><span class="requierd">注意</span>：項目4での回答リストはカンマ区切りで入力して下さい。<br>（例）りんご,みかん,ぶどう</li>
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

<?php echo $this->Form->create('Post', array('type' => 'post', 'action' => 'edit_form_change')); ?>

<div class="magb10">
<strong style="font-size:93%;">アンケート形式：</strong><br>
<select name="postQuestionType" class="postQuestionType" onchange="onChangeSelectFormType('<?php echo $num; ?>');">
<?php $enquete_list = Configure::read('POST_PARTS_CONF.type'); ?>
<?php foreach ($enquete_list as $enquete_key => $enquete_value): ?>
<option value="<?php echo $enquete_key; ?>" <?php if($enquete_key == $enquetes['type']): ?>selected<?php endif; ?>><?php echo $enquete_value; ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="magb10">
<strong style="font-size:93%;">質問内容：</strong><br>
<input name="postQuestionText" type="text" class="formArea postQuestionText" value="<?php echo $enquetes['question']; ?>" />
<div class="errorPostQuestionText"></div>
</div>

<div class="magb10">
<strong style="font-size:93%;">必須項目：</strong><br>
<?php
$checked = array();
if($enquetes['required'] == 1){
	$checked[1] = 'checked';
	$checked[2] = '';
}elseif($enquetes['required'] == 2){
	$checked[1] = '';
	$checked[2] = 'checked';
}
?>
<?php $enquete_required_list = Configure::read('POST_REQIRE_CONF.flag'); ?>
<?php foreach ($enquete_required_list as $enquete_required_list_key => $enquete_required_list_value): ?>
<label>
<input class="radio postQuestionRequired" type="radio" value="<?php echo $enquete_required_list_key; ?>" name="postQuestionRequired" <?php echo $checked[$enquete_required_list_key]; ?>>
<?php echo $enquete_required_list_value; ?> 
<?php endforeach; ?>
</label>
</div>

<div class="magb10">
<strong style="font-size:93%;">回答リスト(ラジオボタン・チェックボックス選択時)：</strong><br>
<input name="postAnswerText" type="text" class="formArea postAnswerText" value="<?php echo $enquetes['answer']; ?>" />
<div class="errorPostAnswerText"></div>
</div>

<input name="num" type="hidden" class="formArea num" value="<?php echo $num; ?>" />
<input name="postId" type="hidden" class="formArea postId" value="<?php echo $enquetes['post_id']; ?>" />
<input name="postQuestionId" type="hidden" class="formArea postQuestionId" value="<?php echo $enquetes['post_question_id']; ?>" />
<input name="postAnswerId" type="hidden" class="formArea postAnswerId" value="<?php echo $enquetes['post_answer_id']; ?>" />

<div class="sendButton">
<p><input id="postEditCompleteButton" type="button" value="質問項目<?php echo $num; ?>を編集する" onclick="beforeEditForm(<?php echo $num; ?>);"></p>
</div>

<?php echo $this->Form->end(); ?>

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
echo $this->Html->link('リロードする', array('action' => 'form_edit', $data['Post']['id']), array('class' => 'form_edit'));
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