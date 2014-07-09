<?php
class PostEnqueteComponent extends Object {
    
    /**
     * アンケート用関数
     * アンケートコンテンツ専用コンポーネント 
     */
	
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
	
	
}
?>