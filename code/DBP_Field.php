<?php

class DBP_Field extends ViewableData {
	
	protected $Label;
	protected $Table;
	
	function __construct($id) {
		parent::__construct();
		if(preg_match('/^(\w+)\.(\w+)$/i', $id, $match)) {
			$this->Table = $match[1];
			$this->Label = $match[2];
		} else {
			$this->Label = $id;
		}
	}
	
	function Spec() {
		if(!$this->Table) return false;
		$fl = DB::fieldList($this->Table);
		if(is_array($fl[$this->Label])) {
			$out ='';
			foreach($fl[$this->Label] as $key => $val) $out .= "$key: $val<br />";
			return $out;
		} else {
			return $fl[$this->Label];
		}
	}
	
	function type() {
		return preg_match('/^\w+/i', $this->Spec(), $match) ? strtolower($match[0]) : false;
	}
	
	function isText() {
		return $this->type() == 'text' || $this->type() == 'mediumtext';
	}
	
	function isBool() {
		return $this->type() == 'bool';
	}
	
	function Table() {
		return new DBP_Table($this->Table);
	}

	function Label() {
		return $this->Label;
	}

	function Ordered() {
		$vars = Controller::curr()->getRequest()->requestVars();
//		aDebug($vars);die();
		if(empty($vars['orderby']) || $vars['orderby'] != $this->Label) return false;
		$vars['orderdir'] = (isset($vars['orderdir']) && $vars['orderdir'] == 'DESC') ? 'DESC' : 'ASC';
		return $vars['orderdir'];
	}

}