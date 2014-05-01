<article class="catalogsArticle">
<header class="catalogsTitle">
<h2><img src="/images/common/h2_catalogs.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseView">
<header>
<h3><?php echo h($this->data['Catalog']['title']); ?></h3>
</header>
<section class="introduction">
<div class="catalogMain">
<p class="image_main"><img src="/img/catalog/resized_<?php echo h($this->data['Catalog']['catalog_image']); ?>"></p>
</div>
</section>
<section class="detailOfCatalogMain">
<div>
<?php echo $this->data['Catalog']['description']; ?>
<p style="text-align: center;" class="padt20"><a href="../<?php echo $this->data['Catalog']['template']; ?>/"><img src="/images/common/cataloglink_button.png"></a></p>
</div>
</section>
<aside class="afterLinks">
<p class="backLink"><?php echo $this->Html->link('一覧へ戻る', array('action' => 'index')); ?></p>
<p class="publishedDate"><?php echo h($this->Html->dateFormat($this->data['Catalog']['published']." 00:00:00")); ?> 公開</p>
</aside>
</article>
<!-- # Loop End # -->
</article>