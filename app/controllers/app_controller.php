<?php
class AppController extends Controller {
    
    public $components = array(
        'DebugKit.Toolbar'
    );
    
    //管理画面時のレイアウトと分割を行う
    public function beforeRender() {
        
        //管理画面
        if(!empty($this->params['control'])){

            //CSV出力の際はレイアウトを使用しない
            if($this->params['action'] === 'control_csvdownload'){
                $this->layout = false;
            }else{
                $this->layout = 'common_format_control';
            }
        }
        
    }  
    
    
    //メール送信の共通関数
    public function _sendMail($options = array()){
        $rtn = false;
        if($options){
            
            extract($options);
            $this->Email->reset();
            $this->Email->language = 'Japanese';
            $this->Email->charset = 'UTF-8';
            $this->Email->lineLength = 1024;
            $this->Email->sendAs = 'text';
            //$this->Email->delivery = 'debug';
            $this->Email->from = $this->_mbConvertEncodingEx($from);
            $this->Email->to = $this->_mbConvertEncodingEx($to);
            $this->Email->return = $return;
            $this->Email->subject = $this->_mbConvertEncodingEx($subject);
            if ($data) {                
                $data = $this->_mbConvertEncodingEx($data);
            }
            $this->set(compact('data'));
            $this->Email->template = $this->_mbConvertEncodingEx($template);
            $this->log(compact('data'));
            $rtn = $this->Email->send();
        }
        return $rtn;
    }
    
    //文字エンコード変換の共通関数
    function _mbConvertEncodingEx($target){
        if(is_array($target)){
            foreach ($target as $key => $val){
                $target[$key] = $this->_mbConvertEncodingEx($val, $this->Email->charset, Configure::read('App.encoding'));
            }
        }else{
            $target = mb_convert_encoding($target, $this->Email->charset, Configure::read('App.encoding'));
        }
        return $target;
    }
    
    
    //画像新規追加：確認画面用　画像ファイルを一時ディレクトリへアップロード＋画像名生成
    /**
     * @param string $model      適用するモデル名
     * @param mixed  $array      フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param int    $image_num  新規追加する際のID
     * @param int    $const      bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF, UPLOAD_PATH_CONF
     * 
     * @return mixed $checkTmpResult 配列に格納される値 => 0:失敗, 1:成功, 2:画像の変更なし or 対象の画像がない
     *  
     */
    function loopAndGenerateImages($model, $array, $image_num, $const){
 
        //処理結果の配列
        $checkTmpResult = array();
        
        foreach ($array as $value) {
            
            if(!empty($this->data[$model][$value]['name']) && $this->data[$model][$value]['name'] !== ""){
                
                $this->data[$model][$value]['name'] = 
                $this->generateFileAndName(
                    $this->data[$model][$value]['name'],
                    $this->data[$model][$value]['tmp_name'],
                    Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}"), 
                    "{$value}_{$image_num}"
                );
                
                if($this->data[$model][$value]['name'] === false){
                    $checkTmpResult[$value] = 0;
                } else {
                    $checkTmpResult[$value] = 1;
                }
                
            }else{
                $checkTmpResult[$value] = 2;
            }

        }
        
