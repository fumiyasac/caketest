<h2>CakePHP簡易名簿</h2>
<form method="post" action="/C07s/index">
氏名<input name="shimei" value=""><br />
住所<textarea cols="50" rows="2" wrap="soft" name="jyusho"></textarea><br />    
電話<input name="denwa">
<input type="submit" value="送信" />
</form>
<hr />
<?php echo $result; ?>