<?php
/**
 * サムネイル表示用ヘルパー
 * 管理画面用・サムネイル用等の画像出力を行うヘルパークラス
 * Date:    2014/10/07
 * Created: Fumiya Sakai
 *
 */
class DisplayImageHelper extends AppHelper{
    
    //アップロード対象のディレクトリ
    private $base_directory = 'img'; 
     
    //管理画面用のサムネイル画像タグを生成する
    /**
     * @param string $filename          ファイル名
     * @param int    $directory_number  ディレクトリ番号
     * @param mixed  $height            縦ピクセル
     * @param mixed  $width             横ピクセル
     * @param bool   $tmp_flag          一時保存ディレクトリへ向けるかを判定 => true:向ける, false:向けない
     *
     * @return string $image_tag imgタグ
     *  
     */
    public function displayControlThumbnail($filename, $directory_number, $height = null, $width = null, $tmp_flag = false){
	    
	    //一時保存ディレクトリに向けるか否かの判定
	    if($tmp_flag == true){
	    	$target_directory = DS . $this->base_directory . DS . Configure::read("UPLOAD_TMP_PATH_CONF.path.{$directory_number}") . DS;
	    }else{
	    	$target_directory = DS . $this->base_directory . DS . Configure::read("UPLOAD_PATH_CONF.path.{$directory_number}") . DS;
	    }
	    
	    //サイズの取得
	    $height_attribute = isset($height) ? ' height="' . $height .'"' : '';
	    $width_attribute  = isset($width)  ? ' width="' . $width .'"' : '';
	    
	    //イメージタグの生成
	    return '<img src="'. $target_directory . h($filename) . '"' . $height_attribute . $width_attribute . '>';
	    
    }

    //サムネイル画像パスを生成する
    /**
     * @param string $filename          ファイル名
     * @param int    $directory_number  ディレクトリ番号
     * @param bool   $resized_flag      true:リサイズ済み, false:元画像
     *
     * @return string $image_path 画像へのパス
     *  
     */
    public function putThumbnailPath($filename, $directory_number, $resized_flag = false){
	    
	    $target_directory = DS . $this->base_directory . DS . Configure::read("UPLOAD_PATH_CONF.path.{$directory_number}") . DS;
	    
	    //一時保存ディレクトリに向けるか否かの判定
	    if($resized_flag == true){
	    	$target_filename = 'resized_' . $filename;
	    }else{
	    	$target_filename = $filename;
	    }
	    	    
	    //画像パスの生成
	    return $target_directory . h($target_filename);	    
    }

    //ページ用のサムネイル画像タグを生成する
    /**
     * @param string $filename          ファイル名
     * @param int    $directory_number  ディレクトリ番号
     * @param mixed  $height            縦ピクセル
     * @param mixed  $width             横ピクセル
     * @param bool   $resized_flag      true:リサイズ済み, false:元画像
     *
     * @return string $image_tag imgタグ
     *  
     */
    public function displayPageThumbnail($filename, $directory_number, $height = null, $width = null, $resized_flag = false){
	    
	    $target_directory = DS . $this->base_directory . DS . Configure::read("UPLOAD_PATH_CONF.path.{$directory_number}") . DS;
	    
	    //一時保存ディレクトリに向けるか否かの判定
	    if($resized_flag == true){
	    	$target_filename = 'resized_' . $filename;
	    }else{
	    	$target_filename = $filename;
	    }
	    
	    //サイズの取得
	    $height_attribute = isset($height) ? ' height="' . $height .'"' : '';
	    $width_attribute  = isset($width)  ? ' width="' . $width .'"' : '';
	    
	    //イメージタグの生成
	    return '<img src="'. $target_directory . h($target_filename) . '"' . $height_attribute . $width_attribute . '>';	    
    }
     
}