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
			
			$nameTag = 'enquete' . $num;
			$nameValue = $this->getValuesByArrayKey($params, $nameTag);
			$tag = '<input type="text" name="' . $nameTag . '" value="' . $nameValue . '" class="formArea" />';
			
		//テキストエリア
		}elseif($type == 2){
			
			$nameTag = 'enquete' . $num;
			$nameValue = $this->getValuesByArrayKey($params, $nameTag);
			$tag = '<textarea name="' . $nameTag . '" cols="40" rows="5" class="formAreaText">'. $nameValue .'</textarea>';
			
		//ラジオボタン
		}elseif($type == 3){
			
			$data_array = explode(',', $answer);
			$tag = '';
			
			$nameTag = 'enquete' . $num;
			$nameValue = $this->getValuesByArrayKey($params, $nameTag);
			
			foreach($data_array as $value){
				
				if($nameValue == $value){
					$checked = 'checked';
				}else{
					$checked = '';					
				}
			
				$tag .= '<label>';
				$tag .= '<input class="radio" type="radio" name="'. $nameTag .'" value="' . $value . '" '. $checked .'>' . $value;
				$tag .= '</label> ';
			}
			
		//チェックボックス
		}elseif($type == 4){

			$data_array = explode(',', $answer);
			$tag = '';
			$counter = 1;
			foreach($data_array as $value){			

				$nameTag = 'enquete' . $num .'Check'. $counter;
				$nameValue = $this->getValuesByArrayKey($params, $nameTag);
				
				if($nameValue == $value){
					$checked = 'checked';
				}else{
					$checked = '';					
				}

				$tag .= '<label>';
				$tag .= '<input class="check" type="checkbox" name="'. $nameTag .'" value="' . $value . '" '. $checked .'>' . $value;
				$tag .= '</label> ';
				$counter++;
			}
			
		//ヒドゥンタグ
		}else{
			
			$nameTag = 'enquete' . $num;
			$nameValue = $this->getValuesByArrayKey($params, $nameTag);
			$tag = '<input type="hidden" name="' . $nameTag . '" value="' . $nameValue . '" />';
			
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