<?php
Class CommonDefine extends Object{
	
	//会員用ページ判別用配列を返す関数
	public function member_page_settings(){
		return array(
			//members_controller
			'members' => array(
				'mypage'
			)
		);
	}
	
}
?>