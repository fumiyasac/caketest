<?php
/**
 * 
 * アンケートコンテンツ専用コンポーネント 
 * Date:    2014/10/18
 * Created: Fumiya Sakai
 *
 */

class PostEnqueteAccessComponent extends Object {
    
	private $loop_max_count = 5;
	
	//アンケートのコンテンツをマージした配列を返す
	public function mergeEnqueteElements($posts_questions, $posts_answers){
		//格納用配列と変数
		$enquete_elements = array();
		$number = 1;
		
		for($i = 0; $i < $this->loop_max_count; $i++){
			$enquete_elements[$number]['post_id']          = $posts_questions[$i]['PostsQuestion']['post_id'];
			$enquete_elements[$number]['post_question_id'] = $posts_questions[$i]['PostsQuestion']['id'];
			$enquete_elements[$number]['type']             = $posts_questions[$i]['PostsQuestion']['type'];
			$enquete_elements[$number]['question']         = $posts_questions[$i]['PostsQuestion']['question'];
			$enquete_elements[$number]['required']         = $posts_questions[$i]['PostsQuestion']['required'];
			$enquete_elements[$number]['post_answer_id']   = $posts_answers[$i]['PostsAnswer']['id'];
			$enquete_elements[$number]['answer']           = $posts_answers[$i]['PostsAnswer']['answer'];
			$number++;
		}
		
		return $enquete_elements;
	}
	
	//確認画面で取得したデータをマージした配列を返す
	public function mergeElementsForConfirm($post_data){
		//格納用配列と変数
		$enquete_elements = array();
		
		for($i = 1; $i <= $this->loop_max_count; $i++){
			$enquete_elements[$i]['required']        = $post_data['required'.$i];
			$enquete_elements[$i]['enqueteQuestion'] = $post_data['enqueteQuestion'.$i];
			$enquete_elements[$i]['enqueteType']     = $post_data['enqueteType'.$i];
			$enquete_elements[$i]['enquete']         = $post_data['enquete'.$i];
		}
		
		return $enquete_elements;
	}

	//アンケート項目のバリデーションを行う
	public function enqueteValidate($data){
		
		$error_message = array();
		for($i = 1; $i <= $this->loop_max_count; $i++){
			
			if($data['enqueteType'.$i] < 4){
				
				//チェックボックス以外
				if( $data['required'.$i] == 1 && empty($data['enquete'.$i]) ){
					$error_message[$i] = "質問事項" . $i . "が入力または選択されていません";
				}
				
			}else{
				
				//チェックボックス
				if( $data['required'.$i] == 1 && $this->enqueteMigrateForValidate($data, $i) == false ){
					$error_message[$i] = "質問事項" . $i . "が入力または選択されていません";
				}
				
			}
			
		}
		return $error_message;
	}

	//アンケート項目のマイグレーションを行う(hiddenタグ用)
	public function enqueteMigrateForHidden($data){

		for($i = 1; $i <= $this->loop_max_count; $i++){

			$migrate_array = array();
			
			if($data['enqueteType'.$i] == 4){
				$pattern_string = '/enquete' . $i . 'Check/i';
				foreach($data as $key => $value){
					if(preg_match($pattern_string, $key)){
						$migrate_array[] = $value;
						unset($data[$key]);
					}
					
				}
			}
			
			if(!empty($migrate_array)){
				$migrate_value = implode("," , $migrate_array);
				$data['enquete'.$i] = $migrate_value;
			}			
			
		}
		return $data;
		
	}

	//アンケート項目のマイグレーションを行う(バリデーション用)
	public function enqueteMigrateForValidate($data, $i){
		
		$migrate_array = array();
		
		if($data['enqueteType'.$i] == 4){
			$pattern_string = '/enquete' . $i . 'Check/i';
			foreach($data as $key => $value){
				if(preg_match($pattern_string, $key)){
					$migrate_array[] = $value;
				}
				
			}
		}
		
		if(!empty($migrate_array)){
			$migrate_value = implode("," , $migrate_array);
			return $migrate_value;
		}else{
			return false;			
		}
		
	}

}