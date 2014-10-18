<?php
class Contact extends AppModel{
    
    //モデル名
    public $name = 'Contact';
    
    //バリデーション
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty',
            'message' => 'お名前は必須項目になります'
        ),
        'kana' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'フリガナは必須項目になります',
                'last' => true,
            ),
            'notKana' => array(
                'rule' => array('kanaCheck'),
                'message' => 'フリガナは半角英数字で入力して下さい',
            ),
        ),
        'mail' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => 'メールアドレスは必須項目になります',
                'last' => true,
            ),
            'notPattern' => array(
                'rule' => array('email', false, '/^[a-z0-9\._-]{1,64}@(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)$/i'), 
                'message' => 'メールアドレスを正しく入力して下さい',
            ),
        ),
       'mail_conf' => array(
            'rule' => array('mailAddressMatch'),
            'message' => 'メールアドレスが一致しません',
        ),
       'purpose' => array(
            'notEmpty' => array(    
                'rule' => 'notEmpty',
                'message' => '目的の選択は必須項目になります',
                'last' => true,
            ),
            'etcPattern' => array(
                'rule' => array('relatedEtc'),
                'allowEmpty' => true,
                'message' => '「その他」を選択した際はテキスト欄へ入力をお願いします',
            ),
       ),
       'text' => array(
            'rule' => 'notEmpty',
            'message' => '本文は必須項目になります',
        ),
        
    );
    
    //バリデーション：メールアドレスの照合（確認で入力したものと同じか否か）
    public function mailAddressMatch($data){        
        //確認用ではないメールアドレスのデータを取得
        $p_mail = $this->data['Contact']['mail'];
        //確認用のメールアドレスのデータを取得
        $p_mail_conf = array_shift($data);
        
        if($p_mail == $p_mail_conf){
            return true;
        }else{
            return false;
        }
    }
    
    //バリデーション：業種とその他ボックスの連携を行う
    public function relatedEtc($data){
        //業種のテキスト欄を取得
        $p_purpose_etc = $this->data['Contact']['purpose_etc'];
        $p_purpose = array_shift($data);
        
        //その他か否かの判定
        if($p_purpose == 7 && !$p_purpose_etc){
            return false;
        }else{
            return true;
        }
    }
    
    //データを物理削除する
    public function deleteDataById($id){
        
        //削除処理
        $this->delete($id);

        //全ての件数の取得
        $allAmount = count($this->find('all'));
		
        //変更したステータスの取得
        $response = array('id' => $id, 'allAmount' => $allAmount);
		return $response;
    }
    
}