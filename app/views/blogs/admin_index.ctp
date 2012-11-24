<h2>大塚「珍しいもん」ストア Official　blog [管理画面]</h2>

<p>以下のことがあれば速やかにお知らせください。<br />お知らせ先：fumiya.def.mathmatica@gmail.com</p>
<ul>
    <li>間違って登録してしまった。</li>
    <li>機能でおかしいところがある。</li>
    <li>使い方がよくわからない。</li>
    <li>管理画面へのご意見やご要望。</li>
</ul>

<!--管理者用　入力画面スタート(後にadmin_addへ移植する予定)//-->
<?php echo $form->create("Blogs",array("type" => "post","action"=>"admin_add")); ?>
<table border="0" cellspacing="5" cellpadding="8" width="580" class="adminForm">
    <tr>
        <th>投稿者</th>
        <td>
            <?php echo $form->text('Blog.name', array('size' => '50')); ?>
            <br /><span class="remark">※必須項目(ハンドルネームやニックネームも可能)</span>
        </td>
    </tr>
    <tr>
        <th>E-mail</th>
        <td>
            <?php echo $form->text('Blog.email', array('size' => '50')); ?>
            <br /><span class="remark">※任意項目</span>
        </td>
    </tr>
    <tr>
        <th>タイトル</th>
        <td>
            <?php echo $form->text('Blog.subject', array('size' => '50')); ?>
            <br /><span class="remark">※必須項目</span>
        </td>
    </tr>
    <tr>
        <th>内容</th>
        <td>
            <?php
                echo $form->textarea('Blog.message', array(
                        'cols' => '45', 
                        'rows' => '5', 
                        'label' => ''
                        )
                     );
            ?>
            <br /><span class="remark">※必須項目</span>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php echo $form->submit('記事を投稿する'); ?>
        </td>
    </tr>
</table>
<?php echo $form->end(); ?>
<!--//管理者用　入力画面エンド(後にadmin_addへ移植する予定)-->
<hr class="adminBound" />
<!--管理者用　表示画面スタート//-->
<h4>■投稿記事一覧</h4>
<p>現在のブログに投稿されている記事の一覧（<?php echo $kensu; ?>件表示）</p>
<?php echo $form->create('Blogs', array('type' => 'post', 'action' => 'admin_delete')); ?>
<?php
for($i=0; $i<count($result); ++$i){
    $data = $result[$i]['Blog'];
    extract($data);

    //時間（createdより時間を抽出して整形する）
    $postTime = $html->dateFormatJpn($created);
    
print <<< HTML
<table width="580" border="0">
    <tr>
        <td>
            {$form->checkbox('Blog.{$i}', array('value' => $id))}
            <strong>{$subject}</strong>
        </td>
    </tr>
    <tr>
        <td>
            投稿者：<strong>

HTML;
    if($email){
        print($html->link($name, 'mailto:'.$email));
    }else{
        print($name);
    }          
print <<< HTML
            </strong>
            <br />
            <br />
            {$message}
            <br />
            <br />
            投稿日：{$postTime}
        </td>
    </tr>
</table><br />
HTML;
}
?>
<!--//管理者用　表示画面エンド-->
<hr class="adminBound" />
<!--管理者用　削除欄スタート//-->
<?php
echo $form->password('Blog.delete_pwd');
echo $form->submit('管理者用削除', array("div"=>"false"));
?>
<!--//管理者用　削除欄エンド-->

<!--管理者用　ページャー機能スタート//-->
<table width="580" border="0">
    <tr>
        <td align="right">
            <?php echo $paginator->first("[最初のページ]"); ?>&nbsp;
            <?php echo $paginator->prev("[前の{$kensu}件]"); ?>&nbsp;
            <?php echo $paginator->next("[次の{$kensu}件]"); ?>&nbsp;
            <?php echo $paginator->last("[最後のページ]"); ?> 
        </td>
    </tr>
</table>
<!--//管理者用　ページャー機能エンド-->
<?php echo $form->end(); ?>

<hr class="adminBound" />
<h6>デバッグ</h6>
<p style="font-size: 11px;"><?php echo print_r($result); ?><p>