<?php
class User extends AppModel{
    
    public $name = "User";
    public $validate = array(
        "username" => array(
            array("rule" => "notEmpty",
                "message" => "ユーザー名を入力して下さい"
            ),
            array("rule" => "alphaNumeric",
                "message" => "半角英数字で入力して下さい"
            ),
            array("rule" => "isUnique",
                "message" => "このユーザー名は既に使用されています"
            )
        ),
        
        "password" => array(
            array("rule" => "notEmpty",
                "message" => "パスワードを入力して下さい"
            ),
            array("rule" => "alphaNumeric",
                "message" => "半角英数字で入力して下さい"
            ),
            array("rule" => array("between", 6, 255),
                "message" => "パスワードは6文字〜255文字で入力して下さい"
            ),
            array("rule" => "passwordSyogo",
                "message" => "パスワードが一致しません"
            ),
        ),
        
        "password2" => array(
            array("rule" => "notEmpty",
                "message" => "パスワードを入力して下さい"
            ),
            array("rule" => "alphaNumeric",
                "message" => "半角英数字で入力して下さい"
            ),
            array("rule" => array("between", 6, 255),
                "message" => "パスワードは6文字〜255文字で入力して下さい"
            ),
            array("rule" => "passwordSyogo",
                "message" => "パスワードが一致しません"
            ),
        )                    
    );
    
    function passwordSyogo(){
        $p_password = $this->data["User"]["password"];
        $p_password2 = $this->data["User"]["password2"];
        
        if($p_password == $p_password2){
            return true;
        }else{
            return false;
        }
    }


    function hashPasswords($data){
        return $data;
    }
}
?>
