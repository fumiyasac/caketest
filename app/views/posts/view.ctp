<!-- ## Cake View Content Start ## -->
<article class="postArticle">
<header class="postTitle">
<h2><img src="/images/common/h2_posts.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3><?php echo h($this->data['Post']['title']); ?>のアンケートフォーム</h3>
</header>

<aside class="enquetesMain">
<p class="image_main"><img src="/img/post/resized_<?php echo h($this->data['Post']['post_image']); ?>"></p>
</aside>

<section>
<div class="forms">
<?php if(!empty($error_announce)): ?>
<p><span class="requierd"><?php echo $error_announce; ?></span></p>
<?php endif; ?>

<?php if(!empty($error_msg_array)): ?>
<?php foreach($error_msg_array as $error_msg): ?>
<p><span class="requierd">・<?php echo $error_msg; ?></span></p>
<?php endforeach; ?>
<?php endif; ?>

<p class="contactText">
アンケートへの回答の際は送信内容を確認して頂きますようよろしくお願い致します。
</p>
<?php echo $this->Form->create('Post', array('type' => 'post', 'action' => 'confirm')); ?>
<p class="magt20"><span class="remarked">●</span>アンケート内容（<span class="requierd">*必須項目</span>は必ず入力してください）</p>
<table cellspacing="0" cellpadding="0" id="formInquiry">
<?php foreach($posts_enquetes as $num => $enquetes): ?>
<tr>
<th>質問事項<?php echo $num; ?></th>
<td id="enqueteArea<?php echo $num; ?>">
<?php echo $this->MakeEnquete->makeEnqueteQuestionModule($num, $enquetes['required'], $enquetes['type'], $enquetes['question']); ?>
<br>
<?php echo $this->MakeEnquete->makeEnqueteAnswerModule($num, $enquetes['type'], $post_data, $enquetes['answer']); ?>
</td>
</tr>
<?php endforeach; ?>
</table>
<input type="hidden" name="post_id" value="<?php echo $this->data['Post']['id']; ?>">
<!-- usernameは会員登録機能実装時に使用する -->
<input type="hidden" name="username" value="fumiyasac">
<div class="sendButton">
<p><?php echo $this->Form->submit('アンケート内容を確認する', array('div' => false, 'id' => 'confirmButton')); ?></p>
</div>
<!--/form-->
<?php echo $this->Form->end(); ?>
</div>
</section>
</article>
<!-- # Loop End # -->
</article>