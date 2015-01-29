<article class="catalogCommentsArticle">
<header class="catalogCommentsTitle">
<h2><img src="/images/common/h2_catalogs.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>皆様のコメントをご紹介</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>
こちらは、<?php echo $catalogTitleList[$this->data['CatalogsComment']['catalog_id']]['title']; ?>に関するコメントになりますので、気になったお店に行くときや大塚にお立ち寄りのとき等、今後の参考になれば幸いです。<br>コメントに関すること全般（コメントの投稿方法等や投稿に関する注意事項等）については<a class="blue" href="/catalogs_comments/">こちら</a>をご覧下さい。
</p>
</div>
</section>
<section class="contentList">
<header>
<h4 class="title"><?php echo h($this->data['CatalogsComment']['username']); ?>さんのコメント</h4>
</header>
<div class="contentDetail">
<p class="published"><?php echo h($this->Html->dateFormat($this->data['CatalogsComment']['published']." 00:00:00")); ?> 公開</p>
<p class="description_main padt10">
<?php echo h($this->data['CatalogsComment']['text']); ?>
</p>
<p class="padt20 padb10">
<img src="/img/catalog/resized_<?php echo $catalogTitleList[$this->data['CatalogsComment']['catalog_id']]['catalog_image']; ?>">
</p>
<p class="readMore">
<a href="../../catalogs/<?php echo $catalogTitleList[$this->data['CatalogsComment']['catalog_id']]['template']; ?>/">カタログを見る</a>
</p>
</div>
</section>
</article>
<!-- # Loop End # -->
</article>

<?php echo $this->element('catalog_introduce_policy'); ?>

<?php echo $this->element('catalog_comment_policy'); ?>