        return $checkTmpResult;
         
    }
    
    //画像新規追加：確認画面用　ファイル名作成関数
    /**
     * @param string $field        適用するモデル名
     * @param string $tmpFile      フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param string $uploadTmp    一時保存ディレクトリ名
     * @param string $imageName    新しく生成される画像名
     * 
     * return mixed  $newFileName  OKのときは(string)[新しく生成される画像名].[拡張子] ※NGのときは(bool)falseを返す
     * 
     */
    function generateFileAndName($field, $tmpFile, $uploadTmp, $imageName){        
        
        //一時的なアップロード先の指定
        $uploadDir = Configure::read('upload.path');
        $uploadFile = $uploadDir.DS.$uploadTmp.DS.basename($field);        
        $imageInfo = pathinfo($uploadFile);
        $newFileName = IMAGES.$uploadTmp."/".$imageName.".".$imageInfo['extension'];
        
        if(move_uploaded_file($tmpFile, $newFileName)){
            chmod($newFileName, 0666);
            return str_replace(IMAGES.$uploadTmp."/", "", $newFileName);
        } else {
            //失敗した際はログに内容を書き込む
            $this->log('Cannot Upload Temporary Images.');
            $this->log($this->data);
            return false;
        }                

    }
    
    //画像新規追加：完了画面用　一部バリデーションを無効にする
    /**
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * 
     */    
    function disableValidate($model, $array) {
        foreach ($array as $value){
            unset($this->$model->validate[$value]);            
        }
    }
    
    //画像新規追加：完了画面用　画像名抽出
    /*
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * 
     */
    function imageFieldChange($model, $array){
        foreach ($array as $value){
            $this->data[$model][$value] = $this->data[$model][$value]['name'];
        }    
    }

    //画像新規追加：完了画面用　画像移動＋画像バリエーション作成
    /**
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param mixed  $size   画像の切り取りサイズを指定した配列 ex. array("縦px", "横px", ...)
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF, UPLOAD_PATH_CONF
     * 
     * @return mixed $array( key => '画像格納カラム', value => 'メッセージ' )
     * 
     */
    function addImageReplaceAndCrop($model, $array, $size, $const){
        
        //処理結果の配列
        $checkResult = array();
            
        foreach ($array as $value) {
            
            if(!empty($this->data[$model][$value]) && $this->data[$model][$value] !== ""){
                
                //画像のリプレイス処理
                $tmp_path = IMAGES.Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}").'/'.$this->data[$model][$value]; 
                $move_path = IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/'.$this->data[$model][$value];
                
                //一時画像がある場合
                if(file_exists($tmp_path)){

                    //画像ファイルへの移動
                    if(rename($tmp_path, $move_path) !== false) {

                        //画像の生成を行う
                        $result = $this->createCropValiations($size, $move_path, $this->data[$model][$value], $const);

                        if($result !== false){   
                            $checkResult[$value] = '処理結果：OK';
                        } else {
                            $checkResult[$value] = '処理結果：（エラー）画像リサイズに失敗しました。';
                        }

                    } else {
                        $checkResult[$value] = '処理結果：（エラー）画像ファイル移動に失敗しました。';
                    }                    
                    
                } else {
                    $checkResult[$value] = '処理結果：更新対象の画像はありません。';                    
                }
                
            }

        }
        return $checkResult;
    }

    //画像新規追加：完了画面用　画像の生成関数
    /**
     * @param mixed  $size            画像の切り取りサイズを指定した配列 ex. array("横px", "縦px")
     * @param string $image_path      画像までの絶対パス
     * @param string $org_image_name  元画像の名前
     * @param int    $const           bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF, UPLOAD_PATH_CONF
     * 
     * @return boolean 画像の処理に成功した場合はtrue,失敗した場合はfalseを返す
     * 
     */
    function createCropValiations($size, $image_path, $org_file_name, $const){
        
        list($width, $height, $type) = getimagesize($image_path);
        
        //比率の計算を行う
        $ratio = $width / $height;
        if($size[0] / $size[1] > $ratio){
            $size[0] = ceil($size[1] * $ratio);
        }else{
            $size[1] = ceil($size[0] / $ratio);
        }
        
        //リサイズ後のひな形を作成する
        $canvas = imagecreatetruecolor($size[0], $size[1]);
        
        //ひな形の背景を白く塗りつぶす
        $white = imagecolorallocate($canvas,255,255,255);
        imagefilltoborder($canvas, 0, 0, $white, $white); 
        
        if($type === 1){
            //GIF画像の場合
            $image = imagecreatefromgif($image_path);
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $size[0], $size[1], $width, $height);
            $ret = imagegif($canvas, IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/'."resized_".$org_file_name);
        }elseif($type === 2){
            //JPG画像の場合
            $image = imagecreatefromjpeg($image_path);
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $size[0], $size[1], $width, $height);
            $ret = imagejpeg($canvas, IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/'."resized_".$org_file_name, 100);
            
        }elseif($type === 3){
            //PNG画像の場合
            $image = imagecreatefrompng($image_path);
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $size[0], $size[1], $width, $height);
            $ret = imagepng($canvas, IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/'."resized_".$org_file_name);
        }
        imagedestroy($canvas);
        return $ret;
        
    }
    
    //画像編集：確認画面用　一部バリデーションを無効にする
    /**
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * 
     */    
    function disableValidateForEditConfirm($model, $array) {
        foreach ($array as $value){
            unset($this->$model->validate[$value]['imageExistCheck']);            
        }

    }

    //画像編集：編集画面用　一時画像アップロードディレクトリの削除
    /**
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF
     * 
     */    
    function deleteTmpImage($model, $array, $const) {
        foreach ($array as $value){
            $tmp_path = IMAGES.Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}").'/'.$this->data[$model][$value];
            if(file_exists($tmp_path)) {
                unlink(@$tmp_path);
            }
        }

    }
   
    //画像編集：完了画面用　画像名抽出
    /**
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * 
     */    
    function imageFieldChangeForEditComplete($model, $array){
        foreach ($array[$model] as $key => $value){
            $this->data[$model][$key] = $value;
        }    
    }
    
    //画像編集／画像削除：　表示用画像を削除する
    /**
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_PATH_CONF
     * 
     */    
    function deleteImage($model, $array, $const) {
        foreach ($array[$model] as $value){
            $org_image_path = IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/'.$value;
            if(file_exists($org_image_path)) {
                unlink(@$org_image_path);
            }
            $resized_image_path = IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/resized_'.$value;
            if(file_exists($resized_image_path)) {
                unlink(@$resized_image_path);
            }
        }

    }
    
    //画像追加／画像編集：　画像のピクセルをチェックする（確認画面がない場合に使用）
    /**
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_PATH_CONF
     * @param mixed  $size   画像の切り取りサイズを指定した配列 ex. array("横px", "縦px")
     * @param mixed  $flag   画像の切り取りサイズを指定した配列 ex. array("横pxのチェック[T or F]", "縦pxのチェック[T or F]")
     * 
     * @return mixed $checkTmpResult 配列に格納される値 => 0:サイズ違反がある, 1:サイズ違反がない, 2:ファイルがない
     * 
     */
    function imagePixelCheck($model, $array, $const, $size, $flag){
        //処理結果の配列
        $checkTmpResult = array();
        foreach ($array as $value) {
            
            if(!empty($this->data[$model][$value]['name']) && $this->data[$model][$value]['name'] !== ""){
                
                $tmp_path = IMAGES.Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}").'/'.$this->data[$model][$value]['name'];
                
                //ファイルが存在する
                if(file_exists($tmp_path)){

                    list($width, $height) = getimagesize($tmp_path);

                    $width_flag = true;
                    $height_flag = true;

                    //画像の横サイズのチェック
                    if($flag[0] === true){
                        if($width !== $size[0]){
                            $width_flag = false;
                        }
                    }

                    //画像の縦サイズのチェック
                    if($flag[1] === true){
                        if($height !== $size[1]){
                            $height_flag = false;
                        }
                    }
                    
                    //サイズチェックに引っかかった場合
                    if($width_flag === true && $height_flag === true){
                        $checkTmpResult[$value] = 1;
                    }else{
                        $checkTmpResult[$value] = 0;
                    }
                    
                }else{
                   $checkTmpResult[$value] = 2; 
                } 
                
            }
            
        }
        return $checkTmpResult;
    }
    
    //画像追加／画像編集：　すでに一時ディレクトリへアップロードされている画像名を取得する（確認画面がない場合に使用）
    /**
     * @param string $name   画像のbasename ※例. [カラム名]_[id].[拡張子] => [カラム名]_[id]の部分を指定する
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF
     * 
     * @return mixed $ret    tmp_XXXXXに上がっている最新の画像 ※(画像名).(拡張子)の形式
     * 
     */
    public function isTmpImageExistSingle($name, $const){
        $ret = '';
        $array = array();
        $path = IMAGES.Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}").'/';
        $pat = $path.$name.'.{jpg,jpeg,png,gif}';
        
        //タイムスタンプ順の配列を作成する
        foreach(glob($pat, GLOB_BRACE) as $file){
            //画像のタイムスタンプを取得する
            $filetime = filemtime($file);
            $array[$filetime] = $file;
        }
        
        //タイムスタンプ順に並べ替える
        ksort($array);
        
        //一番最後にアップしたものが取得できるようにする
        foreach($array as $value){
            $ret = str_replace($path, "", $value);
        }
        
        return $ret;
    }
    
    //画像追加／画像編集：　すでに正式ディレクトリへアップロードされている画像名を取得する（確認画面がない場合に使用）
    /**
     * @param string $name   画像のbasename ※例. [カラム名]_[id].[拡張子] => [カラム名]_[id]の部分を指定する
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF
     * 
     * @return mixed $ret    tmp_XXXXXに上がっている最新の画像 ※(画像名).(拡張子)の形式
     * 
     */
     public function isImageExistSingle($name, $const){
        $ret = '';
        $array = array();
        $path = IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/';
        $pat = $path.$name.'.{jpg,jpeg,png,gif}';
        
        //タイムスタンプ順の配列を作成する
        foreach(glob($pat, GLOB_BRACE) as $file){
            //画像のタイムスタンプを取得する
            $filetime = filemtime($file);
            $array[$filetime] = $file;
        }
        
        //タイムスタンプ順に並べ替える
        ksort($array);
        
        //一番最後にアップしたものが取得できるようにする
        foreach($array as $value){
            $ret = str_replace($path, "", $value);
        }
        
        return $ret;
    }
    
    //画像追加／画像編集：　一時ディレクトリ内該当IDの画像を全て削除する（確認画面がない場合に使用）
    /**
     * @param string $name   画像のbasename ※例. [カラム名]_[id].[拡張子] => [カラム名]_[id]の部分を指定する
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF
     * 
     */
    public function deleteTmpImageAllPattern($name, $const){
        $dir_path = IMAGES.Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}").'/';
        
        if(file_exists($dir_path.$name.".jpg")){
            unlink($dir_path.$name.".jpg");
        }
        
        if(file_exists($dir_path.$name.".gif")){
            unlink($dir_path.$name.".gif");
        }
        
        if(file_exists($dir_path.$name.".png")){
            unlink($dir_path.$name.".png");
  
        }
    }
    
    //画像追加／画像編集：　正式ディレクトリ内該当IDの画像を全て削除する（確認画面がない場合に使用）
    /**
     * @param string $name   画像のbasename ※例. [カラム名]_[id].[拡張子] => [カラム名]_[id]の部分を指定する
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF
     * 
     */
    public function deleteImageAllPattern($name, $const){
        $dir_path = IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/';
        
        if(file_exists($dir_path.$name.".jpg")){
            unlink($dir_path.$name.".jpg");
        }
        
        if(file_exists($dir_path.$name.".gif")){
            unlink($dir_path.$name.".gif");
        }
        
        if(file_exists($dir_path.$name.".png")){
            unlink($dir_path.$name.".png");
  
        }
    }
    
    //画像追加／画像編集：　画像移動のみを行う（確認画面がない場合に使用）
    /**
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF, UPLOAD_PATH_CONF
     * 
     * @return mixed $array( key => '画像格納カラム', value => 'メッセージ' )
     * 
     */
    public function addImageReplaceOnly($model, $array, $const){
        
        //処理結果の配列
        $checkResult = array();
            
        foreach ($array as $value) {
            
            if(!empty($this->data[$model][$value]) && $this->data[$model][$value] !== ""){
                
                //画像のリプレイス処理
                $tmp_path = IMAGES.Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}").'/'.$this->data[$model][$value]; 
                $move_path = IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/'.$this->data[$model][$value];
                
                //一時画像がある場合
                if(file_exists($tmp_path)){

                    //画像ファイルへの移動
                    if(rename($tmp_path, $move_path) !== false) {
                        $checkResult[$value] = '処理結果：OK';
                    } else {
                        $checkResult[$value] = '処理結果：（エラー）画像ファイル移動に失敗しました。';
                    }                    
                    
                } else {
                    $checkResult[$value] = '処理結果：更新対象の画像はありません。';                    
                }
                
            }

        }
        return $checkResult;
    }
}
