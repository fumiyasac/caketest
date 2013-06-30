<?php
class DateformHelper extends FormHelper {
 
    // 日本語YMD形式の日付選択（Y年m月d日の形式を取得）
    /*
     * @param string $fieldName Prefix name for the SELECT element
     * @param string $selected Option which is selected.
     * @param string $attributes array of Attributes
     * @return string Generated set of select boxes for the date and time formats chosen.
     * 
     */
    function dateYMD($fieldName, $selected = null, $attributes = array()) {
        if(!isset($this->options['month'])){
            $this->options['month'] = array();
            for ($i = 1 ; $i <= 12 ; $i++) {
                $this->options['month'][sprintf("%02d", $i)] = $i;
            }
        }
        $sep = array("","","");
        if(isset($attributes['separator'])){
            if(is_array($attributes['separator'])){
                $sep = $attributes['separator'];
                $attributes['separator'] = "";
            }
        }else{
            $attributes['separator'] = "";
            $sep = array(" 年 "," 月 "," 日 ");
        }
        $ret = parent::dateTime($fieldName, 'YMD', null, $selected, $attributes);
       
        $ret = preg_replace('|</select>|', '{/select}'.@$sep[0], $ret, 1);
        $ret = preg_replace('|</select>|', '{/select}'.@$sep[1], $ret, 1);
        $ret = preg_replace('|</select>|', '{/select}'.@$sep[2], $ret, 1);
        $ret = str_replace('{/select}', '</select>', $ret);
        return $ret;
    }
}
?>
