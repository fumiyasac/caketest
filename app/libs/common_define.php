<?php
/**
 *
 * commmon_define.php
 *
 * 共通設定ライブラリクラス
 * Date:    2014/10/05
 * Created: Fumiya Sakai
 *
 */

class CommonDefine extends Object{
	
	//会員用ページ判別用配列を返す関数
    /**
     * @return mixed $array 'controller' => 'action'
     *  
     */
	public function member_page_settings(){
		return array(
			//members_controller
			'members' => array(
				'mypage'
			)
		);
	}
	
}