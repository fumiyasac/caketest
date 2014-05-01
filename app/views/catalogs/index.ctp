<article class="catalogsArticle">
<header class="catalogsTitle">
<h2><img src="/images/common/h2_catalogs.png" width="600" height="40" alt=""></h2>
</header>
<!-- # Loop Start #  -->
<article class="sentenseList">
<header>
<h3>大塚に関する便利な情報や豆知識をまとめてみました。</h3>
</header>
<section class="introduction">
<div class="sentenses">
<p>大塚にお住まいの方、お仕事などで大塚に足を運ぶことがある方のために、お役立ち情報をまとめてみました。大塚へお越しの際には是非ともご活用頂ければ幸いです。</p>
</div>
</section>
<?php if(!empty($catalogs)): ?>
<?php foreach($catalogs as $catalog): ?>
<section class="contentList">
<header>
<h4 class="title"><?php echo h($catalog['Catalog']['title']); ?></h4>
</header>
<div class="contentDetail">
<p class="published"><?php echo h($this->Html->dateFormat($catalog['Catalog']['published']." 00:00:00")); ?> 公開</p>
<div class="padt10">
<p class="padb10"><img src="/img/catalog/resized_<?php echo h($catalog['Catalog']['catalog_image']); ?>"></p>
</div>
<p class="readMore"><?php echo $this->Html->link('続きを読む', array('action' => 'view', $catalog['Catalog']['id'])); ?></p>
</div>
</section>
<?php endforeach; ?>
<?php else: ?>
<section class="contentListNone">
<p class="emptyData">※現在公開中の大塚Catalogsはありません。</p>
</section>
<?php endif; ?>
</article>
<!-- # Loop End # -->
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