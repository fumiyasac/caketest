以下の内容で、お問い合わせがありました。

お名前：<?php echo $data['Entry']['family_name']; ?> <?php echo $data['Entry']['first_name']; ?><?php echo "\n"; ?>
フリガナ：<?php echo $data['Entry']['family_kana']; ?> <?php echo $data['Entry']['first_kana']; ?><?php echo "\n"; ?>
電話番号：<?php echo $data['Entry']['tel1']; ?>-<?php echo $data['Entry']['tel2']; ?>-<?php echo $data['Entry']['tel3']; ?><?php echo "\n"; ?>
FAX番号：<?php echo $data['Entry']['fax1']; ?>-<?php echo $data['Entry']['fax2']; ?>-<?php echo $data['Entry']['fax3']; ?><?php echo "\n"; ?>
メールアドレス：<?php echo $data['Entry']['mail_address']; ?><?php echo "\n"; ?>
会社名：<?php echo $data['Entry']['company_name']; ?><?php echo "\n"; ?>
部署・役職：<?php echo $data['Entry']['official_position']; ?><?php echo "\n"; ?>

携わっている業種：<?php echo Configure::read("CATEGORYCONF.category.{$data['Entry']['business_category']}"); ?><?php echo "\n"; ?>
来訪の可否：<?php echo Configure::read("VISITCONF.visit.{$data['Entry']['have_visit']}"); ?><?php echo "\n"; ?>
関心のある商品について：
<?php
foreach (Configure::read("INTERESTCONF.product") as $k => $v) {
    if($data['Entry']["product_interest{$k}"] == 1){
        echo $v." ";
    }
}
?>
<?php echo "\n"; ?>

内容：<?php echo $data['Entry']['content']; ?><?php echo "\n"; ?>