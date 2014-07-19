<!-- ## Cake View Content Start ## -->
<article class="postArticle">
<header class="postTitle">
<h2><img src="/images/common/h2_posts.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="formList">
<header>
<h3><?php echo h($base_data['Post']['title']); ?>のアンケートフォーム</h3>
</header>

<aside class="enquetesMain">
<p class="image_main"><img src="/img/post/resized_<?php echo h($base_data['Post']['post_image']); ?>"></p>
</aside>

<section>
<div class="forms">
<p class="contactText">下記の内容でアンケート内容を送信します。よろしいですか？</p>
<?php echo $this->Form->create('Post'); ?>
<p class="magt20"><span class="remarked">●</span>アンケート内容（<span class="requierd">*必須項目</span>は必ず入力してください）</p>

<table cellspacing="0" cellpadding="0" id="formInquiry">
<?php foreach($post_data as $num => $post): ?>
<tr>
<th>質問事項<?php echo $num; ?></th>
<td id="enqueteArea<?php echo $num; ?>">
<strong>質問<?php echo $num; ?></strong>&nbsp;
<?php if($post['required'] == 1): ?>
<span class="requierd">*必須項目</span>
<?php endif; ?>
<br>
<?php echo h($post['enqueteQuestion']); ?>
<br><br>
<?php echo h($post['enquete']); ?>
</td>
</tr>
<?php endforeach; ?>
</table>

<?php echo $this->Form->end(); ?>
<div class="sendButton">
<p>
<?php echo $this->Form->create('Post', array('type' => 'post', 'action' => 'index')); ?>
<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
<input type="hidden" name="username" value="<?php echo $username; ?>"><!-- usernameは会員登録機能実装時に使用する -->
<?php foreach($post_data as $num => $post): ?>
<input type="hidden" name="enquete_question<?php echo $num; ?>" value="<?php echo h($post['enqueteQuestion']); ?>">
<input type="hidden" name="enquete_type<?php echo $num; ?>" value="<?php echo h($post['enqueteType']); ?>">
<input type="hidden" name="enquete<?php echo $num; ?>" value="<?php echo h($post['enquete']); ?>">
<?php endforeach; ?>
<?php echo $this->Form->submit('入力画面に戻る', array('div' => false, 'id' => 'indexButton')); ?>
<?php echo $this->Form->end(); ?>
&nbsp;
<?php echo $this->Form->create('Post', array('type' => 'post', 'action' => 'complete')); ?>
<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
<input type="hidden" name="username" value="<?php echo $username; ?>"><!-- usernameは会員登録機能実装時に使用する -->
<?php foreach($post_data as $num => $post): ?>
<input type="hidden" name="enquete_question<?php echo $num; ?>" value="<?php echo h($post['enqueteQuestion']); ?>">
<input type="hidden" name="enquete_type<?php echo $num; ?>" value="<?php echo h($post['enqueteType']); ?>">
<input type="hidden" name="enquete_answer<?php echo $num; ?>" value="<?php echo h($post['enquete']); ?>">
<?php endforeach; ?>
<?php echo $this->Form->submit('この内容で送信する', array('div' => false, 'id' => 'completeButton')); ?>
<?php echo $this->Form->end(); ?>
</p>
</div>
<!--/form-->
</div>
</section>
</article>
<!-- # Loop End # -->
</article>