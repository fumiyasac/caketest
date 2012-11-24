<?php
/*
 * app/app_error.php
 * 自作のエラークラスを作成し、様々なコメントを表示できるようにする。
 * created: 12/11/18
 *  */
class AppError extends ErrorHandler{
  
    function eSyori($params){
        $this->controller->layout = "cakephp_kihon";
        $this->controller->set("eStr",$params["eStr"]);
        $this->_outputMessage("e_hp");
    }
    
}
?>
