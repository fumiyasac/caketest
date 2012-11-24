<?php echo $form->create("User", array("action" => "add")); ?><br />
ユーザー名：<?php echo $form->text("username"); ?><br />
<?php echo $form->error("username"); ?><br />
パスワード：<?php echo $form->password("password"); ?><br />
<?php echo $form->error("password"); ?><br />
パスワード（確認用）：<?php echo $form->password("password2"); ?><br />
<?php echo $form->error("password2"); ?><br />
<?php
echo $form->submit("登録する");
echo $form->end();
debug($this->data);
?>

