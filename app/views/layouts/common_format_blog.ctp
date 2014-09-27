<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title><?php echo $title_for_layout; ?> | 大塚「珍しいもん」Store　-Official Blog-</title>
<!-- meta and other definition -->
<meta name="description" content="">
<meta name="keywords" content="">
<link rel="start" href="/">
<link rel="index" href="/index.php" title="大塚「珍しいもん」ストア　-Official Blog-">
<meta http-equiv="imagetoolbar" content="no">
<meta name="viewport" content="width=1020px,user-scalable=yes">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- CSS definition -->
<link rel="stylesheet" type="text/css" href="/css/yui/reset-min.css" />
<link rel="stylesheet" type="text/css" href="/css/yui/base-min.css" />
<link rel="stylesheet" type="text/css" href="/css/yui/font-min.css" />
<link rel="stylesheet" type="text/css" href="/css/layout.css" />
<link rel="stylesheet" type="text/css" href="/css/detail.css" />
<link rel="stylesheet" type="text/css" href="/css/vtip.css">
<link rel="stylesheet" type="text/css" href="/css/shadowbox.css">
<link rel="stylesheet" type="text/css" href="/css/jquery.simplyscroll.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="/js/jquery.easing.1.3.js"></script>
<script src="/js/pagescroller.js"></script>
<script src="/js/jquery.opacity.rollover.js"></script>
<script src="/js/vtip.js"></script>
<script src="/js/jquery.simplyscroll.js"></script>
<script src="/js/lazyload.js"></script>
<script src="/js/definition.js"></script>
<script src="/js/jquery.easyslider.js"></script>
<script src="/js/jquery.cookie.js"></script>
<script src="/js/tabview.js"></script>
<?php echo $scripts_for_layout; ?>
</head>
<body>
<div id="wrapper">
<div id="container">

<!-- Header Contents Area// -->
<header id="globalHeader">
<div class="subNav">
<p>大塚で見つける出会いのもの</p>
<ul>
<?php if( empty($is_login) ): ?>
<li><span>・</span><a href="/members/">メンバー募集に関して</a></li>
<li><span>・</span><a href="/members/add">メンバー登録</a></li>
<li><span>・</span><a href="/members/login">ログイン</a></li>
<?php else: ?>
<li><span>・</span><a href="/members/mypage">マイページ</a></li>
<li><span>・</span><a href="#">クーポン情報一覧</a></li>
<li><span>・</span><a href="/members/logout">ログアウト</a></li>
<?php endif; ?>
</ul>
</div>
<div class="mainNav">
<h1><a href="/">大塚「珍しいもん」ストア　-Official Blog-</a></h1>
<nav>
<ul>
<li class="gNavi01"><a href="/specials/">特集記事の一覧</a></li>
<li class="gNavi02"><a href="/abouts/">このブログについて</a></li>
<li class="gNavi03"><a href="/contacts/">お問い合わせ</a></li>
</ul>
</nav>
</div>
</header>
<!-- //Header Contents Area -->
        
<?php if(!$member_page_flag): ?>
<!-- Header Contents Area// -->
<aside class="slideGallery">
<div id="slide">
<ul id="scroller">
<?php $slides = $this->requestAction('slides/index/sort:id/order:asc/limit:10'); ?>
<?php foreach ($slides as $slide): ?>
<?php if($slide['Slide']['blank_flag'] == 1): ?>
<?php $attr = 'target="_blank"'; ?>
<?php else: ?>
<?php $attr = ''; ?>
<?php endif; ?>
<li><a href="<?php echo $slide['Slide']['link_url']; ?>" <?php echo $attr; ?> class="vtip" title="<strong class='vTitle'><span>●</span>&nbsp;<?php echo $slide['Slide']['title']; ?></strong><br><?php echo $slide['Slide']['description']; ?>"><img src="/img/slide/<?php echo $slide['Slide']['slide_image']; ?>" width="360" height="250" alt=""></a></li>
<?php endforeach; ?>
</ul>
</div>
</aside>
<!-- //Header Contents Area -->
<?php endif; ?>

<!-- BreadCramb Contents Area// -->
<aside class="breadCramb">
<div id="bread">
<p>
<?php
foreach($breadcrumb as $val) {
    $option = (isset($val['option'])) ? $val['option'] : array();
    $link = (isset($val['link'])) ? $val['link'] : null;
    $this->Html->addCrumb($val['name'], $link, $option);
}
echo $this->Html->getCrumbs(' > ');
?>
</p>
</div>
</aside>
<!-- //BreadCramb Contents Area -->

<!-- Main Contents Area// -->
<div id="mainContents">
<div id="leftContents">
<?php echo $content_for_layout; ?>    
<!-- ## Cake Element Content Start ## -->
<article class="showcaseArea">
<header>
<h2><img src="/images/common/h2_showcase.png" width="600" height="40" alt=""></h2>
</header>
<section id="shopSlider">
<ul>
<!-- # Loop Start #  -->
<?php $showcases = $this->requestAction('showcases/index/limit:4'); ?>
<?php foreach ($showcases as $showcase): ?>
<li><div>
<img src="/img/showcase/resized_<?php echo h($showcase['Showcase']['image_main']); ?>" width="280" height="160" alt="" class="showcaseThumb">
<p class="showcaseTitle"><span>●</span>&nbsp;<?php echo h($showcase['Showcase']['title']); ?></p>
<p class="showcasePrice"><?php echo h($showcase['Showcase']['price']); ?></p>
<p class="description">
<?php if(mb_strlen($showcase['Showcase']['description_main']) > 50): ?>
<?php echo h(mb_substr($showcase['Showcase']['description_main'], 0, 50)."..."); ?>
<?php else: ?>
<?php echo h($showcase['Showcase']['description_main']); ?>
<?php endif; ?>
</p>
<p class="showcaseButton"><a href="/showcases/view/<?php echo h($showcase['Showcase']['id']); ?>"><img src="/images/common/shoplink_button.png" width="143" height="23" alt=""></a></p>
</div></li>
<?php endforeach; ?>
<!-- # Loop End #  -->
</ul>
</section>
</article>
<!-- ## Cake Element Content End ## -->

</div>
<div id="rightContents">
<?php echo $this->element('sidebar'); ?>
</div>
</div>
<!-- //Main Contents Area -->

<!-- Footer Contents Area// -->
<footer id="globalFooter">
<div id="footerSub1">
<h2><img src="/images/common/footer_logo.png" width="310" height="60" alt=""></h2>
<section>
<p class="whatsThat">大塚で出会える珍しい商品・気になった商品を紹介している、大塚「珍しいもん」Storeの公式ブログです。<br>大塚に関する情報や店主のコラムも掲載中です。</p>
<p class="footerBanner">
<a href="#"><img src="/images/sample/sample_footer_banner.png" width="320" height="74" alt=""></a>
</p>
</section>
</div>
<div id="footerSub2">
<section id="sponsors">
<header>
<h3>お世話になっている方々</h3>
</header>
<div>

</div>
</section>
<section id="perdate">
<header>
<h3>Archives</h3>
</header>
<div>

</div>
</section>
<section id="copyright">
<p class="copyrightText">Copyright &copy; 2013 大塚「珍しいもん」Store All Rights Reserved.</p>
<p class="toTop"><a href="#wrapper">▲&nbsp;Back To Top</a></p>
</section>
</div>
</footer>
<!-- //Footer Contents Area -->
        
</div>
</div>
</body>
</html>