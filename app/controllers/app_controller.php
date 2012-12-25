<?php
class AppController extends Controller {
    
    public $components = array(
        'DebugKit.Toolbar'
    );
    
    //メール送信の共通関数
    public function _sendMail($options = array()){
        $rtn = false;
        if($options){
            
            extract($options);
            $this->Email->reset();
            $this->Email->language = 'Japanese';
            $this->Email->charset = 'UTF-8';
            $this->Email->lineLength = 1024;
            $this->Email->sendAs = 'text';
            //$this->Email->delivery = 'debug';
            $this->Email->from = $this->_mbConvertEncodingEx($from);
            $this->Email->to = $this->_mbConvertEncodingEx($to);
            $this->Email->return = $return;
            $this->Email->subject = $this->_mbConvertEncodingEx($subject);
            if ($data) {                
                $data = $this->_mbConvertEncodingEx($data);
            }
            $this->set(compact('data'));
            $this->Email->template = $this->_mbConvertEncodingEx($template);
            $this->log(compact('data'));
            $rtn = $this->Email->send();
        }
        return $rtn;
    }
    
    //文字エンコード変換の共通関数
    function _mbConvertEncodingEx($target){
        if(is_array($target)){
            foreach ($target as $key => $val){
                $target[$key] = $this->_mbConvertEncodingEx($val, $this->Email->charset, Configure::read('App.encoding'));
            }
        }else{
            $target = mb_convert_encoding($target, $this->Email->charset, Configure::read('App.encoding'));
        }
        return $target;
    }
}
