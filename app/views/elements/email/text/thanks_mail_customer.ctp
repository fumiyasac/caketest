<?php echo $data['Entry']['family_name']; ?> <?php echo $data['Entry']['first_name']; ?>様
この度は、弊社お問い合わせフォームよりのお問い合わせ誠にありがとうございます。
送信内容は以下になります。入力内容に誤りがないか、今一度ご確認くださいますようよろしくお願い申し上げます。

---------------------------------------
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

---------------------------------------

=======================================
シナジーボックス株式会社
Synergy Box Inc.

本社
〒169-0072 東京都新宿区大久保2-4-12　新宿ラムダックスビル8F
TEL：03-6380-3581（代表）
FAX：03-5155-2772

事業所
〒169-0072 東京都新宿区大久保2-4-15　サンライズ新宿7F
TEL：03-6380-2771（代表）
=======================================