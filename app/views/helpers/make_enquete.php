<?php
class MakeEnqueteHelper extends AppHelper{
     
    /**
     * アンケート用ヘルパー
     * テキストボックス・テキストエリア・ラジオボタン・テキストボックスを作成する
     */
        
    //フォーム回答用エレメントを作成する
    public function makeEnqueteAnswerModule($num, $type, $params = array(), $answer = null){
	    
	    //テキストボックス
		if($type == 1){
			
			$name_tag   = 'enquete' . $num;
			$name_value = $this->getValuesByArrayKey($params, $name_tag);
			
			$tag = '<input type="text" name="' . $name_tag . '" value="' . $name_value . '" class="formArea" />';
			
		//テキストエリア
		}elseif($type == 2){
			
			$name_tag   = 'enquete' . $num;
			$name_value = $this->getValuesByArrayKey($params, $name_tag);
			
			$tag = '<textarea name="' . $name_tag . '" cols="40" rows="5" class="formAreaText">'. $name_value .'</textarea>';
			
		//ラジオボタン
		}elseif($type == 3){
			
			$data_array = explode(',', $answer);
			$tag = '';
			
			$name_tag   = 'enquete' . $num;
			$name_value = $this->getValuesByArrayKey($params, $name_tag);
			
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
		}elseif($type == 4){

			$data_array = explode(',', $answer);
			$tag = '';
			
			$counter = 1;
			foreach($data_array as $value){			

				$name_tag   = 'enquete' . $num .'Check'. $counter;
				$name_value = $this->getValuesByArrayKey($params, $name_tag);
				
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
			$name_value = $this->getValuesByArrayKey($params, $name_tag);
			$tag = '<input type="hidden" name="' . $name_tag . '" value="' . $name_value . '" />';
			
		}
		$tag .= '<input type="hidden" name="enqueteType' . $num . '" value="' . $type . '" />';	    
	    
	    return $tag;
    }
    
    //フォーム質問用エレメントを作成する
    public function makeEnqueteQuestionModule($num, $required, $type, $question){
	    $tag  = '<strong>質問' . $num . '</strong>';
	    if($required == 1){
			$tag .= '&nbsp;<span class="requierd">*必須項目</span>';
	    }
		$tag .= '<input type="hidden" name="required' . $num . '" value="' . $required . '" />';
		$tag .= '<br>' . $question;
		$tag .= '<input type="hidden" name="enqueteQuestion' . $num . '" value="' . $question . '" />';
	    
	    return $tag;
    }
    
    //private:keyに相当する変数を取得する
    private function getValuesByArrayKey($params = array(), $key){
	    if( !empty($params[$key]) ){
		    return $params[$key];
	    }else{
		    return '';
	    }
    }
    
    
    
    
}