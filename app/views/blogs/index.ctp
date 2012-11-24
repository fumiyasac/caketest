<h2>CakePHP簡易名簿</h2>
<p>ようこそ、<?php echo $a_username; ?>さん&nbsp;<?php echo $html->link("[ログアウト]","../users/logout"); ?>&nbsp;<?php echo $html->link("[ログイン完了ページへ]","../users"); ?></p>
<!--form method="post" action="/C09s/tuika"-->
<?php
echo $form->create("Meibos",array("type" => "post","action"=>"tuika"));
?>
<p>（メイン情報）</p>
氏名<?php echo $form->text("Meibo.shimei"); ?><br />
住所<?php echo $form->textarea("Meibo.jusyo",array("cols" => "50", "rows" => "2")); ?><br />    
電話<?php echo $form->text("Meibo.denwa"); ?><br />
<p>(追加情報)</p>
郵便番号<?php echo $form->text("Tuikameibo.yubinnum"); ?><br />
Fax<?php echo $form->text("Tuikameibo.faxnum"); ?><br />
メールアドレス<?php echo $form->text("Tuikameibo.mailadd",array("size" => "50")); ?>
<?php
echo $form->end(array("label" => "送信","div"=>"false"));
?>
<hr />
<table border="1" width="900">
<?php
echo $html->tableHeaders(
        array("削除","No.","氏名","住所","電話","郵便番号","FAX","メールアドレス"), //テーブルのフィールド
        array(),                         //trの属性指定
        array("align" => "left")         //thの属性指定
     );
?>

<?php
echo $form->create("Meibos",array("type" => "post", "action" => "sakujo"));
?>
    
<?php
for($i=0;$i<count($result);++$i){
    //MeiboモデルのExtract
    $data = $result[$i]["Meibo"]; 
    extract($data);
    //TuikameiboモデルのExtract
    $data2 = $result[$i]["Tuikameibo"];
    extract($data2);
    
print <<< HTML_END
<tr>
    <td>{$form->checkbox("Meibo.{$i}", array("value" => $id))}</td>
    <td>{$id}</td>
    <td>{$shimei}</td>
    <td>{$jusyo}</td>
    <td>{$denwa}</td>
    <td>{$yubinnum}</td>
    <td>{$faxnum}</td>
    <td>{$mailadd}</td>
</tr>   
HTML_END;
    
    //セルであるtr行（データを含むtdタグ）を出力する
    //echo $html->tableCells($data,array(),array());
}
?>
</table>

<?php
echo $form->password("Meibo.delete_pwd");
echo $form->end(array("label" => "管理者用削除","div"=>"false"));
?>
<hr />
<p style="font-size:11px;">
<?php echo $paginator->first("<<"); ?>&nbsp;
<?php echo $paginator->prev("前の{$kensu}件へ"); ?>&nbsp;
<?php echo $paginator->next("次の{$kensu}件へ"); ?>&nbsp;
<?php echo $paginator->last(">>"); ?>
</p>
<h5>デバッグ</h5>
<?php echo print_r($result); ?>