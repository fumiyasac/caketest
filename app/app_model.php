<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.model
 */
class AppModel extends Model {
    
    //数字のチェック
    public function numericCheck($data){
        $check_str = array_shift($data);
        if(ctype_digit($check_str)){
            return true;
        }else{
            return false;
        }
    }
    
    //全角カナのチェック
    public function kanaCheck($data){
        $check_str = array_shift($data);
        $pattern = '/^[ァ-ヶー]+$/u';
        if(preg_match($pattern, $check_str)){
            return true;
        }else{
            return false;
        }
    }
    
    //画像に関するチェック（存在チェック）
    public function imageExistCheck($data, $field){
        if($this->data[$this->alias][$field]['error'] === 0 && !empty($this->data[$this->alias][$field]['name'])){
            return true;
        }else{
            return false;
        }
    }    
    
    //画像に関するチェック（Mimeタイプチェック）
    public function imageMimeCheck($data, $field, $types){
        if($this->data[$this->alias][$field]['error'] === 0){
            foreach($types as $type){
                if(strpos($this->data[$this->alias][$field]['type'], $type) !== false){
                    return true;
                }
            }
            return false;
        }else{
            return true;
        }
    }
    
    //画像に関するチェック（容量チェック）
    public function imageVolumeCheck($data, $field, $volume){
        if($this->data[$this->alias][$field]['error'] === 0){
            if($this->data[$this->alias][$field]['size'] < $volume){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    //画像に関するチェック（縦横サイズ完全一致チェック）
    public function imagePixelMatchCheck($data, $field, $pixel){
    	
    	list($width, $height, $type, $attr) = getimagesize($this->data[$this->alias][$field]['tmp_name']);
    
        if($width != $pixel[0] || $height != $pixel[1]){
            return false;
        }else{
            return true;
        }
    }
    
    //次のAUTO_INCREMENTの値を取得する
    public function getNextAutoIncrement(){ 

        $next_increment = 0; 
        $table = Inflector::tableize($this->name); 
        $query = "SHOW TABLE STATUS LIKE '$table'"; 
        $db =& ConnectionManager::getDataSource($this->useDbConfig); 
        $result = $db->rawQuery($query); 

        while ($row = mysql_fetch_assoc($result)) { 
            $next_increment = $row['Auto_increment']; 
        } 
        return $next_increment; 
        
    } 
    
}
