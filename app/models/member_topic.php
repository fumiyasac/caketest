<?php
Class MembersTopic extends AppModel{
    
    //モデル名
    public $name = 'MembersTopic';
    
    //バリデーション
    
    //private変数（マイページ用取得件数　デフォルト：5）
	private $count_member_topic_num = 5;
	
    //private変数（公開フラグ　デフォルト：1）    
    private $published_flag         = 1;
    
    //会員専用情報を最新を取得する
    public function getNewestMemberTopic(){
        
        $conditions = array(
        	//取得条件
        	'conditions' => array(
        		'MemberTopic.flag' => $this->published_flag
			),
			//ソート順
			'order' => 'MemberTopic.id DESC',
			//件数
			'limit' => $this->count_member_topic_num			
        );
        $newestMemberTopics = $this->find('all', $conditions);
        return $newestMemberTopics;
    }
    
}
?>