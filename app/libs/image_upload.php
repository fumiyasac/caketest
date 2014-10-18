<?php
/**
 *
 * image_upload.php
 *
 * 画像アップロード用ライブラリクラス
 * Date:    2014/10/05
 * Created: Fumiya Sakai
 *
 */

class ImageUpload extends Object{
		
    //画像新規追加：確認画面用　画像ファイルを一時ディレクトリへアップロード＋画像名生成
    /**
     * @param mixed  $data       データ ※$this->dataの値
     * @param string $model      適用するモデル名
     * @param mixed  $array      フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param int    $const      bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF, UPLOAD_PATH_CONF
     * 
     * @return mixed $checkTmpResult 配列に格納される値 => 0:失敗, 1:成功(成功の場合は一時ファイル名も返す), 2:画像の変更なし or 対象の画像がない
     *  
     */
    public function loopAndGenerateImages( $data, $model, $array, $const ){
 
        //処理結果の配列
        $check_tmp_result = array();
        
        //一時保存処理        
        foreach( $array as $value ) {
            
            if( !empty( $data[$model][$value]['name'] ) && $data[$model][$value]['name'] !== "" ){
                
                $data[$model][$value]['name'] = $this->_generateFileAndName(
                    $data[$model][$value]['name'],
                    $data[$model][$value]['tmp_name'],
                    Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}") 
                );
                
                if( $data[$model][$value]['name'] === false ){
                    $check_tmp_result[$value]['result_code'] = 0;
                }else{
                    $check_tmp_result[$value]['result_code']   = 1;
                    $check_tmp_result[$value]['tmp_file_name'] = $data[$model][$value]['name'];
                }
                
            }else{
                $check_tmp_result[$value]['result_code'] = 2;
            }

        }
        return $check_tmp_result;
    }

    //画像新規追加：確認画面用　ファイル名作成関数
    /**
     * @param string $field         適用するモデル名
     * @param string $tmp_file      フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param string $upload_tmp    一時保存ディレクトリ名
     * 
     * return mixed  $newFileName  OKのときは(string)[新しく生成される画像名].[拡張子] ※NGのときは(bool)falseを返す
     * 
     */
    private function _generateFileAndName( $field, $tmp_file, $upload_tmp ){        
        
        //一時的なアップロード先の指定
        $upload_dir    = Configure::read( 'upload.path' );
        $upload_file   = $upload_dir . DS . $upload_tmp . DS . basename( $field );
        $image_info    = pathinfo( $upload_file );
        $new_file_name = IMAGES.$upload_tmp . DS . $image_info['basename'];
        
        //一時画像ファイル書き込み処理
        if(move_uploaded_file( $tmp_file, $new_file_name ) ){
            chmod( $new_file_name, 0666 );
            return str_replace( IMAGES . $upload_tmp . DS, "", $new_file_name );
        }else {
            //失敗した際はログに内容を書き込む
            $this->log( 'Cannot Upload Temporary Images.' );
            return false;
        }
    }
        
    //画像新規追加：完了画面用　画像名抽出
    /*
     * @param mixed  $data   データ ※$this->dataの値
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * 
     */
    function imageFieldChange($data, $model, $array, $next_id){
        foreach ($array as $value){
            
        	//拡張子のみを取得し、フィールド名_ID.拡張子という文字列を作成
	        $tmp_file_name = $data[$model][$value]['name'];
			$extension     = pathinfo($tmp_file_name, PATHINFO_EXTENSION);
			$new_file_name = $value . '_' . $next_id . '.' . $extension;	        	

			//配列の詰め替えを行う			
	        $data[$model][$value]             = $new_file_name;
	        $data[$model]["tmp_file_".$value] = $tmp_file_name;
	        $data[$model]["new_file_".$value] = $new_file_name;
        }
        return $data;
    }

    //画像新規追加：完了画面用　画像移動＋画像バリエーション作成
    /**
     * @param mixed  $data        データ ※$this->dataの値
     * @param string $model       適用するモデル名
     * @param mixed  $array       フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param mixed  $size        画像の切り取りサイズを指定した配列 ex. array("縦px", "横px", ...)
     * @param bool   $ratio_flag  画像の切り取りサイズの比率 ex. true:比率計算をする false:比率計算をしない
     * @param int    $const       bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF, UPLOAD_PATH_CONF
     * @param int    $move_only   true：移動のみを行う, false：移動＋リサイズ
     * 
     * @return mixed $array( key => '画像格納カラム', value => 'メッセージ' )
     * 
     */
    function addImageReplaceAndCrop($data, $model, $array, $size, $ratio_flag, $const, $move_only = false){
        
        //処理結果の配列
        $checkResult = array();
            
        foreach ($array as $value) {
            
            if(!empty($data[$model][$value]) && $data[$model][$value] !== ""){
                
                //画像のリプレイス処理
                $tmp_file_name = $data[$model]["tmp_file_".$value];
                $new_file_name = $data[$model]["new_file_".$value];
                
                $tmp_path = IMAGES.Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}").'/'.$tmp_file_name; 
                $move_path = IMAGES.Configure::read("UPLOAD_PATH_CONF.path.{$const}").'/'.$new_file_name;
                
                //一時画像がある場合
                if( !empty($tmp_file_name) && !empty($new_file_name) ){

                    //画像ファイルへの移動
                    if(rename($tmp_path, $move_path) !== false) {

                        //画像の生成を行う
                        if($move_only === false){
                        	$result = $this->createCropValiations($size, $ratio_flag, $move_path, $data[$model][$value], $const);	                        
                        }else{
	                        $result = true;
                        }

						//処理結果を返す
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
     * @param bool   $ratio_flag      画像の切り取りサイズの比率 ex. true:比率計算をする false:比率計算をしない
     * @param string $image_path      画像までの絶対パス
     * @param string $org_image_name  元画像の名前
     * @param int    $const           bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF, UPLOAD_PATH_CONF
     * 
     * @return boolean 画像の処理に成功した場合はtrue,失敗した場合はfalseを返す
     * 
     */
    function createCropValiations($size, $ratio_flag, $image_path, $org_file_name, $const){
        
        list($width, $height, $type) = getimagesize($image_path);
        
        if($ratio_flag){
			
			//比率の計算を行う
	        $ratio = $width / $height;
	        if($size[0] / $size[1] > $ratio){
	            $size[0] = ceil($size[1] * $ratio);
	        }else{
	            $size[1] = ceil($size[0] / $ratio);
	        }
	        
        }else{
	        
	        //サイズの指定がない場合は、元イメージのサイズを当てはめる
	        if(empty($size)){
		    	$size[0] = $width;
		    	$size[1] = $height;		        
	        }
	    		        
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
     * @param mixed  $data   データ ※$this->dataの値
     * @param string $model  適用するモデル名
     * @param mixed  $array  フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param int    $const  bootstrap.phpで設定したディレクトリ配列定数の番号　※配列名：UPLOAD_TMP_PATH_CONF
     * 
     */    
    function deleteTmpImage($data, $model, $array, $const) {
        foreach ($array as $value){
            $tmp_path = IMAGES.Configure::read("UPLOAD_TMP_PATH_CONF.path.{$const}").'/'.$data[$model][$value];
            if(file_exists($tmp_path)) {
                unlink(@$tmp_path);
            }
        }
    }
   
    //画像編集：完了画面用　画像名抽出
    /**
     * @param mixed  $data      データ ※$this->dataの値
     * @param string $model     適用するモデル名
     * @param mixed  $array     フィールドを格納した配列 ex. array("画像格納フィールド名" ...)
     * @param string $id        ID（プライマリキー）
     * @param string $filename  ファイル名
     * 
     */    
    function imageFieldChangeForEditComplete($data, $model, $array, $id, $filename){

        foreach ($array as $value){
            
            //拡張子のみを取得し、フィールド名_ID.拡張子という文字列を作成
        	if($data[$model][$value]['name']){
	        	$tmp_file_name = $data[$model][$value]['name'];
				$extension     = pathinfo($tmp_file_name, PATHINFO_EXTENSION);
				$new_file_name = $value . '_' . $id . '.' . $extension;	        	

				//配列の詰め替えを行う			
	            $data[$model][$value]             = $new_file_name;
	            $data[$model]["tmp_file_".$value] = $tmp_file_name;
	            $data[$model]["new_file_".$value] = $new_file_name;

        	}else{

				//配列の詰め替えを行う			
	            $data[$model][$value]             = $filename[$model][$value];
	            $data[$model]["tmp_file_".$value] = '';
	            $data[$model]["new_file_".$value] = '';	        	
        	}
            
        }
        return $data;
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
    
}