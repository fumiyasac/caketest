<?php
class MakeEnqueteHelper extends AppHelper{
     
    /**
     * アンケート用ヘルパー
     * テキストボックス・テキストエリア・ラジオボタン・テキストボックスを作成する
     */
        
    //フォーム回答用エレメントを作成する
    public function makeEnqueteAnswerModule($num, $type, $param = null, $answer = null){
	    
	    //テキストボックス
		if($type == 1){
		
			$tag = '<input type="text" name="enquete' . $num . '" value="' . $param . '" class="formArea" />';
			
		//テキストエリア
		}elseif($type == 2){
			
			$tag = '<textarea name="enquete' . $num . '" cols="40" rows="5" class="formAreaText">'. $param .'</textarea>';
			
		//ラジオボタン
		}elseif($type == 3){
			
			$data_array = explode(',', $answer);
			$tag = '';
			foreach($data_array as $value){
				$tag .= '<label>';
				$tag .= '<input class="radio" type="radio" name="enquete'. $num .'" value="' . $value . '">' . $value;
				$tag .= '</label> ';
			}
			
		//チェックボックス
		}elseif($type == 4){

			$data_array = explode(',', $answer);
			$tag = '';
			$counter = 1;
			foreach($data_array as $value){			
				$tag .= '<label>';
				$tag .= '<input class="check" type="checkbox" name="enquete'. $num .'Check'. $counter .'" value="' . $value . '">' . $value;
				$tag .= '</label> ';
				$counter++;
			}
			
		//ヒドゥンタグ
		}else{
			
			$tag = '<input type="hidden" name="enquete' . $num . '" value="' . $param . '" />';
			
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
		$tag .= '<br>' . $question;	    	    
		$tag .= '<input type="hidden" name="enqueteQuestion' . $num . '" value="' . $question . '" />';	    
	    
	    return $tag;
    }
    
}