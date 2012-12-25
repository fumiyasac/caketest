<?php
class FormhiddenHelper extends AppHelper {

	var $helpers = array('Form');
	var $list = array();
	var $listType = true; // true:whitelist false:blacklist

	/**
	* $this->data中身を Hiddenタグで返す
	*
	* @param   String  $modelName   モデル名
	* @return  String
	*/
	function hiddenVars($model = null, $options = array()) {
		
		$defaultOption = array('data' => array(), 'list' => false, 'listType' => true);
		$extractOption = Set::merge($defaultOption, $options);
		extract($extractOption);

		$ret = "";
		$keyStack = array();
		if ($list) {
			// リストを参照
			$this->list = $list;
			$this->listType = $listType;
		} else {
			
			$this->list = false;
		}
		if ($data) { // そのまま使用する
		}  else {
			
			$data = is_null($model) ? $this->data : $this->data[$model];
		}
		$this->_hiddenVarsNestParse($data, $keyStack, $ret, $model);
		return $ret;
	}

	function _hiddenVarsNestParse($data, &$keyStack, &$ret, $model = null) {
		
		if (is_array($data)) {
			foreach ($data as $key => $val) {
				
				array_push($keyStack, $key);
				$this->_hiddenVarsNestParse($val, $keyStack, $ret, $model);
				array_pop($keyStack);
			}
		} else {
			
			$check = true;
			if (is_array($this->list)) {
				if ($this->listType) {
					
					$check = false;
					foreach ($this->list as $k => $v) { if (in_array($v, (array)$keyStack)) { $check = true; break; } }
				} else {
					
					foreach ($this->list as $k => $v) { if (in_array($v, (array)$keyStack)) { $check = false; break; } }
				}
			}
			if ($check) {
				$ret .= $model ? $this->Form->hidden("{$model}." . implode('.' ,$keyStack)) . "\n" : $this->Form->hidden(implode('.' ,$keyStack)) . "\n";
			}
		}
	}
}
?>
