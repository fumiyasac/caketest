<strong style="color: #ff0000;">
<?php
if($session->check("Message.flash")){
    echo $session->flash("flash");
}else if($session->check("Message.auth")){
    echo $session->flash("auth");
}else{
    echo 'ログインしてください。';
}
?>
</strong>
<?php echo $form->create("User", array("type" => "post","action" => "login")); ?>
ユーザー：<?php echo $form->text("username", array("value" => $c_username)); ?><br />
ログイン：<?php echo $form->password("password", array("value" => $c_password)); ?><br />
<?php echo $form->checkbox("remember_me"); ?>&nbsp;ユーザーIDの自動入力はしない<br />
<?php echo $form->end(array("label" => "ログイン")); ?>