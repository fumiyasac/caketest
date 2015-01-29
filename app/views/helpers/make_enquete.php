<?php
/**
 * アンケート用ヘルパー
 * テキストボックス・テキストエリア・ラジオボタン・テキストボックスを作成する
 */
class MakeEnqueteHelper extends AppHelper{
    
    //出力要素用の定数値（1:テキストボックス, 2:テキストエリア, 3:ラジオボタン, 4:チェックボックス）
	const ELEMENT_TEXTBOX  = 1;
	const ELEMENT_TEXTAREA = 2;
	const ELEMENT_RADIO    = 3;
	const ELEMENT_CHECKBOX = 4;
	
	//必須フラグ値（デフォルト：1）
	const REQUIRED_FLAG    = 1;
    
    //フォーム回答用エレメントを作成する
    public function makeEnqueteAnswerModule($num, $type, $params = array(), $answer = null){
	    
	    //テキストボックス
		if($type == self::ELEMENT_TEXTBOX){
			
			$name_tag   = 'enquete' . $num;
			$name_value = $this->_getValuesByArrayKey($params, $name_tag);
			
			$tag = '<input type="text" name="' . $name_tag . '" value="' . $name_value . '" class="formArea" />';
			
		//テキストエリア
		}elseif($type == self::ELEMENT_TEXTAREA){
			
			$name_tag   = 'enquete' . $num;
			$name_value = $this->_getValuesByArrayKey($params, $name_tag);
			
			$tag = '<textarea name="' . $name_tag . '" cols="40" rows="5" class="formAreaText">'. $name_value .'</textarea>';
			
		//ラジオボタン
		}elseif($type == self::ELEMENT_RADIO){
			
			$data_array = explode(',', $answer);
			$tag = '';
			
			$name_tag   = 'enquete' . $num;
			$name_value = $this->_getValuesByArrayKey($params, $name_tag);
			
			foreach($data_array as $value){
				
				if($name_value == $value){
					$checked = 'checked';
				}else{
					$checked = '';					
				}
			
				$tag .= '<label>';
				$tag .= '<input class="radio" type="radio" name="'. $name_tag .'" value="' . $value . '" '. $checked .'>' . $value;
				$tag .= '</label> ';
			}
			
		//チェックボックス
		}elseif($type == self::ELEMENT_CHECKBOX){

			$data_array = explode(',', $answer);
			$tag = '';
			
			$counter = 1;
			foreach($data_array as $value){			

				$name_tag   = 'enquete' . $num .'Check'. $counter;
				$name_value = $this->_getValuesByArrayKey($params, $name_tag);
				
				if($name_value == $value){
					$checked = 'checked';
				}else{
					$checked = '';					
				}

				$tag .= '<label>';
				$tag .= '<input class="check" type="checkbox" name="'. $name_tag .'" value="' . $value . '" '. $checked .'>' . $value;
				$tag .= '</label> ';
				$counter++;
			}
			
		//ヒドゥンタグ
		}else{
			
			$name_tag   = 'enquete' . $num;
			$name_value = $this->_getValuesByArrayKey($params, $name_tag);
			$tag = '<input type="hidden" name="' . $name_tag . '" value="' . $name_value . '" />';
			
		}
		$tag .= '<input type="hidden" name="enqueteType' . $num . '" value="' . $type . '" />';	    
	    
	    return $tag;
    }
    
    //フォーム質問用エレメントを作成する
    public function makeEnqueteQuestionModule($num, $required, $type, $question){
	    $tag  = '<strong>質問' . $num . '</strong>';
	    if($required == self::REQUIRED_FLAG){
			$tag .= '&nbsp;<span class="requierd">*必須項目</span>';
	    }
		$tag .= '<input type="hidden" name="required' . $num . '" value="' . $required . '" />';
		$tag .= '<br>' . $question;
		$tag .= '<input type="hidden" name="enqueteQuestion' . $num . '" value="' . $question . '" />';
	    
	    return $tag;
    }
    
    //private:keyに相当する変数を取得する
    private function _getValuesByArrayKey($params = array(), $key){
	    if( !empty($params[$key]) ){
		    return $params[$key];
	    }else{
		    return '';
	    }
    }
    
}