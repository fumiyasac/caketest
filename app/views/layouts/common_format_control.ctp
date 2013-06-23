<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>大塚「珍しいもん」ストア　-マスター管理画面-</title>
<!-- meta and other definition -->
<meta name="robots" content="noindex,nofollow"> 
<meta name="description" content="">
<meta name="keywords" content="">
<meta http-equiv="imagetoolbar" content="no">
<meta name="viewport" content="width=1020px,user-scalable=yes">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- CSS definition -->
<link rel="stylesheet" href="/css/yui/reset-min.css">
<link rel="stylesheet" href="/css/yui/base-min.css">
<link rel="stylesheet" href="/css/yui/font-min.css">
<link rel="stylesheet" href="/css/admin_layout.css">
<!-- jQuery definition -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="/js/jquery.easing.1.3.js"></script>
<script src="/js/utility.js"></script>
<!-- Fancybox definition -->
<link rel="stylesheet" href="/fancybox/jquery.fancybox-1.3.4.css" />
<script type="text/javascript" src="/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
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
<li><span>・</span><a href="#">メンバー募集に関して</a></li>
<li><span>・</span><a href="#">メンバー登録</a></li>
<li><span>・</span><a href="#">ログイン</a></li>
</ul>
</div>
<div class="mainNav">
<h1><a href="/">大塚「珍しいもん」ストア　-マスター管理画面-</a></h1>
<nav>
</nav>
</div>
</header>
<!-- //Header Contents Area -->

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
</div>

<div id="rightContents">
<?php echo $this->element('control_sidebar'); ?>
</div>
</div>
<!-- //Main Contents Area -->

<!-- Footer Contents Area// -->
<footer id="globalFooter">
<div id="footerSub1">
<h2><img src="/images/common/footer_logo.png" width="310" height="60" alt=""></h2>
<section>
<p class="whatsThat">大塚で出会える珍しい商品・気になった商品を紹介している、大塚「珍しいもん」Storeの管理画面です。<br>サイト全体の管理をここで行います。</p>
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