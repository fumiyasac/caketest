<article class="memberArticle">
<header class="memberTitle">
<h2><img src="/images/common/h2_mypage.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>ようこそ「<?php echo $is_login['Member']['username']; ?>」さん</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>こちらは会員様専用のマイページになります。<br>自分のブログ更新やマイクーポンの発行等を行うことができます。会員様だけのおトクな情報も随時公開しておりますので、是非ご活用頂ければと思います。</p>
</div>
</section>

<p class="mypageLabel"><span class="remarked">●</span>最新の会員専用情報一覧</p>
<?php if(!empty($members_topics)): ?>
<!-- 会員専用記事（最大5件） #Start-->
<div class="mypageListArea">
<?php foreach($members_topics as $members_topic): ?>
<section class="mypageList">
<header>
<h4 class="title"><?php echo h($members_topic['MembersTopic']['title']); ?></h4>
</header>
<div class="contentDetail">
<p class="published"><?php echo h($this->Html->dateFormat($members_topic['MembersTopic']['published']." 00:00:00")); ?> 公開</p>
<p class="readMore"><?php echo $this->Html->link('続きを読む', array('controller' => 'members_topics' ,'action' => 'view', $members_topic['MembersTopic']['id'])); ?></p>
</div>
</section>
<?php endforeach; ?>
</div>
<!-- 会員専用記事（最大5件） #End-->
<?php else: ?>
<div class="nonMypageListArea">
<section class="mypageListNone">
<p class="emptyData">※現在公開中の会員専用お知らせ情報はありません。</p>
</section>
</div>
<?php endif; ?>

<!-- クーポン情報エリア #Start// -->

<!-- //クーポン情報エリア #End -->

</article>
<!-- # Loop End # -->
</article>

<aside class="pagenationArea">
<p></p>
</aside>

<?php echo $this->element('catalog_introduce_policy'); ?>