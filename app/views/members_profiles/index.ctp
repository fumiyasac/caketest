<article class="memberArticle">
<header class="memberTitle">
<h2><img src="/images/common/h2_myprofile.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>「<?php echo $is_login['Member']['username']; ?>」さんのプロフィール</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>こちらはあなたの現在登録されているプロフィールになります。<br>現在は登録や変更はできませんので、もし変更等を行う場合は管理者へお問い合わせ下さい。<!--変更や更新等がありましたら、<a class="blue" href="/members_profiles/edit">こちら</a>よりプロフィールの変更を行って下さい。--></p>
</div>
</section>

<p class="mypageLabel"><span class="remarked">●</span>あなたのプロフィール</p>
<div class="mypageListArea">
<section class="mypageTable">
<table cellspacing="0" cellpadding="0" id="formInquiry">
<tr>
<th>プロフィール写真</th>
<td>
<?php if (h($this->data['MembersProfile']['filename']) == MembersProfile::DEFAULT_FILENAME): ?>
<img src="/img/members_profile/noimage.gif" width="150" height="150">
<?php else: ?>
<img src="/img/members_profile/resized_<?php echo h($this->data['MembersProfile']['filename']); ?>">
<?php endif; ?>
</td>
</tr>
<tr>
<th>ブログや個人サイトのURL</th>
<td>
<?php echo $this->Html->link($this->data['MembersProfile']['link_url'], $this->data['MembersProfile']['link_url'], array('class' => 'blue', 'target' => '_blank')); ?>
</td>
</tr>
<tr>
<th>自己紹介</th>
<td>
<?php echo $this->data['MembersProfile']['description']; ?>
</td>
</tr>
<tr>
<th>公開ステータス</th>
<td>
<span><?php echo h(Configure::read("FLAG_CONF.flag.{$this->data['MembersProfile']['flag']}")); ?></span>
</td>
</tr>
</table>
<p class="remarkText padb10">
<span class="remark-text">※</span>公開ステータスの有無で記事表示ページにプロフィールの出し分けを行います。<br>
<span class="remark-text">※</span>プロフィールの公開を行わない場合は非表示への設定をお願いします。
</p>
</section>
</div>

</article>
<!-- # Loop End # -->
</article>

<aside class="pagenationArea">
<p></p>
</aside>

<?php echo $this->element('catalog_introduce_policy'); ?>